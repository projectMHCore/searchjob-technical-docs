<?php
// Скрипт для создания/обновления пользователя-работодателя
require_once __DIR__ . '/../../../backend/config/db.php';
require_once __DIR__ . '/../../../backend/models/User.php';

$config = require __DIR__ . '/../../../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}

echo "<h2>Управление пользователями</h2>";

$result = $db->query("SELECT id, login, email, role FROM users");
echo "<h3>Существующие пользователи:</h3>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Login</th><th>Email</th><th>Role</th><th>Действие</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['login']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
    echo "<td>";
    if ($row['role'] !== 'employer') {
        echo "<a href='?action=make_employer&id=" . $row['id'] . "'>Сделать работодателем</a>";
    } else {
        echo "Уже работодатель";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'make_employer' && isset($_GET['id'])) {
        $userId = intval($_GET['id']);
        $stmt = $db->prepare("UPDATE users SET role = 'employer' WHERE id = ?");
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            echo "<div style='color: green; margin: 20px 0;'>Пользователь с ID $userId успешно назначен работодателем!</div>";
            echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
        } else {
            echo "<div style='color: red; margin: 20px 0;'>Ошибка при обновлении роли: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    
    if ($_GET['action'] === 'create_employer') {
        $login = 'employer_test';
        $email = 'employer@test.com';
        $password = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (login, email, password, role) VALUES (?, ?, ?, 'employer')");
        $stmt->bind_param("sss", $login, $email, $password);
        
        if ($stmt->execute()) {
            echo "<div style='color: green; margin: 20px 0;'>Тестовый работодатель создан! Логин: $login, Пароль: 123456</div>";
            echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
        } else {
            echo "<div style='color: red; margin: 20px 0;'>Ошибка при создании пользователя: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

echo "<br><a href='?action=create_employer' style='background: #007cba; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Создать тестового работодателя</a>";

$db->close();

echo "<p><a href='/frontend/profile.php'>← Вернуться в профиль</a></p>";
?>
