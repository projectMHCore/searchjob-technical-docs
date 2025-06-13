<?php
// Тест-скрипт для проверки логотипов
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Тест логотипов компаний</h2>";

try {
    if (!isset($_SESSION['token'])) {
        echo "<p style='color: red;'>Не авторизован. <a href='/frontend/login.php'>Войти</a></p>";
        exit;
    }

    require_once __DIR__ . '/../backend/models/User.php';
    
    $userModel = new User();
    $userData = $userModel->getUserByToken($_SESSION['token']);
    
    if (!$userData) {
        echo "<p style='color: red;'>Пользователь не найден</p>";
        exit;
    }
    
    echo "<h3>Информация о пользователе:</h3>";
    echo "<p>ID: " . $userData['id'] . "</p>";
    echo "<p>Login: " . htmlspecialchars($userData['login']) . "</p>";
    echo "<p>Role: " . htmlspecialchars($userData['role']) . "</p>";
    
    $currentLogo = $userData['company_logo'] ?? '';
    echo "<p>Company Logo в БД: " . ($currentLogo ? htmlspecialchars($currentLogo) : 'NULL') . "</p>";
    
    if ($currentLogo) {
        $fullPath = __DIR__ . '/' . $currentLogo;
        echo "<p>Полный путь к файлу: " . $fullPath . "</p>";
        echo "<p>Файл существует: " . (file_exists($fullPath) ? 'ДА' : 'НЕТ') . "</p>";
        
        if (file_exists($fullPath)) {
            echo "<p>Размер файла: " . filesize($fullPath) . " байт</p>";
            echo "<h4>Предпросмотр логотипа:</h4>";
            echo "<img src='/" . htmlspecialchars($currentLogo) . "' alt='Логотип' style='max-width: 200px; max-height: 200px; border: 1px solid #ccc;'>";
        }
    }
    
    echo "<h3>Проверка директории логотипов:</h3>";
    $logoDir = __DIR__ . '/assets/uploads/company_logos/';
    echo "<p>Директория: $logoDir</p>";
    echo "<p>Существует: " . (is_dir($logoDir) ? 'ДА' : 'НЕТ') . "</p>";
    
    if (is_dir($logoDir)) {
        $files = scandir($logoDir);
        $logoFiles = array_filter($files, function($file) {
            return !in_array($file, ['.', '..']);
        });
        
        echo "<h4>Файлы в директории:</h4>";
        if (count($logoFiles) > 0) {
            echo "<ul>";
            foreach ($logoFiles as $file) {
                $filePath = $logoDir . $file;
                $fileSize = filesize($filePath);
                echo "<li>";
                echo htmlspecialchars($file) . " (" . $fileSize . " байт)";
                echo " <a href='/assets/uploads/company_logos/" . urlencode($file) . "' target='_blank'>Открыть</a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Файлов нет</p>";
        }
    }
    
    // Проверим, есть ли файлы, принадлежащие этому пользователю
    echo "<h3>Поиск файлов пользователя:</h3>";
    if (is_dir($logoDir)) {
        $userPattern = 'company_logo_' . $userData['id'] . '_';
        $userFiles = array_filter(scandir($logoDir), function($file) use ($userPattern) {
            return strpos($file, $userPattern) === 0;
        });
        
        if (count($userFiles) > 0) {
            echo "<p style='color: green;'>Найдены файлы пользователя:</p>";
            echo "<ul>";
            foreach ($userFiles as $file) {
                $filePath = 'assets/uploads/company_logos/' . $file;
                echo "<li>";
                echo htmlspecialchars($file);
                echo " <a href='/" . $filePath . "' target='_blank'>Открыть</a>";
                
                // Проверим, нужно ли привязать этот файл к пользователю
                if (empty($currentLogo)) {
                    echo " <a href='?bind_file=" . urlencode($file) . "' style='color: blue;'>[Привязать к профилю]</a>";
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Файлов пользователя не найдено</p>";
        }
    }
      // Обработка привязки файла
    if (isset($_GET['bind_file'])) {
        $fileName = $_GET['bind_file'];
        $filePath = 'assets/uploads/company_logos/' . $fileName;
        
        echo "<h4>Попытка привязки файла:</h4>";
        echo "<p>Файл: " . htmlspecialchars($fileName) . "</p>";
        echo "<p>Путь: " . htmlspecialchars($filePath) . "</p>";
        echo "<p>Полный путь: " . htmlspecialchars(__DIR__ . '/' . $filePath) . "</p>";
        echo "<p>Файл существует: " . (file_exists(__DIR__ . '/' . $filePath) ? 'ДА' : 'НЕТ') . "</p>";
        
        if (file_exists(__DIR__ . '/' . $filePath)) {
            $config = require __DIR__ . '/../backend/config/db.php';
            $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
            
            if ($db->connect_error) {
                echo "<p style='color: red;'>✗ Ошибка подключения к БД: " . $db->connect_error . "</p>";
            } else {
                echo "<p style='color: green;'>✓ Подключение к БД успешно</p>";
                
                // Проверим текущее значение
                $checkStmt = $db->prepare("SELECT company_logo FROM users WHERE id = ?");
                $checkStmt->bind_param("i", $userData['id']);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $currentData = $result->fetch_assoc();
                $checkStmt->close();
                
                echo "<p>Текущее значение в БД: " . ($currentData['company_logo'] ?? 'NULL') . "</p>";
                
                $stmt = $db->prepare("UPDATE users SET company_logo = ? WHERE id = ?");
                if (!$stmt) {
                    echo "<p style='color: red;'>✗ Ошибка подготовки запроса: " . $db->error . "</p>";
                } else {
                    $stmt->bind_param("si", $filePath, $userData['id']);
                    
                    echo "<p>Выполняем SQL: UPDATE users SET company_logo = '$filePath' WHERE id = {$userData['id']}</p>";
                    
                    if ($stmt->execute()) {
                        $affected_rows = $stmt->affected_rows;
                        echo "<p style='color: green;'>✓ SQL запрос выполнен успешно!</p>";
                        echo "<p>Затронуто строк: $affected_rows</p>";
                        
                        if ($affected_rows > 0) {
                            // Проверяем, что действительно записалось
                            $verifyStmt = $db->prepare("SELECT company_logo FROM users WHERE id = ?");
                            $verifyStmt->bind_param("i", $userData['id']);
                            $verifyStmt->execute();
                            $verifyResult = $verifyStmt->get_result();
                            $newData = $verifyResult->fetch_assoc();
                            $verifyStmt->close();
                            
                            echo "<p style='color: blue;'>Новое значение в БД: " . htmlspecialchars($newData['company_logo'] ?? 'NULL') . "</p>";
                            echo "<p style='color: green;'>✓ Файл успешно привязан к профилю!</p>";
                            echo "<script>setTimeout(() => location.reload(), 3000);</script>";
                        } else {
                            echo "<p style='color: orange;'>⚠ Ни одна строка не была изменена. Возможно, значение уже было таким же.</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>✗ Ошибка выполнения запроса: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                }
            }
            $db->close();
        } else {
            echo "<p style='color: red;'>✗ Файл не найден!</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<br><hr><br>";
echo "<p><a href='/frontend/edit_profile.php'>← Назад к редактированию профиля</a></p>";
echo "<p><a href='/frontend/components/company_logo_upload.php'>Тестировать загрузку логотипа</a></p>";
?>
