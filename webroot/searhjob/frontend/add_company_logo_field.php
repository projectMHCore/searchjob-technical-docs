<?php
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Add Company Logo Field</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; } .error { color: red; } .success { color: green; } .warning { color: orange; } .btn { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; } .btn-danger { background: #dc3545; } .btn:hover { opacity: 0.8; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>";
echo "</head><body>";
echo "<h1>🏢 Добавление поля для логотипа компании</h1>";

// Проверяем авторизацию
if (empty($_SESSION) || !isset($_SESSION['user_id'])) {
    echo "<div class='error'>❌ Требуется авторизация для выполнения операций с базой данных</div>";
    echo "<a href='login.php'>Войти в систему</a>";
    echo "</body></html>";
    exit;
}

// Подключение к БД
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

if ($db->connect_error) {
    echo "<div class='error'>❌ Database connection error: " . $db->connect_error . "</div>";
    exit;
}

// Обработка POST запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_logo_field') {
        echo "<div class='debug-section'>";
        echo "<h2>🏢 Добавление поля company_logo в таблицу users</h2>";
        
        // Проверяем, существует ли уже поле
        $result = $db->query("DESCRIBE users");
        $fieldExists = false;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['Field'] === 'company_logo') {
                    $fieldExists = true;
                    break;
                }
            }
        }
        
        if ($fieldExists) {
            echo "<div class='warning'>⚠️ Поле company_logo уже существует в таблице users</div>";
        } else {
            // Добавляем поле
            $sql = "ALTER TABLE users ADD COLUMN company_logo VARCHAR(200) NULL AFTER avatar";
            
            if ($db->query($sql)) {
                echo "<div class='success'>✅ Поле company_logo успешно добавлено в таблицу users</div>";
                echo "<p><strong>Параметры поля:</strong></p>";
                echo "<ul>";
                echo "<li>Тип: VARCHAR(200)</li>";
                echo "<li>Может быть NULL</li>";
                echo "<li>Расположение: после поля avatar</li>";
                echo "</ul>";
            } else {
                echo "<div class='error'>❌ Ошибка добавления поля: " . $db->error . "</div>";
            }
        }
        echo "</div>";
    }
      if ($action === 'create_logo_directory') {
        echo "<div class='debug-section'>";
        echo "<h2>📁 Создание директории для логотипов</h2>";
        
        $logoDir = __DIR__ . '/assets/uploads/company_logos/';
        
        if (is_dir($logoDir)) {
            echo "<div class='warning'>⚠️ Директория $logoDir уже существует</div>";
        } else {
            if (mkdir($logoDir, 0755, true)) {
                echo "<div class='success'>✅ Директория для логотипов создана: $logoDir</div>";
                
                // Создаем .htaccess для безопасности
                $htaccessContent = "# Защита от выполнения PHP файлов\n";
                $htaccessContent .= "php_flag engine off\n\n";
                $htaccessContent .= "# Разрешаем только изображения\n";
                $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png|gif|webp)$\">\n";
                $htaccessContent .= "    Order allow,deny\n";
                $htaccessContent .= "    Allow from all\n";
                $htaccessContent .= "</FilesMatch>\n\n";
                $htaccessContent .= "# Блокируем все остальные файлы\n";
                $htaccessContent .= "<FilesMatch \".*\">\n";
                $htaccessContent .= "    Order deny,allow\n";
                $htaccessContent .= "    Deny from all\n";
                $htaccessContent .= "</FilesMatch>";
                
                if (file_put_contents($logoDir . '.htaccess', $htaccessContent)) {
                    echo "<p>✅ Файл .htaccess создан для безопасности</p>";
                } else {
                    echo "<p>⚠️ Не удалось создать .htaccess файл</p>";
                }
            } else {
                echo "<div class='error'>❌ Ошибка создания директории: " . error_get_last()['message'] . "</div>";
            }
        }
        echo "</div>";
    }
    
    if ($action === 'test_logo_upload') {
        echo "<div class='debug-section'>";
        echo "<h2>🧪 Тест загрузки логотипа</h2>";
        
        // Проверяем, что пользователь - работодатель
        $userId = $_SESSION['user_id'];
        $result = $db->query("SELECT role FROM users WHERE id = $userId");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($user['role'] === 'employer') {
                echo "<div class='success'>✅ Пользователь является работодателем</div>";
                echo "<p>Можно протестировать загрузку логотипа</p>";
                
                // Показываем форму загрузки
                echo "<form method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='action' value='upload_test_logo'>";
                echo "<p><label>Выберите файл логотипа:</label></p>";
                echo "<input type='file' name='logo_file' accept='image/*' required>";
                echo "<br><br>";
                echo "<button type='submit' class='btn'>📤 Загрузить тестовый логотип</button>";
                echo "</form>";
            } else {
                echo "<div class='warning'>⚠️ Текущий пользователь не является работодателем (роль: " . $user['role'] . ")</div>";
                echo "<p>Логотипы могут загружать только работодатели</p>";
            }
        }
        echo "</div>";
    }
    
    if ($action === 'upload_test_logo') {
        echo "<div class='debug-section'>";
        echo "<h2>📤 Загрузка тестового логотипа</h2>";
          if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $userId = $_SESSION['user_id'];
            $uploadDir = __DIR__ . '/assets/uploads/company_logos/';
            
            // Проверяем директорию
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileInfo = pathinfo($_FILES['logo_file']['name']);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExtension = strtolower($fileInfo['extension']);
            
            if (in_array($fileExtension, $allowedTypes)) {
                // Генерируем имя файла
                $fileName = 'company_logo_' . $userId . '_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $filePath)) {
                    // Обновляем базу данных
                    $relativePath = 'assets/uploads/company_logos/' . $fileName;
                    $stmt = $db->prepare("UPDATE users SET company_logo = ? WHERE id = ?");
                    $stmt->bind_param("si", $relativePath, $userId);
                    
                    if ($stmt->execute()) {
                        echo "<div class='success'>✅ Логотип успешно загружен!</div>";
                        echo "<p><strong>Файл:</strong> $fileName</p>";
                        echo "<p><strong>Путь в БД:</strong> $relativePath</p>";
                        echo "<p><strong>Размер:</strong> " . round($_FILES['logo_file']['size'] / 1024, 2) . " KB</p>";
                          // Показываем логотип
                        echo "<div style='margin-top: 15px;'>";
                        echo "<p><strong>Предварительный просмотр:</strong></p>";
                        echo "<img src='assets/uploads/company_logos/$fileName' style='max-width: 200px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;'>";
                        echo "</div>";
                    } else {
                        echo "<div class='error'>❌ Ошибка обновления базы данных: " . $db->error . "</div>";
                        unlink($filePath); // Удаляем файл при ошибке БД
                    }
                } else {
                    echo "<div class='error'>❌ Ошибка перемещения файла</div>";
                }
            } else {
                echo "<div class='error'>❌ Недопустимый тип файла. Разрешены: " . implode(', ', $allowedTypes) . "</div>";
            }
        } else {
            echo "<div class='error'>❌ Ошибка загрузки файла: " . $_FILES['logo_file']['error'] . "</div>";
        }
        echo "</div>";
    }
}

