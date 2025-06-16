<?php
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Скидання AUTO_INCREMENT</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; } .error { color: red; } .success { color: green; } .warning { color: orange; } .btn { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; } .btn-danger { background: #dc3545; } .btn:hover { opacity: 0.8; }</style>";
echo "</head><body>";
echo "<h1>🔄 Скидання AUTO_INCREMENT</h1>";

// Проверяем авторизацию администратора (опционально)
if (empty($_SESSION) || !isset($_SESSION['user_id'])) {
    echo "<div class='error'>❌ Потрібна авторизація для виконання операцій з базою даних</div>";
    echo "<a href='login.php'>Увійти в систему</a>";
    echo "</body></html>";
    exit;
}

// Подключение к БД
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

if ($db->connect_error) {
    echo "<div class='error'>❌ Помилка підключення до бази даних: " . $db->connect_error . "</div>";
    exit;
}

// Обработка POST запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
      if ($action === 'reset_auto_increment') {
        echo "<div class='debug-section'>";
        echo "<h2>🔄 Скидання AUTO_INCREMENT для таблиці users</h2>";
        
        $result = $db->query("SHOW TABLE STATUS LIKE 'users'");
        if ($result && $result->num_rows > 0) {
            $tableInfo = $result->fetch_assoc();
            $currentAutoIncrement = $tableInfo['Auto_increment'];
            $rowCount = $tableInfo['Rows'];
            
            echo "<p><strong>Поточний AUTO_INCREMENT:</strong> $currentAutoIncrement</p>";
            echo "<p><strong>Кількість записів:</strong> $rowCount</p>";
            
            if ($rowCount == 0) {
                if ($db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
                    echo "<div class='success'>✅ AUTO_INCREMENT успішно скинуто на 1</div>";
                } else {
                    echo "<div class='error'>❌ Помилка скидання AUTO_INCREMENT: " . $db->error . "</div>";
                }
            } else {
                $result = $db->query("SELECT MAX(id) as max_id FROM users");
                if ($result) {
                    $maxId = $result->fetch_assoc()['max_id'];
                    $newAutoIncrement = $maxId + 1;
                    
                    if ($db->query("ALTER TABLE users AUTO_INCREMENT = $newAutoIncrement")) {
                        echo "<div class='success'>✅ AUTO_INCREMENT скинуто на $newAutoIncrement (наступний після максимального ID $maxId)</div>";
                    } else {
                        echo "<div class='error'>❌ Помилка скидання AUTO_INCREMENT: " . $db->error . "</div>";
                    }
                }
            }
        }
        echo "</div>";
    }
      if ($action === 'reset_to_one') {
        echo "<div class='debug-section'>";
        echo "<h2>⚠️ ПРИМУСОВЕ скидання AUTO_INCREMENT на 1</h2>";
        
        $result = $db->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $count = $result->fetch_assoc()['count'];
            
            if ($count > 0) {
                echo "<div class='warning'>⚠️ УВАГА: В таблиці є $count записів!</div>";
                echo "<p>Примусове скидання AUTO_INCREMENT на 1 може призвести до конфліктів при додаванні нових записів.</p>";
            }
            
            if ($db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
                echo "<div class='success'>✅ AUTO_INCREMENT примусово скинуто на 1</div>";
                echo "<p><strong>Рекомендація:</strong> Якщо в таблиці є записи, краще видалити їх перед скиданням або використовувати звичайне скидання.</p>";
            } else {
                echo "<div class='error'>❌ Помилка примусового скидання: " . $db->error . "</div>";
            }
        }
        echo "</div>";
    }
      if ($action === 'delete_all_users') {
        echo "<div class='debug-section'>";
        echo "<h2>🗑️ Видалення всіх користувачів</h2>";
        
        $db->query("SET FOREIGN_KEY_CHECKS = 0");
        
        $success = true;
        $deletedCount = 0;
        
        $tables = ['user_tokens', 'applications', 'job_applications'];
        foreach ($tables as $table) {
            $result = $db->query("SELECT COUNT(*) as count FROM $table");
            if ($result) {
                $count = $result->fetch_assoc()['count'];
                if ($count > 0) {
                    if ($db->query("DELETE FROM $table")) {
                        echo "<p>✅ Видалено $count записів з таблиці $table</p>";
                    } else {
                        echo "<p>❌ Помилка видалення з таблиці $table: " . $db->error . "</p>";
                        $success = false;
                    }
                }
            }
        }
        
        $result = $db->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $userCount = $result->fetch_assoc()['count'];
            if ($userCount > 0) {
                if ($db->query("DELETE FROM users")) {
                    echo "<p>✅ Видалено $userCount користувачів</p>";
                    $deletedCount = $userCount;
                } else {
                    echo "<p>❌ Помилка видалення користувачів: " . $db->error . "</p>";
                    $success = false;
                }
            } else {
                echo "<p>ℹ️ Таблиця користувачів вже порожня</p>";
            }
        }
        
        if ($success && $db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
            echo "<p>✅ AUTO_INCREMENT скинуто на 1</p>";
        } else if ($success) {
            echo "<p>❌ Помилка скидання AUTO_INCREMENT: " . $db->error . "</p>";
        }
        
        $db->query("SET FOREIGN_KEY_CHECKS = 1");
        
        if ($success && $deletedCount > 0) {
            echo "<div class='success'>✅ Всі користувачі та пов'язані дані успішно видалені. AUTO_INCREMENT скинуто.</div>";
            echo "<div class='warning'>⚠️ Увага: Ваша поточна сесія буде недійсною. <a href='logout.php'>Вийти з системи</a></div>";
        } else if ($success) {
            echo "<div class='success'>✅ Операція завершена. База даних була порожньою.</div>";
        }
        
        echo "</div>";
    }
}

