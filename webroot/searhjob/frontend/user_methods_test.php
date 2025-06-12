<?php
/**
 * Тест методов User для работы с аватарами
 */

session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo "Ошибка: Пользователь не авторизован";
    exit;
}

echo "<h1>User Model Methods Test</h1>";

// Подключаем модель User
require_once __DIR__ . '/../backend/models/User.php';

echo "<h2>1. Создание объекта User</h2>";
try {
    $user = new User();
    echo "✅ Объект User создан успешно<br>";
} catch (Exception $e) {
    echo "❌ Ошибка создания объекта User: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. Проверка существования методов</h2>";

$methods = ['getAvatarPath', 'updateAvatar', 'getUserProfile'];
foreach ($methods as $method) {
    if (method_exists($user, $method)) {
        echo "✅ Метод {$method} существует<br>";
    } else {
        echo "❌ Метод {$method} НЕ существует<br>";
    }
}

echo "<h2>3. Тест getAvatarPath</h2>";
$userId = $_SESSION['user_id'];
try {
    $avatarPath = $user->getAvatarPath($userId);
    echo "✅ Метод getAvatarPath выполнен успешно<br>";
    echo "Результат: " . ($avatarPath ? $avatarPath : 'null') . "<br>";
} catch (Exception $e) {
    echo "❌ Ошибка выполнения getAvatarPath: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Информация о классе User</h2>";
$reflection = new ReflectionClass($user);
echo "Файл класса: " . $reflection->getFileName() . "<br>";
echo "Методы класса:<br>";
foreach ($reflection->getMethods() as $method) {
    echo "- " . $method->getName() . "<br>";
}

echo "<h2>5. Содержимое файла User.php (последние 50 строк)</h2>";
$userFilePath = __DIR__ . '/../backend/models/User.php';
$lines = file($userFilePath);
$totalLines = count($lines);
$startLine = max(0, $totalLines - 50);

echo "<pre>";
for ($i = $startLine; $i < $totalLines; $i++) {
    echo sprintf("%03d: %s", $i + 1, htmlspecialchars($lines[$i]));
}
echo "</pre>";

echo "<a href='avatar_test.php'>← Вернуться к тесту аватаров</a>";
?>