// Показываем текущее состояние таблицы
echo "<div class='debug-section'>";
echo "<h2>📊 Структура таблицы users</h2>";

$result = $db->query("DESCRIBE users");
if ($result) {
    echo "<table>";
    echo "<tr><th>Поле</th><th>Тип</th><th>Null</th><th>Ключ</th><th>По умолчанию</th><th>Дополнительно</th></tr>";
    
    $logoFieldExists = false;
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['Field'] === 'company_logo') ? 'style="background-color: yellow;"' : '';
        if ($row['Field'] === 'company_logo') {
            $logoFieldExists = true;
        }
        
        echo "<tr $highlight>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if ($logoFieldExists) {
        echo "<div class='success'>✅ Поле company_logo существует</div>";
    } else {
        echo "<div class='warning'>⚠️ Поле company_logo не найдено</div>";
    }
} else {
    echo "<div class='error'>❌ Не удалось получить структуру таблицы</div>";
}
echo "</div>";

// Проверяем директорию для логотипов
echo "<div class='debug-section'>";
echo "<h2>📁 Состояние директории логотипов</h2>";

$logoDir = __DIR__ . '/assets/uploads/company_logos/';
$uploadsDir = __DIR__ . '/assets/uploads/';

echo "<p><strong>Путь к директории логотипов:</strong> $logoDir</p>";

if (is_dir($uploadsDir)) {
    echo "<p>✅ Основная директория uploads существует</p>";
} else {
    echo "<p>❌ Основная директория uploads НЕ существует</p>";
}

