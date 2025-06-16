<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Помилка: Користувач не авторизований";
    exit;
}

echo "<h1>Тест методів моделі User</h1>";

require_once __DIR__ . '/../backend/models/User.php';

echo "<h2>1. Створення об'єкта User</h2>";
try {
    $user = new User();
    echo "✅ Об'єкт User створено успішно<br>";
} catch (Exception $e) {
    echo "❌ Помилка створення об'єкта User: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. Перевірка існування методів</h2>";

$methods = ['getAvatarPath', 'updateAvatar', 'getUserProfile'];
foreach ($methods as $method) {
    if (method_exists($user, $method)) {
        echo "✅ Метод {$method} існує<br>";
    } else {
        echo "❌ Метод {$method} НЕ існує<br>";
    }
}

echo "<h2>3. Тест getAvatarPath</h2>";
$userId = $_SESSION['user_id'];
try {
    $avatarPath = $user->getAvatarPath($userId);
    echo "✅ Метод getAvatarPath виконано успішно<br>";
    echo "Результат: " . ($avatarPath ? $avatarPath : 'null') . "<br>";
} catch (Exception $e) {
    echo "❌ Помилка виконання getAvatarPath: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Інформація про клас User</h2>";
$reflection = new ReflectionClass($user);
echo "Файл класу: " . $reflection->getFileName() . "<br>";
echo "Методи класу:<br>";
foreach ($reflection->getMethods() as $method) {
    echo "- " . $method->getName() . "<br>";
}

echo "<h2>5. Вміст файлу User.php (останні 50 рядків)</h2>";
$userFilePath = __DIR__ . '/../backend/models/User.php';
$lines = file($userFilePath);
$totalLines = count($lines);
$startLine = max(0, $totalLines - 50);

echo "<pre>";
for ($i = $startLine; $i < $totalLines; $i++) {
    echo sprintf("%03d: %s", $i + 1, htmlspecialchars($lines[$i]));
}
echo "</pre>";

echo "<a href='avatar_test.php'>← Повернутися до тесту аватарів</a>";
?>
