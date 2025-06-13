<?php
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Database Analysis</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; } .error { color: red; } .success { color: green; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>";
echo "</head><body>";
echo "<h1>🔍 Database Structure Analysis</h1>";

// Подключение к БД
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

if ($db->connect_error) {
    echo "<div class='error'>❌ Database connection error: " . $db->connect_error . "</div>";
    exit;
}

// 1. Проверяем AUTO_INCREMENT значение
echo "<div class='debug-section'>";
echo "<h2>1. AUTO_INCREMENT Settings</h2>";

$result = $db->query("SHOW TABLE STATUS LIKE 'users'");
if ($result && $result->num_rows > 0) {
    $tableInfo = $result->fetch_assoc();
    echo "<strong>Current AUTO_INCREMENT value:</strong> " . $tableInfo['Auto_increment'] . "<br>";
    echo "<strong>Table engine:</strong> " . $tableInfo['Engine'] . "<br>";
    echo "<strong>Rows count:</strong> " . $tableInfo['Rows'] . "<br>";
} else {
    echo "<div class='error'>❌ Could not get table info</div>";
}
echo "</div>";

// 2. Показываем структуру таблицы
echo "<div class='debug-section'>";
echo "<h2>2. Users Table Structure</h2>";

$result = $db->query("DESCRIBE users");
if ($result) {
    echo "<table>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>❌ Could not describe table</div>";
}
echo "</div>";

// 3. Показываем всех пользователей
echo "<div class='debug-section'>";
echo "<h2>3. All Users in Database</h2>";

$result = $db->query("SELECT id, login, email, role, created_at FROM users ORDER BY id");
if ($result) {
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Login</th><th>Email</th><th>Role</th><th>Created At</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['id'] == 44) ? 'style="background-color: yellow;"' : '';
            echo "<tr $highlight>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['login'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p><strong>Total users:</strong> " . $result->num_rows . "</p>";
    } else {
        echo "<div class='error'>❌ No users found in database</div>";
    }
} else {
    echo "<div class='error'>❌ Could not query users table</div>";
}
echo "</div>";

// 4. Проверяем пропуски в ID
echo "<div class='debug-section'>";
echo "<h2>4. ID Sequence Analysis</h2>";

$result = $db->query("SELECT id FROM users ORDER BY id");
if ($result && $result->num_rows > 0) {
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id'];
    }
    
    echo "<strong>User IDs:</strong> " . implode(', ', $ids) . "<br>";
    
    if (count($ids) > 0) {
        $minId = min($ids);
        $maxId = max($ids);
        $expectedCount = $maxId - $minId + 1;
        $actualCount = count($ids);
        
        echo "<strong>ID Range:</strong> $minId - $maxId<br>";
        echo "<strong>Expected sequential count:</strong> $expectedCount<br>";
        echo "<strong>Actual count:</strong> $actualCount<br>";
        
        if ($expectedCount != $actualCount) {
            echo "<div class='error'>⚠️ There are gaps in the ID sequence!</div>";
            
            // Найдем пропущенные ID
            $missing = [];
            for ($i = $minId; $i <= $maxId; $i++) {
                if (!in_array($i, $ids)) {
                    $missing[] = $i;
                }
            }
            if (!empty($missing)) {
                echo "<strong>Missing IDs:</strong> " . implode(', ', $missing) . "<br>";
            }
        } else {
            echo "<div class='success'>✅ ID sequence is continuous</div>";
        }
    }
}
echo "</div>";

// 5. Проверяем историю удалений (если есть лог таблица)
echo "<div class='debug-section'>";
echo "<h2>5. Database History Check</h2>";

// Проверяем, есть ли таблицы с историей
$tables = [];
$result = $db->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
}

echo "<strong>Available tables:</strong><br>";
foreach ($tables as $table) {
    echo "- $table<br>";
}

// Проверяем, есть ли записи о том, что база была очищена
if (in_array('user_tokens', $tables)) {
    echo "<h3>Token History:</h3>";
    $result = $db->query("SELECT COUNT(*) as count, MIN(created_at) as first_token, MAX(created_at) as last_token FROM user_tokens");
    if ($result && $result->num_rows > 0) {
        $tokenStats = $result->fetch_assoc();
        echo "<strong>Total tokens:</strong> " . $tokenStats['count'] . "<br>";
        echo "<strong>First token created:</strong> " . $tokenStats['first_token'] . "<br>";
        echo "<strong>Last token created:</strong> " . $tokenStats['last_token'] . "<br>";
    }
}
echo "</div>";

// 6. Анализ и рекомендации
echo "<div class='debug-section'>";
echo "<h2>6. Analysis & Recommendations</h2>";

$result = $db->query("SHOW TABLE STATUS LIKE 'users'");
if ($result && $result->num_rows > 0) {
    $tableInfo = $result->fetch_assoc();
    $autoIncrement = $tableInfo['Auto_increment'];
    $rowCount = $tableInfo['Rows'];
    
    if ($autoIncrement > ($rowCount + 1)) {
        echo "<div class='error'>";
        echo "<h3>🚨 EXPLANATION FOUND:</h3>";
        echo "<p>AUTO_INCREMENT value ($autoIncrement) is much higher than the actual number of rows ($rowCount).</p>";
        echo "<p><strong>This means:</strong></p>";
        echo "<ul>";
        echo "<li>Users were previously registered and then deleted</li>";
        echo "<li>The table was truncated but AUTO_INCREMENT was not reset</li>";
        echo "<li>Data was imported with specific ID values</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<h3>🔧 To Reset AUTO_INCREMENT:</h3>";
        echo "<p>If you want to start from ID 1, run this SQL command:</p>";
        echo "<code>ALTER TABLE users AUTO_INCREMENT = 1;</code>";
    } else {
        echo "<div class='success'>✅ AUTO_INCREMENT value seems normal</div>";
    }
}

echo "</div>";

$db->close();
echo "</body></html>";
?>
