<?php
/**
 * Утилита для исправления путей аватаров в базе данных
 */

session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo "Ошибка: Пользователь не авторизован";
    exit;
}

require_once __DIR__ . '/../backend/models/User.php';

echo "<h1>Avatar Path Fix Utility</h1>";

$user = new User();
$userId = $_SESSION['user_id'];

echo "<h2>1. Текущее состояние</h2>";
$currentProfile = $user->getUserProfile($userId);
if ($currentProfile && !empty($currentProfile['avatar'])) {
    $currentPath = $currentProfile['avatar'];
    echo "<strong>Текущий путь:</strong> $currentPath<br>";
    
    if (strpos($currentPath, 'frontend/') === 0) {
        $newPath = substr($currentPath, 9); // убираем 'frontend/'
        echo "<strong>Новый путь:</strong> $newPath<br>";
        
        echo "<h2>2. Исправление пути</h2>";
        if ($user->updateAvatar($userId, $newPath)) {
            echo "✅ Путь успешно исправлен!<br>";
            
            echo "<h2>3. Проверка файла</h2>";
            $fullPath = __DIR__ . '/' . $newPath;
            echo "<strong>Полный путь к файлу:</strong> $fullPath<br>";
            echo "<strong>Файл существует:</strong> " . (file_exists($fullPath) ? "✅ YES" : "❌ NO") . "<br>";
            
            if (file_exists($fullPath)) {
                echo "<h2>4. Предварительный просмотр</h2>";
                echo "<img src='./$newPath' style='max-width: 150px; max-height: 150px; border: 2px solid #ddd; border-radius: 8px;' alt='Avatar'><br>";
            }
        } else {
            echo "❌ Ошибка обновления пути<br>";
        }
    } else {
        echo "✅ Путь уже правильный<br>";
        
        echo "<h2>2. Проверка файла</h2>";
        $fullPath = __DIR__ . '/' . $currentPath;
        echo "<strong>Полный путь к файлу:</strong> $fullPath<br>";
        echo "<strong>Файл существует:</strong> " . (file_exists($fullPath) ? "✅ YES" : "❌ NO") . "<br>";
        
        if (file_exists($fullPath)) {
            echo "<h2>3. Предварительный просмотр</h2>";
            echo "<img src='./$currentPath' style='max-width: 150px; max-height: 150px; border: 2px solid #ddd; border-radius: 8px;' alt='Avatar'><br>";
        }
    }
} else {
    echo "❌ У пользователя нет аватара<br>";
}

echo "<br><a href='profile.php'>← Вернуться к профилю</a>";
echo " | <a href='profile_avatar_test.php'>Повторить диагностику</a>";
?>
