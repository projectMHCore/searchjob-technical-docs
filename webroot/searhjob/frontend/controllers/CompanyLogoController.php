<?php
/**
 * Контроллер для работы с логотипами компаний
 */
session_start();

header('Content-Type: application/json');
if (!isset($_SESSION['token'])) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

require_once __DIR__ . '/../../backend/models/User.php';

$userModel = new User();
$userData = $userModel->getUserByToken($_SESSION['token']);

if (!$userData) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

// Проверяем, что пользователь - работодатель
if ($userData['role'] !== 'employer') {
    echo json_encode(['success' => false, 'message' => 'Недостатньо прав. Логотипи можуть завантажувати тільки роботодавці']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не дозволено']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'upload_company_logo':
        uploadCompanyLogo($userData);
        break;
    
    case 'remove_company_logo':
        removeCompanyLogo($userData);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Невідома дія']);
        break;
}

/**
 * Загрузка логотипа компании
 */
function uploadCompanyLogo($userData) {
    // Проверяем, есть ли файл
    if (!isset($_FILES['logo_file']) || $_FILES['logo_file']['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'Файл перевищує максимальний розмір',
            UPLOAD_ERR_FORM_SIZE => 'Файл перевищує максимальний розмір форми',
            UPLOAD_ERR_PARTIAL => 'Файл завантажено частково',
            UPLOAD_ERR_NO_FILE => 'Файл не завантажено',
            UPLOAD_ERR_NO_TMP_DIR => 'Відсутня тимчасова директорія',
            UPLOAD_ERR_CANT_WRITE => 'Неможливо записати файл',
            UPLOAD_ERR_EXTENSION => 'Завантаження зупинено розширенням'
        ];
        
        $error = $_FILES['logo_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        $message = $errorMessages[$error] ?? 'Невідома помилка завантаження';
        
        echo json_encode(['success' => false, 'message' => $message]);
        return;
    }
    
    $file = $_FILES['logo_file'];
    
    // Валидация файла
    $validation = validateLogoFile($file);
    if (!$validation['valid']) {
        echo json_encode(['success' => false, 'message' => $validation['message']]);
        return;
    }
    $logoDir = __DIR__ . '/../assets/uploads/company_logos/';
    if (!is_dir($logoDir)) {
        if (!mkdir($logoDir, 0755, true)) {
            echo json_encode(['success' => false, 'message' => 'Не вдалося створити директорію для логотипів']);
            return;
        }
        createLogoSecurityFile($logoDir);
    }

    removeOldLogo($userData);
    
    // Генерируем имя файла
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'company_logo_' . $userData['id'] . '_' . time() . '_' . uniqid() . '.' . $extension;
    $filePath = $logoDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $success = updateLogoInDatabase($userData['id'], 'assets/uploads/company_logos/' . $fileName);
          if ($success) {
            echo json_encode([
                'success' => true, 
                'message' => 'Логотип успішно завантажено!',
                'logo_path' => 'assets/uploads/company_logos/' . $fileName
            ]);
        } else {
            unlink($filePath);
            echo json_encode(['success' => false, 'message' => 'Помилка збереження в базі даних']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Помилка переміщення файлу']);
    }
}

/**
 * Удаление логотипа компании
 */
function removeCompanyLogo($userData) {
    removeOldLogo($userData);
    $success = updateLogoInDatabase($userData['id'], null);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Логотип успішно видалено!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Помилка видалення з бази даних']);
    }
}

/**
 * Валидация файла логотипа
 */
function validateLogoFile($file) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    $fileType = $file['type'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
        return ['valid' => false, 'message' => 'Недопустимий тип файлу. Дозволені: JPG, PNG, GIF, WEBP'];
    }
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'message' => 'Файл занадто великий. Максимальний розмір: 2MB'];
    }
    
    // Проверяем, что это действительно изображение
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['valid' => false, 'message' => 'Файл не є зображенням'];
    }
    
    $maxWidth = 1000;
    $maxHeight = 500;
    if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
        return ['valid' => false, 'message' => "Розмір зображення занадто великий. Максимум: {$maxWidth}x{$maxHeight}px"];
    }
    
    return ['valid' => true];
}

/**
 * Удаление старого логотипа
 */
function removeOldLogo($userData) {
    $oldLogo = $userData['company_logo'] ?? '';
    if (!empty($oldLogo)) {
        $logoPath = __DIR__ . '/../assets/uploads/company_logos/' . basename($oldLogo);
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }
    }
}

/**
 * Обновление пути логотипа в базе данных
 */
function updateLogoInDatabase($userId, $logoPath) {
    try {
        $config = require __DIR__ . '/../../backend/config/db.php';
        $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        
        if ($db->connect_error) {
            error_log("Database connection error: " . $db->connect_error);
            return false;
        }
        
        $stmt = $db->prepare("UPDATE users SET company_logo = ? WHERE id = ?");
        $stmt->bind_param("si", $logoPath, $userId);
        
        $success = $stmt->execute();
        
        $stmt->close();
        $db->close();
        
        return $success;
        
    } catch (Exception $e) {
        error_log("Error updating logo in database: " . $e->getMessage());
        return false;
    }
}

/**
 * Создание файла безопасности для директории логотипов
 */
function createLogoSecurityFile($logoDir) {
    $htaccessContent = "# Защита от выполнения PHP файлов\n";
    $htaccessContent .= "php_flag engine off\n\n";
    $htaccessContent .= "# Разрешаем только изображения\n";
    $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png|gif|webp)$\">\n";
    $htaccessContent .= "    Require all granted\n";
    $htaccessContent .= "</FilesMatch>\n\n";
    $htaccessContent .= "# Блокируем все остальные файлы\n";
    $htaccessContent .= "<FilesMatch \".*\">\n";
    $htaccessContent .= "    Require all denied\n";
    $htaccessContent .= "</FilesMatch>\n\n";
    $htaccessContent .= "# Запрещаем листинг директории\n";
    $htaccessContent .= "Options -Indexes\n";
    
    file_put_contents($logoDir . '.htaccess', $htaccessContent);

    $indexContent = "<?php\n";
    $indexContent .= "// Доступ запрещен\n";
    $indexContent .= "http_response_code(403);\n";
    $indexContent .= "exit('Access denied');\n";
    $indexContent .= "?>";
    
    file_put_contents($logoDir . 'index.php', $indexContent);
}
?>