echo "<div class='debug-section'>";
echo "<h2>📊 Поточний стан таблиці users</h2>";

$result = $db->query("SHOW TABLE STATUS LIKE 'users'");
if ($result && $result->num_rows > 0) {
    $tableInfo = $result->fetch_assoc();
    echo "<p><strong>AUTO_INCREMENT:</strong> " . $tableInfo['Auto_increment'] . "</p>";
    echo "<p><strong>Кількість записів:</strong> " . $tableInfo['Rows'] . "</p>";
    echo "<p><strong>Рушій:</strong> " . $tableInfo['Engine'] . "</p>";
} else {
    echo "<div class='error'>❌ Не вдалося отримати інформацію про таблицю</div>";
}

$result = $db->query("SELECT id, login, email, created_at FROM users ORDER BY id LIMIT 10");
if ($result && $result->num_rows > 0) {
    echo "<h3>Поточні користувачі (перші 10):</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Login</th><th>Email</th><th>Створено</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['login'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>📭 Користувачів у базі даних немає</p>";
}
echo "</div>";

echo "<div class='debug-section'>";
echo "<h2>🛠️ Доступні дії</h2>";

echo "<form method='post' style='margin-bottom: 10px;'>";
echo "<input type='hidden' name='action' value='reset_auto_increment'>";
echo "<button type='submit' class='btn'>🔄 Розумне скидання AUTO_INCREMENT</button>";
echo "<p><small>Скидає AUTO_INCREMENT на наступне значення після максимального ID (або на 1, якщо таблиця порожня)</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;' onsubmit='return confirm(\"Ви впевнені? Це примусово встановить AUTO_INCREMENT = 1\");'>";
echo "<input type='hidden' name='action' value='reset_to_one'>";
echo "<button type='submit' class='btn btn-danger'>⚠️ Примусове скидання на 1</button>";
echo "<p><small>ОБЕРЕЖНО: Примусово встановлює AUTO_INCREMENT = 1 (може викликати конфлікти)</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;' onsubmit='return confirm(\"УВАГА! Це видалить ВСІХ користувачів та пов'язані дані! Ви впевнені?\");'>";
echo "<input type='hidden' name='action' value='delete_all_users'>";
echo "<button type='submit' class='btn btn-danger'>🗑️ Видалити всіх користувачів і скинути ID</button>";
echo "<p><small>НЕБЕЗПЕЧНО: Видаляє всіх користувачів, токени, заявки і скидає AUTO_INCREMENT на 1</small></p>";
echo "</form>";

echo "</div>";

echo "<div class='debug-section'>";
echo "<h2>ℹ️ Інформація</h2>";
echo "<p><strong>Розумне скидання</strong> - безпечний варіант, який встановлює AUTO_INCREMENT на наступне значення після максимального існуючого ID.</p>";
echo "<p><strong>Примусове скидання</strong> - встановлює AUTO_INCREMENT = 1 незалежно від існуючих записів. Може викликати конфлікти при додаванні нових користувачів.</p>";
echo "<p><strong>Видалення всіх користувачів</strong> - повне очищення всіх користувачів та пов'язаних даних з подальшим скиданням AUTO_INCREMENT на 1.</p>";
echo "</div>";

echo "<div class='debug-section'>";
echo "<h2>🔗 Навігація</h2>";
echo "<p><a href='debug_user_id.php'>🔍 Діагностика User ID</a></p>";
echo "<p><a href='debug_database.php'>🗄️ Аналіз бази даних</a></p>";
echo "<p><a href='profile.php'>👤 Повернутися до профілю</a></p>";
echo "</div>";

$db->close();
echo "</body></html>";
?>
