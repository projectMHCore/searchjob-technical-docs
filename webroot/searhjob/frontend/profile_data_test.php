<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['token'])) {
    echo "Ошибка: Нет токена в сессии";
    exit;
}

require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/../backend/utils/AvatarHelper.php';

echo "<h1>Profile Controller Data Test</h1>";

$userModel = new UserModel();

echo "<h2>1. Тест getProfile через UserModel</h2>";
$result = $userModel->getProfile($_SESSION['token']);

echo "<strong>API Result:</strong><br>";
echo "<pre>" . print_r($result, true) . "</pre>";

if ($result['success']) {
    $profile = $result['profile'];
    
    echo "<h2>2. Тип данных профиля</h2>";
    echo "<strong>Profile type:</strong> " . gettype($profile) . "<br>";
    
    if (is_object($profile)) {
        echo "<strong>Profile object properties:</strong><br>";
        foreach (get_object_vars($profile) as $key => $value) {
            echo "- $key: " . (is_string($value) ? $value : gettype($value)) . "<br>";
        }
        
        // Конвертируем объект в массив для AvatarHelper
        $profileArray = (array) $profile;
        echo "<h2>3. Конвертация в массив</h2>";
        echo "<strong>Avatar field:</strong> " . ($profileArray['avatar'] ?? 'NULL') . "<br>";
        
        echo "<h2>4. Тест AvatarHelper с массивом</h2>";
        try {
            $avatarHtml = AvatarHelper::renderAvatar($profileArray, 'large');
            echo "✅ AvatarHelper работает<br>";
            echo "<strong>Generated HTML:</strong><br>";
            echo "<pre>" . htmlspecialchars($avatarHtml) . "</pre>";
            echo "<strong>Visual result:</strong><br>";
            echo $avatarHtml;
        } catch (Exception $e) {
            echo "❌ Ошибка AvatarHelper: " . $e->getMessage() . "<br>";
        }
        
    } elseif (is_array($profile)) {
        echo "<strong>Avatar field:</strong> " . ($profile['avatar'] ?? 'NULL') . "<br>";
        
        echo "<h2>3. Тест AvatarHelper с массивом</h2>";
        try {
            $avatarHtml = AvatarHelper::renderAvatar($profile, 'large');
            echo "✅ AvatarHelper работает<br>";
            echo "<strong>Generated HTML:</strong><br>";
            echo "<pre>" . htmlspecialchars($avatarHtml) . "</pre>";
            echo "<strong>Visual result:</strong><br>";
            echo $avatarHtml;
        } catch (Exception $e) {
            echo "❌ Ошибка AvatarHelper: " . $e->getMessage() . "<br>";
        }
    }
    
} else {
    echo "❌ Ошибка получения профиля: " . ($result['error'] ?? 'Unknown error') . "<br>";
}

echo "<br><a href='profile.php'>← Вернуться к профилю</a>";
?>
