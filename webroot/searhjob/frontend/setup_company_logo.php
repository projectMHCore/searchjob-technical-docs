<?php
// Простой скрипт для добавления поля company_logo в таблицу users
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Подключаемся к базе данных
    $config = require __DIR__ . '/../backend/config/db.php';
    $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    
    if ($db->connect_error) {
        throw new Exception("Ошибка подключения: " . $db->connect_error);
    }
    
    echo "<h2>Настройка поля company_logo</h2>";
    
    // Проверяем, существует ли уже поле
    $result = $db->query("DESCRIBE users company_logo");
    
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: orange;'>✓ Поле company_logo уже существует в таблице users</p>";
    } else {
        // Добавляем поле
        $sql = "ALTER TABLE users ADD COLUMN company_logo VARCHAR(255) NULL";
        
        if ($db->query($sql)) {
            echo "<p style='color: green;'>✓ Поле company_logo успешно добавлено в таблицу users</p>";
        } else {
            throw new Exception("Ошибка добавления поля: " . $db->error);
        }
    }
    
    // Проверяем структуру таблицы
    echo "<h3>Текущая структура таблицы users:</h3>";
    $result = $db->query("DESCRIBE users");
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['Field'] === 'company_logo') ? 'style="background-color: #90EE90;"' : '';
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
    
    // Создаем директорию для логотипов
    $logoDir = __DIR__ . '/assets/uploads/company_logos/';
    
    if (!is_dir($logoDir)) {
        if (mkdir($logoDir, 0755, true)) {
            echo "<p style='color: green;'>✓ Директория $logoDir создана</p>";
        } else {
            echo "<p style='color: red;'>✗ Не удалось создать директорию $logoDir</p>";
        }
    } else {
        echo "<p style='color: orange;'>✓ Директория $logoDir уже существует</p>";
    }
    
    $db->close();
    
    echo "<br><p style='color: blue; font-weight: bold;'>Настройка завершена! Теперь можно тестировать загрузку логотипов.</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
