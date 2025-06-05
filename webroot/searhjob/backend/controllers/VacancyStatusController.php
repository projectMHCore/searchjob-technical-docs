<?php
// Контроллер для управления статусом вакансий
session_start();

require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/User.php';

// Проверяем авторизацию
if (!isset($_SESSION['token']) || $_SESSION['user_role'] !== 'employer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
    exit;
}

$userModel = new User();
$userData = $userModel->getUserByToken($_SESSION['token']);

if (!$userData) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Пользователь не найден']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$vacancyModel = new Vacancy();

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'toggle':
            if ($method === 'POST') {
                $vacancy_id = intval($_POST['vacancy_id'] ?? 0);
                
                if ($vacancy_id > 0) {
                    $result = $vacancyModel->toggleStatus($vacancy_id, $userData['id']);
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Статус вакансии изменен']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Ошибка изменения статуса']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'ID вакансии не указан']);
                }
            }
            break;
            
        case 'activate':
            if ($method === 'POST') {
                $vacancy_id = intval($_POST['vacancy_id'] ?? 0);
                
                if ($vacancy_id > 0) {
                    $result = $vacancyModel->setStatus($vacancy_id, true, $userData['id']);
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Вакансия активирована']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Ошибка активации вакансии']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'ID вакансии не указан']);
                }
            }
            break;
            
        case 'deactivate':
            if ($method === 'POST') {
                $vacancy_id = intval($_POST['vacancy_id'] ?? 0);
                
                if ($vacancy_id > 0) {
                    $result = $vacancyModel->setStatus($vacancy_id, false, $userData['id']);
                    
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Вакансия деактивирована']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'Ошибка деактивации вакансии']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'ID вакансии не указан']);
                }
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Действие не найдено']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера: ' . $e->getMessage()]);
}
?>
