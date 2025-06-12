<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo "Ошибка: Пользователь не авторизован";
    exit;
}

require_once __DIR__ . '/../backend/models/User.php';
require_once __DIR__ . '/../backend/utils/AvatarHelper.php';

// Включаем CSS для аватаров
echo AvatarHelper::getAvatarCSS();
?>

<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 20px;
    background: #f5f5f5;
}

h1, h2, h3 {
    color: #333;
    border-bottom: 2px solid #eaa850;
    padding-bottom: 5px;
}

.avatar-test-container {
    background: white;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.avatar-showcase {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
    margin: 15px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.avatar-showcase-item {
    text-align: center;
}

.avatar-showcase-item label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #666;
}

pre {
    background: #f4f4f4;
    padding: 10px;
    border-radius: 4px;
    border-left: 4px solid #eaa850;
    overflow-x: auto;
}

.status-good { color: #28a745; font-weight: bold; }
.status-bad { color: #dc3545; font-weight: bold; }

.preview-image {
    max-width: 150px; 
    max-height: 150px; 
    border: 2px solid #ddd; 
    border-radius: 8px;
    margin: 10px 0;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #eaa850;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.back-link:hover {
    background: #d4922a;
}
</style>

<h1>Profile Avatar Diagnostics</h1>

<?php
$user = new User();
$userId = $_SESSION['user_id'];

echo "<div class='avatar-test-container'>";
echo "<h2>1. Получение профиля пользователя</h2>";
try {
    $profile = $user->getUserProfile($userId);
    if ($profile) {
        echo "<span class='status-good'>✅ Профиль найден</span><br>";
        echo "<strong>Avatar path:</strong> " . ($profile['avatar'] ?? 'NULL') . "<br>";
        echo "<strong>User data:</strong><br>";
        echo "<pre>" . print_r($profile, true) . "</pre>";
    } else {
        echo "<span class='status-bad'>❌ Профиль не найден</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='status-bad'>❌ Ошибка получения профиля: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

echo "<div class='avatar-test-container'>";
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
    echo "<strong>Full file exists:</strong> " . (file_exists($fullPath) ? "<span class='status-good'>✅ YES</span>" : "<span class='status-bad'>❌ NO</span>") . "<br>";
    echo "<strong>Thumb file path:</strong> $thumbPath<br>";
    echo "<strong>Thumb file exists:</strong> " . (file_exists($thumbPath) ? "<span class='status-good'>✅ YES</span>" : "<span class='status-bad'>❌ NO</span>") . "<br>";
    
    echo "<h3>Предварительный просмотр:</h3>";
    if (file_exists($fullPath)) {
        echo "<img src='$fullUrl' class='preview-image' alt='Avatar'><br>";
    } else {
        echo "<span class='status-bad'>❌ Файл не найден для предварительного просмотра</span><br>";
    }
    
} else {
    echo "<span class='status-bad'>❌ У пользователя нет аватара</span><br>";
}
echo "</div>";

echo "<div class='avatar-test-container'>";
echo "<h2>3. Тест рендеринга с AvatarHelper</h2>";
if ($profile) {
    echo "<div class='avatar-showcase'>";
    
    echo "<div class='avatar-showcase-item'>";
    echo AvatarHelper::renderAvatar($profile, 'small');
    echo "<label>Small (32px)</label>";
    echo "</div>";
    
    echo "<div class='avatar-showcase-item'>";
    echo AvatarHelper::renderAvatar($profile, 'medium');
    echo "<label>Medium (48px)</label>";
    echo "</div>";
    
    echo "<div class='avatar-showcase-item'>";
    echo AvatarHelper::renderAvatar($profile, 'large');
    echo "<label>Large (80px)</label>";
    echo "</div>";
    
    echo "<div class='avatar-showcase-item'>";
    echo AvatarHelper::renderAvatar($profile, 'xlarge');
    echo "<label>XLarge (120px)</label>";
    echo "</div>";
    
    echo "</div>";
}
echo "</div>";

echo "<div class='avatar-test-container'>";
echo "<h2>4. Информация о путях</h2>";
echo "<strong>Current REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
echo "<strong>Current directory:</strong> " . __DIR__ . "<br>";
echo "<strong>Assets directory:</strong> " . __DIR__ . "/assets/uploads/avatars/<br>";
echo "<strong>Assets directory exists:</strong> " . (is_dir(__DIR__ . "/assets/uploads/avatars/") ? "<span class='status-good'>✅ YES</span>" : "<span class='status-bad'>❌ NO</span>") . "<br>";
echo "</div>";

echo "<a href='profile.php' class='back-link'>← Вернуться к профилю</a>";
echo " <a href='fix_avatar_path.php' class='back-link'>Исправить пути</a>";
?>
