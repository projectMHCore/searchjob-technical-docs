<?php
// Диагностический скрипт для проверки поля company_logo
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h2>Диагностика поля company_logo</h2>";

try {
    // Подключаемся к базе данных
    $config = require __DIR__ . '/../backend/config/db.php';
    $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    
    if ($db->connect_error) {
        throw new Exception("Ошибка подключения: " . $db->connect_error);
    }
    
    echo "<h3>1. Проверка структуры таблицы users:</h3>";
    $result = $db->query("DESCRIBE users");
    
    $hasCompanyLogoField = false;
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            if ($row['Field'] === 'company_logo') {
                $hasCompanyLogoField = true;
                $highlight = 'style="background-color: #90EE90;"';
            } else {
                $highlight = '';
            }
            echo "<tr $highlight>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    if (!$hasCompanyLogoField) {
        echo "<p style='color: red;'>✗ Поле company_logo НЕ найдено! Добавляем...</p>";
        
        $sql = "ALTER TABLE users ADD COLUMN company_logo VARCHAR(255) NULL";
        if ($db->query($sql)) {
            echo "<p style='color: green;'>✓ Поле company_logo успешно добавлено!</p>";
            $hasCompanyLogoField = true;
        } else {
            echo "<p style='color: red;'>✗ Ошибка добавления поля: " . $db->error . "</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ Поле company_logo найдено!</p>";
    }
    
    echo "<h3>2. Проверка текущего пользователя:</h3>";
    if (isset($_SESSION['token'])) {
        echo "<p>Токен сессии: " . htmlspecialchars($_SESSION['token']) . "</p>";
        
        // Проверяем пользователя
        require_once __DIR__ . '/../backend/models/User.php';
        $userModel = new User();
        $userData = $userModel->getUserByToken($_SESSION['token']);
        
        if ($userData) {
            echo "<p style='color: green;'>✓ Пользователь найден:</p>";
            echo "<ul>";
            echo "<li>ID: " . $userData['id'] . "</li>";
            echo "<li>Login: " . htmlspecialchars($userData['login']) . "</li>";
            echo "<li>Role: " . htmlspecialchars($userData['role']) . "</li>";
            if ($hasCompanyLogoField) {
                echo "<li>Company Logo: " . (isset($userData['company_logo']) ? htmlspecialchars($userData['company_logo']) : 'NULL') . "</li>";
            }
            echo "</ul>";
            
            // Проверяем права
            if ($userData['role'] === 'employer') {
                echo "<p style='color: green;'>✓ Пользователь имеет права работодателя</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Пользователь НЕ является работодателем (role: " . $userData['role'] . ")</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Пользователь с таким токеном не найден</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Токен сессии не найден. Пользователь не авторизован.</p>";
        echo "<p><a href='/frontend/login.php'>Войти в систему</a></p>";
    }
    
    echo "<h3>3. Проверка директории для логотипов:</h3>";
    $logoDir = __DIR__ . '/assets/uploads/company_logos/';
    
    if (is_dir($logoDir)) {
        echo "<p style='color: green;'>✓ Директория существует: $logoDir</p>";
        
        $files = scandir($logoDir);
        $logoFiles = array_filter($files, function($file) {
            return !in_array($file, ['.', '..']);
        });
        
        if (count($logoFiles) > 0) {
            echo "<p>Найденные файлы:</p>";
            echo "<ul>";
            foreach ($logoFiles as $file) {
                echo "<li>" . htmlspecialchars($file) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Файлы не найдены.</p>";
        }
        
        // Проверяем права доступа
        if (is_writable($logoDir)) {
            echo "<p style='color: green;'>✓ Директория доступна для записи</p>";
        } else {
            echo "<p style='color: red;'>✗ Директория НЕ доступна для записи</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Директория НЕ существует: $logoDir</p>";
        
        if (mkdir($logoDir, 0755, true)) {
            echo "<p style='color: green;'>✓ Директория создана</p>";
        } else {
            echo "<p style='color: red;'>✗ Не удалось создать директорию</p>";
        }
    }
    
    echo "<h3>4. Тест записи в базу данных:</h3>";
    if ($hasCompanyLogoField && isset($userData) && $userData) {
        $testPath = 'assets/uploads/company_logos/test_logo.jpg';
        
        $stmt = $db->prepare("UPDATE users SET company_logo = ? WHERE id = ?");
        $stmt->bind_param("si", $testPath, $userData['id']);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Тестовая запись в базу данных прошла успешно</p>";
            
            // Проверяем, что записалось
            $checkStmt = $db->prepare("SELECT company_logo FROM users WHERE id = ?");
            $checkStmt->bind_param("i", $userData['id']);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();
            
            echo "<p>Значение в БД: " . htmlspecialchars($row['company_logo'] ?? 'NULL') . "</p>";
            
            // Очищаем тестовое значение
            $clearStmt = $db->prepare("UPDATE users SET company_logo = NULL WHERE id = ?");
            $clearStmt->bind_param("i", $userData['id']);
            $clearStmt->execute();
            
        } else {
            echo "<p style='color: red;'>✗ Ошибка записи в базу данных: " . $stmt->error . "</p>";
        }
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<br><hr><br>";
echo "<p><a href='/frontend/edit_profile.php'>Перейти к редактированию профиля</a></p>";
echo "<p><a href='/frontend/components/company_logo_upload.php'>Тестировать компонент загрузки логотипа</a></p>";
?>
