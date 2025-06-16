<?php
/**
 * API для работы с аватарами пользователей
 */

session_start();

require_once __DIR__ . '/../controllers/AvatarController.php';
require_once __DIR__ . '/../models/User.php';

header('Content-Type: application/json');

if (!isset($_SESSION['token'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user = new User();
$userData = $user->getUserByToken($_SESSION['token']);

if (!$userData) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Недействительный токен']);
    exit;
}

$userId = $userData['id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $avatarController = new AvatarController();
    
    switch ($method) {
        case 'POST':
            if (isset($_FILES['avatar'])) {
                $result = $avatarController->uploadAvatar($userId, $_FILES['avatar']);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'Файл не найден']);
            }
            break;
            
        case 'DELETE':
            $result = $avatarController->deleteAvatar($userId);
            echo json_encode($result);
            break;
            
        case 'GET':
            $user = new User();
            $avatarPath = $user->getAvatarPath($userId);            if ($avatarPath) {
                echo json_encode([
                    'success' => true,
                    'avatar_path' => $avatarPath,
                    'avatar_url' => './' . $avatarPath,
                    'thumb_url' => './' . dirname($avatarPath) . '/thumb_' . basename($avatarPath)
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'avatar_path' => null,
                    'avatar_url' => null,
                    'thumb_url' => null
                ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Внутренняя ошибка сервера']);
}
?>