if (is_dir($logoDir)) {
    echo "<p>✅ Директория company_logos существует</p>";
    
    // Показываем файлы в директории
    $files = scandir($logoDir);
    $logoFiles = array_filter($files, function($file) {
        return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
    });
    
    if (!empty($logoFiles)) {
        echo "<p><strong>Загруженные логотипы:</strong></p>";
        echo "<ul>";
        foreach ($logoFiles as $file) {
            echo "<li>$file</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>📭 Логотипов пока нет</p>";
    }
} else {
    echo "<p>❌ Директория company_logos НЕ существует</p>";
}
echo "</div>";

// Показываем пользователей-работодателей
echo "<div class='debug-section'>";
echo "<h2>🏢 Работодатели в системе</h2>";

// Проверяем, существует ли поле company_logo перед запросом
$logoFieldExists = false;
$result = $db->query("DESCRIBE users");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row['Field'] === 'company_logo') {
            $logoFieldExists = true;
            break;
        }
    }
}

// Выполняем запрос в зависимости от наличия поля
if ($logoFieldExists) {
    $result = $db->query("SELECT id, login, company_name, company_logo FROM users WHERE role = 'employer' ORDER BY id");
} else {
    $result = $db->query("SELECT id, login, company_name FROM users WHERE role = 'employer' ORDER BY id");
}
if ($result && $result->num_rows > 0) {
    echo "<table>";
    if ($logoFieldExists) {
        echo "<tr><th>ID</th><th>Логин</th><th>Название компании</th><th>Логотип</th></tr>";
    } else {
        echo "<tr><th>ID</th><th>Логин</th><th>Название компании</th><th>Логотип</th></tr>";
    }
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['login'] . "</td>";
        echo "<td>" . ($row['company_name'] ?? 'Не указано') . "</td>";
        if ($logoFieldExists) {
            echo "<td>" . ($row['company_logo'] ?? 'Нет логотипа') . "</td>";
        } else {
            echo "<td>Поле не создано</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>📭 Работодателей в системе нет</p>";
}
echo "</div>";

// Показываем формы действий
echo "<div class='debug-section'>";
echo "<h2>🛠️ Доступные действия</h2>";

echo "<form method='post' style='margin-bottom: 10px;'>";
echo "<input type='hidden' name='action' value='add_logo_field'>";
echo "<button type='submit' class='btn'>🏢 Добавить поле company_logo</button>";
echo "<p><small>Добавляет поле company_logo VARCHAR(200) NULL в таблицу users</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;'>";
echo "<input type='hidden' name='action' value='create_logo_directory'>";
echo "<button type='submit' class='btn'>📁 Создать директорию для логотипов</button>";
echo "<p><small>Создает директорию /uploads/company_logos/ с защитным .htaccess</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;'>";
echo "<input type='hidden' name='action' value='test_logo_upload'>";
echo "<button type='submit' class='btn'>🧪 Протестировать загрузку логотипа</button>";
echo "<p><small>Показывает форму для тестовой загрузки логотипа (только для работодателей)</small></p>";
echo "</form>";

echo "</div>";

// Информационная секция
echo "<div class='debug-section'>";
echo "<h2>ℹ️ Информация</h2>";
echo "<p><strong>Поле company_logo</strong> - будет хранить путь к файлу логотипа компании относительно корня сайта.</p>";
echo "<p><strong>Директория company_logos</strong> - будет содержать загруженные логотипы компаний.</p>";
echo "<p><strong>Безопасность</strong> - .htaccess файл запрещает выполнение PHP кода в директории с логотипами.</p>";
echo "<p><strong>Формат имени файла</strong> - company_logo_{user_id}_{timestamp}_{uniqid}.{extension}</p>";
echo "</div>";

// Навигация
echo "<div class='debug-section'>";
echo "<h2>🔗 Навигация</h2>";
echo "<p><a href='debug_user_id.php'>🔍 Диагностика User ID</a></p>";
echo "<p><a href='debug_database.php'>🗄️ Анализ базы данных</a></p>";
echo "<p><a href='reset_auto_increment.php'>🔄 Сброс AUTO_INCREMENT</a></p>";
echo "<p><a href='profile.php'>👤 Вернуться к профилю</a></p>";
echo "</div>";

$db->close();
echo "</body></html>";
?>
