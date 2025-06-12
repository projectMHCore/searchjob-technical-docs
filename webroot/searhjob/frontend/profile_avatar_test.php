<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo "Ошибка: Пользователь не авторизован";
    exit;
}

require_once __DIR__ . '/../backend/models/User.php';
require_once __DIR__ . '/../backend/utils/AvatarHelper.php';

echo "<h1>Profile Avatar Diagnostics</h1>";

$user = new User();
$userId = $_SESSION['user_id'];

echo "<h2>1. Получение профиля пользователя</h2>";
try {
    $profile = $user->getUserProfile($userId);
    if ($profile) {
        echo "✅ Профиль найден<br>";
        echo "<strong>Avatar path:</strong> " . ($profile['avatar'] ?? 'NULL') . "<br>";
        echo "<strong>User data:</strong><br>";
        echo "<pre>" . print_r($profile, true) . "</pre>";
    } else {
        echo "❌ Профиль не найден<br>";
    }
} catch (Exception $e) {
    echo "❌ Ошибка получения профиля: " . $e->getMessage() . "<br>";
}

echo "<h2>2. Тест AvatarHelper</h2>";
if (isset($profile['avatar']) && $profile['avatar']) {
    $avatarPath = $profile['avatar'];
    echo "<strong>Original path:</strong> $avatarPath<br>";
    
    $fullUrl = AvatarHelper::getAvatarUrl($avatarPath, 'full');
    $thumbUrl = AvatarHelper::getAvatarUrl($avatarPath, 'thumb');
    
    echo "<strong>Full URL:</strong> $fullUrl<br>";
    echo "<strong>Thumb URL:</strong> $thumbUrl<br>";
    
    echo "<h3>Проверка существования файлов:</h3>";
    $fullPath = __DIR__ . '/' . $avatarPath;
    $thumbPath = dirname($fullPath) . '/thumb_' . basename($fullPath);
    
    echo "<strong>Full file path:</strong> $fullPath<br>";
    echo "<strong>Full file exists:</strong> " . (file_exists($fullPath) ? "✅ YES" : "❌ NO") . "<br>";
    echo "<strong>Thumb file path:</strong> $thumbPath<br>";
    echo "<strong>Thumb file exists:</strong> " . (file_exists($thumbPath) ? "✅ YES" : "❌ NO") . "<br>";
    
    echo "<h3>Предварительный просмотр:</h3>";
    if (file_exists($fullPath)) {
        echo "<img src='$fullUrl' style='max-width: 150px; max-height: 150px; border: 2px solid #ddd; border-radius: 8px;' alt='Avatar'><br>";
    } else {
        echo "❌ Файл не найден для предварительного просмотра<br>";
    }
    
} else {
    echo "❌ У пользователя нет аватара<br>";
}

echo "<h2>3. Тест рендеринга с AvatarHelper</h2>";
if ($profile) {
    echo "<h3>Small avatar:</h3>";
    echo AvatarHelper::renderAvatar($profile, 'small');
    
    echo "<h3>Medium avatar:</h3>";
    echo AvatarHelper::renderAvatar($profile, 'medium');
    
    echo "<h3>Large avatar:</h3>";
    echo AvatarHelper::renderAvatar($profile, 'large');
    
    echo "<h3>XLarge avatar:</h3>";
    echo AvatarHelper::renderAvatar($profile, 'xlarge');
}

echo "<h2>4. Информация о путях</h2>";
echo "<strong>Current REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
echo "<strong>Current directory:</strong> " . __DIR__ . "<br>";
echo "<strong>Assets directory:</strong> " . __DIR__ . "/assets/uploads/avatars/<br>";
echo "<strong>Assets directory exists:</strong> " . (is_dir(__DIR__ . "/assets/uploads/avatars/") ? "✅ YES" : "❌ NO") . "<br>";

echo "<br><a href='profile.php'>← Вернуться к профилю</a>";
?>
