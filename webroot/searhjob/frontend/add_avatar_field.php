<?php
/**
 * Скрипт для добавления поля avatar в таблицу users
 */

// Подключение к БД
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}

echo "<h1>Добавление поля avatar в таблицу users</h1>";

// Проверяем, существует ли уже поле avatar
$result = $db->query("SHOW COLUMNS FROM users LIKE 'avatar'");

if ($result->num_rows > 0) {
    echo "<p style='color: orange;'>⚠️ Поле 'avatar' уже существует в таблице users</p>";
} else {
    echo "<h2>Добавляем поле avatar...</h2>";
    
    // Добавляем поле avatar
    $sql = "ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER company_industry";
    
    if ($db->query($sql)) {
        echo "<p style='color: green;'>✅ Поле 'avatar' успешно добавлено в таблицу users</p>";
    } else {
        echo "<p style='color: red;'>❌ Ошибка добавления поля: " . $db->error . "</p>";
    }
}

// Показываем структуру таблицы
echo "<h2>Текущая структура таблицы users:</h2>";
$result = $db->query("DESCRIBE users");

if ($result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        $style = ($row['Field'] === 'avatar') ? 'background: #d4edda;' : '';
        echo "<tr style='$style'>";
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

$db->close();

echo "<br><a href='profile.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Вернуться к профилю</a>";
echo " <a href='avatar_test.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Тест аватаров</a>";
?>
