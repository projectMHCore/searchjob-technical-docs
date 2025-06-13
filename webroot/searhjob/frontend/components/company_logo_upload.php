<?php
// Компонент для загрузки логотипа компании

// НЕ запускаем сессию здесь - она должна быть запущена в родительской странице
// Проверяем, что сессия уже запущена
if (session_status() !== PHP_SESSION_ACTIVE) {
    echo '<p style="color: red;">Ошибка: сессия не активна. Компонент должен вызываться из страницы с активной сессией.</p>';
    return;
}

// Проверяем авторизацию только для POST запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['token'])) {
        echo json_encode(['success' => false, 'error' => 'Не авторизован']);
        exit;
    }

    require_once __DIR__ . '/../../backend/models/User.php';

    $userModel = new User();
    $userData = $userModel->getUserByToken($_SESSION['token']);

    if (!$userData || $userData['role'] !== 'employer') {
        echo json_encode(['success' => false, 'error' => 'Недостаточно прав']);
        exit;
    }

    $action = $_POST['action'] ?? '';
    
    if ($action === 'upload_logo') {
        // Проверяем, есть ли файл
        if (!isset($_FILES['company_logo']) || $_FILES['company_logo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'Файл не завантажено']);
            exit;
        }
        
        $file = $_FILES['company_logo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Проверяем тип файла
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'error' => 'Недопустимий формат файлу. Дозволені: JPG, PNG, GIF, WEBP']);
            exit;
        }
        
        // Проверяем размер файла
        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'error' => 'Файл занадто великий. Максимум 5MB']);
            exit;
        }
        
        // Проверяем, что это действительно изображение
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            echo json_encode(['success' => false, 'error' => 'Файл не є зображенням']);
            exit;
        }        // Создаем директорию для логотипов если не существует
        $logoDir = __DIR__ . '/../assets/uploads/company_logos/';
        if (!is_dir($logoDir)) {
            mkdir($logoDir, 0755, true);
        }          // Удаляем старый логотип если есть
        $oldLogo = $userData['company_logo'] ?? '';
        if ($oldLogo && file_exists(__DIR__ . '/../assets/uploads/company_logos/' . basename($oldLogo))) {
            unlink(__DIR__ . '/../assets/uploads/company_logos/' . basename($oldLogo));
        }
        
        // Генерируем имя файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'company_logo_' . $userData['id'] . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filePath = $logoDir . $fileName;
          // Перемещаем файл
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Обновляем путь в базе данных
            $config = require __DIR__ . '/../../backend/config/db.php';
            $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
            
            if ($db->connect_error) {
                unlink($filePath);
                echo json_encode(['success' => false, 'error' => 'Помилка підключення до БД: ' . $db->connect_error]);
                exit;
            }
            
            $logoPath = 'assets/uploads/company_logos/' . $fileName;
            $stmt = $db->prepare("UPDATE users SET company_logo = ? WHERE id = ?");
            
            if (!$stmt) {
                unlink($filePath);
                $db->close();
                echo json_encode(['success' => false, 'error' => 'Помилка підготовки запиту: ' . $db->error]);
                exit;
            }
            
            $stmt->bind_param("si", $logoPath, $userData['id']);
            
            if ($stmt->execute()) {
                // Проверяем, что запись действительно обновилась
                $affected_rows = $stmt->affected_rows;
                $stmt->close();
                
                if ($affected_rows > 0) {
                    // Дополнительная проверка - читаем из БД
                    $checkStmt = $db->prepare("SELECT company_logo FROM users WHERE id = ?");
                    $checkStmt->bind_param("i", $userData['id']);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();
                    $row = $result->fetch_assoc();
                    $checkStmt->close();
                    
                    $db->close();
                    
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Логотип успішно завантажено!',
                        'logo_path' => '/' . $logoPath,
                        'debug' => [
                            'affected_rows' => $affected_rows,
                            'db_value' => $row['company_logo'] ?? 'NULL'
                        ]
                    ]);
                } else {
                    unlink($filePath);
                    $db->close();
                    echo json_encode(['success' => false, 'error' => 'Запис не оновлено (affected_rows = 0)']);
                }
            } else {
                // Удаляем файл при ошибке БД
                unlink($filePath);
                $error_msg = $stmt->error;
                $stmt->close();
                $db->close();
                echo json_encode(['success' => false, 'error' => 'Помилка виконання запиту: ' . $error_msg]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Помилка завантаження файлу']);
        }
        exit;
    }
      if ($action === 'delete_logo') {        // Удаляем логотип
        $oldLogo = $userData['company_logo'] ?? '';
        if ($oldLogo) {
            $logoDir = __DIR__ . '/../assets/uploads/company_logos/';
            $fullPath = $logoDir . basename($oldLogo);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            // Обновляем базу данных
            $config = require __DIR__ . '/../../backend/config/db.php';
            $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
            
            $stmt = $db->prepare("UPDATE users SET company_logo = NULL WHERE id = ?");
            $stmt->bind_param("i", $userData['id']);
            
            if ($stmt->execute()) {
                $db->close();
                echo json_encode(['success' => true, 'message' => 'Логотип видалено!']);
            } else {
                $db->close();
                echo json_encode(['success' => false, 'error' => 'Помилка видалення з бази даних']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Логотип не знайдено']);
        }
        exit;
    }
}

// Если это GET запрос, отображаем компонент
// Проверяем авторизацию для GET запросов
if (!isset($_SESSION['token'])) {
    echo '<p style="color: red;">Вы не авторизованы. <a href="../login.php">Войти в систему</a></p>';
    return;
}

require_once __DIR__ . '/../../backend/models/User.php';
$userModel = new User();
$userData = $userModel->getUserByToken($_SESSION['token']);

if (!$userData) {
    echo '<p style="color: red;">Пользователь не найден.</p>';
    return;
}

if ($userData['role'] !== 'employer') {
    echo '<p style="color: orange;">Только работодатели могут загружать логотипы компаний.</p>';
    return;
}

$currentLogo = $userData['company_logo'] ?? '';
$hasLogo = !empty($currentLogo) && file_exists(__DIR__ . '/../' . $currentLogo);
?>

<div class="logo-upload-component">
    <h3>
        <i class="fas fa-image"></i>
        Логотип компанії
    </h3>
    
    <div class="logo-preview-section">
        <?php if ($hasLogo): ?>
            <div class="logo-preview">
                <img src="/<?= htmlspecialchars($currentLogo) ?>" alt="Логотип компанії" id="logoImage">
                <div class="logo-overlay">
                    <button type="button" class="overlay-btn" onclick="deleteCompanyLogo()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="logo-placeholder" id="logoPlaceholder">
                <i class="fas fa-building"></i>
                <span>Немає логотипу</span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="logo-actions">
        <label class="btn btn-primary" for="logoFileInput">
            <i class="fas fa-upload"></i>
            <?= $hasLogo ? 'Змінити логотип' : 'Завантажити логотип' ?>
        </label>
        <input type="file" id="logoFileInput" accept="image/*" style="display: none;" onchange="uploadCompanyLogo(this)">
        
        <?php if ($hasLogo): ?>
            <button type="button" class="btn btn-danger" onclick="deleteCompanyLogo()">
                <i class="fas fa-trash"></i>
                Видалити
            </button>
        <?php endif; ?>
    </div>
    
    <div class="logo-info">
        <small>
            <i class="fas fa-info-circle"></i>
            Допустимі формати: JPG, PNG, GIF, WEBP. Максимальний розмір: 5MB
        </small>
    </div>
    
    <div id="logoUploadMessage" class="upload-message" style="display: none;"></div>
</div>

<style>
.logo-upload-component {
    background: white;
    border: 1px solid #e1e8ed;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.logo-upload-component h3 {
    margin: 0 0 20px 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logo-preview-section {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.logo-preview {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e1e8ed;
    transition: all 0.3s ease;
}

.logo-preview:hover {
    border-color: #eaa850;
}

.logo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.logo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.logo-preview:hover .logo-overlay {
    opacity: 1;
}

.overlay-btn {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.overlay-btn:hover {
    transform: scale(1.1);
}

.logo-placeholder {
    width: 150px;
    height: 150px;
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    background: #f8fafc;
    transition: all 0.3s ease;
}

.logo-placeholder:hover {
    border-color: #eaa850;
    color: #eaa850;
}

.logo-placeholder i {
    font-size: 2rem;
    margin-bottom: 8px;
}

.logo-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 15px;
}

.logo-info {
    text-align: center;
    color: #64748b;
}

.logo-info i {
    color: #3b82f6;
}

.upload-message {
    margin-top: 15px;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
}

.upload-message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.upload-message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #eaa850;
    color: white;
}

.btn-primary:hover {
    background: #d4922a;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}
</style>

<script>
async function uploadCompanyLogo(input) {
    if (!input.files[0]) return;
    
    const formData = new FormData();
    formData.append('company_logo', input.files[0]);
    formData.append('action', 'upload_logo');
    
    const messageDiv = document.getElementById('logoUploadMessage');
    messageDiv.style.display = 'block';
    messageDiv.className = 'upload-message';
    messageDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Завантаження...';
    
    try {
        const response = await fetch('/frontend/components/company_logo_upload.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            messageDiv.className = 'upload-message success';
            messageDiv.innerHTML = '<i class="fas fa-check"></i> ' + result.message;
            
            // Обновляем интерфейс
            const placeholder = document.getElementById('logoPlaceholder');
            if (placeholder) {
                placeholder.outerHTML = `
                    <div class="logo-preview">
                        <img src="${result.logo_path}" alt="Логотип компанії" id="logoImage">
                        <div class="logo-overlay">
                            <button type="button" class="overlay-btn" onclick="deleteCompanyLogo()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('logoImage').src = result.logo_path;
            }
            
            // Обновляем кнопки
            location.reload();
            
        } else {
            messageDiv.className = 'upload-message error';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + result.error;
        }
    } catch (error) {
        messageDiv.className = 'upload-message error';
        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Помилка мережі';
    }
    
    // Очищаем input
    input.value = '';
    
    // Скрываем сообщение через 5 секунд
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}

async function deleteCompanyLogo() {
    if (!confirm('Ви впевнені, що хочете видалити логотип?')) return;
    
    const formData = new FormData();
    formData.append('action', 'delete_logo');
    
    const messageDiv = document.getElementById('logoUploadMessage');
    messageDiv.style.display = 'block';
    messageDiv.className = 'upload-message';
    messageDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Видалення...';
    
    try {
        const response = await fetch('/frontend/components/company_logo_upload.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            messageDiv.className = 'upload-message success';
            messageDiv.innerHTML = '<i class="fas fa-check"></i> ' + result.message;
            
            // Перезагружаем страницу для обновления интерфейса
            setTimeout(() => {
                location.reload();
            }, 1000);
              } else {
            messageDiv.className = 'upload-message error';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + result.error;
        }
    } catch (error) {
        messageDiv.className = 'upload-message error';
        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Помилка мережі';
    }
}
</script>
