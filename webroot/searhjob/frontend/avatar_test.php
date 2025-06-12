<?php
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../backend/models/User.php';
require_once __DIR__ . '/../backend/utils/AvatarHelper.php';

$user = new User();
$userData = $user->getUserByToken($_SESSION['token']);

if (!$userData) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест аватаров - SearchJob</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?= AvatarHelper::getAvatarCSS() ?>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .page-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        .test-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
        }
        
        .test-section h3 {
            color: #eaa850;
            margin-bottom: 20px;
        }
        
        .avatar-demo {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #eaa850;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .btn {
            background: #eaa850;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn:hover {
            background: #d4922a;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="profile.php" class="back-link">← Назад к профилю</a>
        
        <h1 class="page-title">🧪 Тестирование системы аватаров</h1>
        
        <div class="test-section">
            <h3>📷 Загрузка аватара</h3>
            <?php include __DIR__ . '/components/avatar_upload.php'; ?>
            <a href="edit_profile.php" class="btn">Перейти к редактированию профиля</a>
        </div>
        
        <div class="test-section">
            <h3>📏 Различные размеры аватаров</h3>
            <div class="avatar-demo">
                <div style="text-align: center;">
                    <p><strong>Маленький (32px)</strong></p>
                    <?= AvatarHelper::renderAvatar(['login' => 'test', 'first_name' => 'Test', 'avatar' => null], 'small') ?>
                </div>
                
                <div style="text-align: center;">
                    <p><strong>Средний (48px)</strong></p>
                    <?= AvatarHelper::renderAvatar(['login' => 'test', 'first_name' => 'Test', 'avatar' => null], 'medium') ?>
                </div>
                
                <div style="text-align: center;">
                    <p><strong>Большой (80px)</strong></p>
                    <?= AvatarHelper::renderAvatar(['login' => 'test', 'first_name' => 'Test', 'avatar' => null], 'large') ?>
                </div>
                
                <div style="text-align: center;">
                    <p><strong>Очень большой (120px)</strong></p>
                    <?= AvatarHelper::renderAvatar(['login' => 'test', 'first_name' => 'Test', 'avatar' => null], 'xlarge') ?>
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <h3>👥 Примеры разных пользователей</h3>
            <div class="avatar-demo">
                <?= AvatarHelper::renderAvatar(['login' => 'john_doe', 'first_name' => 'John', 'last_name' => 'Doe'], 'large') ?>
                <span>John Doe</span>
                
                <?= AvatarHelper::renderAvatar(['login' => 'jane_smith', 'first_name' => 'Jane', 'last_name' => 'Smith'], 'large') ?>
                <span>Jane Smith</span>
                
                <?= AvatarHelper::renderAvatar(['login' => 'admin'], 'large') ?>
                <span>admin (только логин)</span>
                
                <?= AvatarHelper::renderAvatar(['login' => 'company123', 'company_name' => 'Tech Corp'], 'large') ?>
                <span>Tech Corp</span>
            </div>
        </div>
        
        <div class="test-section">
            <h3>ℹ️ Информация</h3>
            <ul>
                <li>✅ Поддерживаемые форматы: JPG, PNG, GIF</li>
                <li>✅ Максимальный размер: 5MB</li>
                <li>✅ Автоматическое создание миниатюр</li>
                <li>✅ Fallback на инициалы при отсутствии фото</li>
                <li>✅ Адаптивный дизайн</li>
                <li>✅ Поддержка темной темы</li>
            </ul>
        </div>
    </div>
</body>
</html>
