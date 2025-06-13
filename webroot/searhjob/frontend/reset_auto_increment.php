<?php
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Reset AUTO_INCREMENT</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; } .error { color: red; } .success { color: green; } .warning { color: orange; } .btn { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; } .btn-danger { background: #dc3545; } .btn:hover { opacity: 0.8; }</style>";
echo "</head><body>";
echo "<h1>🔄 Сброс AUTO_INCREMENT</h1>";

// Проверяем авторизацию администратора (опционально)
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
    
    if ($action === 'reset_auto_increment') {
        echo "<div class='debug-section'>";
        echo "<h2>🔄 Сброс AUTO_INCREMENT для таблицы users</h2>";
        
        // Проверяем текущее состояние
        $result = $db->query("SHOW TABLE STATUS LIKE 'users'");
        if ($result && $result->num_rows > 0) {
            $tableInfo = $result->fetch_assoc();
            $currentAutoIncrement = $tableInfo['Auto_increment'];
            $rowCount = $tableInfo['Rows'];
            
            echo "<p><strong>Текущий AUTO_INCREMENT:</strong> $currentAutoIncrement</p>";
            echo "<p><strong>Количество записей:</strong> $rowCount</p>";
            
            if ($rowCount == 0) {
                // Если таблица пуста, сбрасываем на 1
                if ($db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
                    echo "<div class='success'>✅ AUTO_INCREMENT успешно сброшен на 1</div>";
                } else {
                    echo "<div class='error'>❌ Ошибка сброса AUTO_INCREMENT: " . $db->error . "</div>";
                }
            } else {
                // Если есть записи, находим максимальный ID и сбрасываем на следующее значение
                $result = $db->query("SELECT MAX(id) as max_id FROM users");
                if ($result) {
                    $maxId = $result->fetch_assoc()['max_id'];
                    $newAutoIncrement = $maxId + 1;
                    
                    if ($db->query("ALTER TABLE users AUTO_INCREMENT = $newAutoIncrement")) {
                        echo "<div class='success'>✅ AUTO_INCREMENT сброшен на $newAutoIncrement (следующий после максимального ID $maxId)</div>";
                    } else {
                        echo "<div class='error'>❌ Ошибка сброса AUTO_INCREMENT: " . $db->error . "</div>";
                    }
                }
            }
        }
        echo "</div>";
    }
    
    if ($action === 'reset_to_one') {
        echo "<div class='debug-section'>";
        echo "<h2>⚠️ ПРИНУДИТЕЛЬНЫЙ сброс AUTO_INCREMENT на 1</h2>";
        
        // Проверяем, есть ли записи в таблице
        $result = $db->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $count = $result->fetch_assoc()['count'];
            
            if ($count > 0) {
                echo "<div class='warning'>⚠️ ВНИМАНИЕ: В таблице есть $count записей!</div>";
                echo "<p>Принудительный сброс AUTO_INCREMENT на 1 может привести к конфликтам при добавлении новых записей.</p>";
            }
            
            if ($db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
                echo "<div class='success'>✅ AUTO_INCREMENT принудительно сброшен на 1</div>";
                echo "<p><strong>Рекомендация:</strong> Если в таблице есть записи, лучше удалить их перед сбросом или использовать обычный сброс.</p>";
            } else {
                echo "<div class='error'>❌ Ошибка принудительного сброса: " . $db->error . "</div>";
            }
        }
        echo "</div>";
    }
    
    if ($action === 'delete_all_users') {
        echo "<div class='debug-section'>";
        echo "<h2>🗑️ Удаление всех пользователей</h2>";
        
        // Отключаем проверку внешних ключей
        $db->query("SET FOREIGN_KEY_CHECKS = 0");
        
        $success = true;
        $deletedCount = 0;
        
        // Удаляем связанные данные
        $tables = ['user_tokens', 'applications', 'job_applications'];
        foreach ($tables as $table) {
            $result = $db->query("SELECT COUNT(*) as count FROM $table");
            if ($result) {
                $count = $result->fetch_assoc()['count'];
                if ($count > 0) {
                    if ($db->query("DELETE FROM $table")) {
                        echo "<p>✅ Удалено $count записей из таблицы $table</p>";
                    } else {
                        echo "<p>❌ Ошибка удаления из таблицы $table: " . $db->error . "</p>";
                        $success = false;
                    }
                }
            }
        }
        
        // Удаляем пользователей
        $result = $db->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $userCount = $result->fetch_assoc()['count'];
            if ($userCount > 0) {
                if ($db->query("DELETE FROM users")) {
                    echo "<p>✅ Удалено $userCount пользователей</p>";
                    $deletedCount = $userCount;
                } else {
                    echo "<p>❌ Ошибка удаления пользователей: " . $db->error . "</p>";
                    $success = false;
                }
            } else {
                echo "<p>ℹ️ Таблица пользователей уже пуста</p>";
            }
        }
        
        // Сбрасываем AUTO_INCREMENT
        if ($success && $db->query("ALTER TABLE users AUTO_INCREMENT = 1")) {
            echo "<p>✅ AUTO_INCREMENT сброшен на 1</p>";
        } else if ($success) {
            echo "<p>❌ Ошибка сброса AUTO_INCREMENT: " . $db->error . "</p>";
        }
        
        // Включаем обратно проверку внешних ключей
        $db->query("SET FOREIGN_KEY_CHECKS = 1");
        
        if ($success && $deletedCount > 0) {
            echo "<div class='success'>✅ Все пользователи и связанные данные успешно удалены. AUTO_INCREMENT сброшен.</div>";
            echo "<div class='warning'>⚠️ Внимание: Ваша текущая сессия будет недействительна. <a href='logout.php'>Выйти из системы</a></div>";
        } else if ($success) {
            echo "<div class='success'>✅ Операция завершена. База данных была пуста.</div>";
        }
        
        echo "</div>";
    }
}

// Показываем текущее состояние
echo "<div class='debug-section'>";
echo "<h2>📊 Текущее состояние таблицы users</h2>";

$result = $db->query("SHOW TABLE STATUS LIKE 'users'");
if ($result && $result->num_rows > 0) {
    $tableInfo = $result->fetch_assoc();
    echo "<p><strong>AUTO_INCREMENT:</strong> " . $tableInfo['Auto_increment'] . "</p>";
    echo "<p><strong>Количество записей:</strong> " . $tableInfo['Rows'] . "</p>";
    echo "<p><strong>Движок:</strong> " . $tableInfo['Engine'] . "</p>";
} else {
    echo "<div class='error'>❌ Не удалось получить информацию о таблице</div>";
}

// Показываем пользователей
$result = $db->query("SELECT id, login, email, created_at FROM users ORDER BY id LIMIT 10");
if ($result && $result->num_rows > 0) {
    echo "<h3>Текущие пользователи (первые 10):</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Login</th><th>Email</th><th>Создан</th></tr>";
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
    echo "<p>📭 Пользователей в базе данных нет</p>";
}
echo "</div>";

// Показываем форму действий
echo "<div class='debug-section'>";
echo "<h2>🛠️ Доступные действия</h2>";

echo "<form method='post' style='margin-bottom: 10px;'>";
echo "<input type='hidden' name='action' value='reset_auto_increment'>";
echo "<button type='submit' class='btn'>🔄 Умный сброс AUTO_INCREMENT</button>";
echo "<p><small>Сбрасывает AUTO_INCREMENT на следующее значение после максимального ID (или на 1, если таблица пуста)</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;' onsubmit='return confirm(\"Вы уверены? Это принудительно установит AUTO_INCREMENT = 1\");'>";
echo "<input type='hidden' name='action' value='reset_to_one'>";
echo "<button type='submit' class='btn btn-danger'>⚠️ Принудительный сброс на 1</button>";
echo "<p><small>ОСТОРОЖНО: Принудительно устанавливает AUTO_INCREMENT = 1 (может вызвать конфликты)</small></p>";
echo "</form>";

echo "<form method='post' style='margin-bottom: 10px;' onsubmit='return confirm(\"ВНИМАНИЕ! Это удалит ВСЕХ пользователей и связанные данные! Вы уверены?\");'>";
echo "<input type='hidden' name='action' value='delete_all_users'>";
echo "<button type='submit' class='btn btn-danger'>🗑️ Удалить всех пользователей и сбросить ID</button>";
echo "<p><small>ОПАСНО: Удаляет всех пользователей, токены, заявки и сбрасывает AUTO_INCREMENT на 1</small></p>";
echo "</form>";

echo "</div>";

// Информационная секция
echo "<div class='debug-section'>";
echo "<h2>ℹ️ Информация</h2>";
echo "<p><strong>Умный сброс</strong> - безопасный вариант, который устанавливает AUTO_INCREMENT на следующее значение после максимального существующего ID.</p>";
echo "<p><strong>Принудительный сброс</strong> - устанавливает AUTO_INCREMENT = 1 независимо от существующих записей. Может вызвать конфликты при добавлении новых пользователей.</p>";
echo "<p><strong>Удаление всех пользователей</strong> - полная очистка всех пользователей и связанных данных с последующим сбросом AUTO_INCREMENT на 1.</p>";
echo "</div>";

// Навигация
echo "<div class='debug-section'>";
echo "<h2>🔗 Навигация</h2>";
echo "<p><a href='debug_user_id.php'>🔍 Диагностика User ID</a></p>";
echo "<p><a href='debug_database.php'>🗄️ Анализ базы данных</a></p>";
echo "<p><a href='profile.php'>👤 Вернуться к профилю</a></p>";
echo "</div>";

$db->close();
echo "</body></html>";
?>
