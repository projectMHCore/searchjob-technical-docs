<?php
// API-контроллер для прямого доступа к вакансиям
require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/ApiLogController.php';
header('Content-Type: application/json; charset=utf-8');

log_api([
    'request' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'body' => file_get_contents('php://input'),
    'time' => date('Y-m-d H:i:s')
]);

/**
 * Функция для отправки JSON ответа с логированием
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    $jsonResponse = json_encode($data);
    
    log_api([
        'response' => $data,
        'status_code' => $statusCode,
        'time' => date('Y-m-d H:i:s')
    ]);
    
    echo $jsonResponse;
}

/**
 * Получение токена из заголовков запроса
 */
function getAuthToken() {
    $headers = [];
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$header] = $value;
            }
        }
    }
    
    $token = '';
    if (isset($headers['Authorization'])) {
        $token = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $token = $headers['authorization'];
    }
    
    return $token;
}
try {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_GET['action'] ?? '';
    
    $vacancyModel = new Vacancy();
    $userModel = new User();
    
    switch ($path) {
        case 'create':
            if ($method === 'POST') {
                $token = getAuthToken();
                $user_id = $userModel->getUserIdByToken($token);
                
                if (!$user_id) {
                    sendJsonResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                    break;
                }
                $data = json_decode(file_get_contents('php://input'), true);
                $title = trim($data['title'] ?? '');
                $description = trim($data['description'] ?? '');
                $salary = trim($data['salary'] ?? '');
                
                if ($title && $description) {
                    $vacancyId = $vacancyModel->create([
                        'employer_id' => $user_id,
                        'title' => $title,
                        'description' => $description,
                        'salary' => $salary
                    ]);
                    
                    if ($vacancyId) {
                        sendJsonResponse(['success' => true, 'vacancy_id' => $vacancyId]);
                    } else {
                        sendJsonResponse(['success' => false, 'error' => 'Ошибка создания вакансии'], 400);
                    }
                } else {
                    sendJsonResponse(['success' => false, 'error' => 'Название и описание обязательны'], 400);
                }
            }
            break;
            
        case 'list':
            if ($method === 'GET') {
                $search = trim($_GET['search'] ?? '');
                if ($search) {
                    $vacancies = $vacancyModel->search($search);
                } else {
                    $vacancies = $vacancyModel->getAll();
                }
                sendJsonResponse(['success' => true, 'vacancies' => $vacancies]);
            }
            break;
            
        case 'detail':
            if ($method === 'GET') {
                $id = intval($_GET['id'] ?? 0);
                $vacancy = $vacancyModel->getById($id);
                if ($vacancy) {
                    sendJsonResponse(['success' => true, 'vacancy' => $vacancy]);
                } else {
                    sendJsonResponse(['success' => false, 'error' => 'Вакансия не найдена'], 404);
                }
            }
            break;
            
        case 'update':
            if ($method === 'PUT' || $method === 'POST') {
                $token = getAuthToken();
                $user_id = $userModel->getUserIdByToken($token);
                
                if (!$user_id) {
                    sendJsonResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                    break;
                }
                
                $id = intval($_GET['id'] ?? 0);
                if ($id > 0) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $title = trim($data['title'] ?? '');
                    $description = trim($data['description'] ?? '');
                    $salary = trim($data['salary'] ?? '');
                    
                    if ($title && $description) {
                        $updateResult = $vacancyModel->update($id, [
                            'title' => $title,
                            'description' => $description,
                            'salary' => $salary
                        ], $user_id);
                        
                        if ($updateResult) {
                            sendJsonResponse(['success' => true]);
                        } else {
                            sendJsonResponse(['success' => false, 'error' => 'Ошибка обновления вакансии'], 400);
                        }
                    } else {
                        sendJsonResponse(['success' => false, 'error' => 'Название и описание обязательны'], 400);
                    }
                } else {
                    sendJsonResponse(['success' => false, 'error' => 'ID вакансии обязателен'], 400);
                }
            }
            break;
            
        case 'my_vacancies':
            if ($method === 'GET') {
                $token = getAuthToken();
                $user_id = $userModel->getUserIdByToken($token);
                
                if (!$user_id) {
                    sendJsonResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                    break;
                }
                
                $vacancies = $vacancyModel->getVacanciesByEmployer($user_id);
                sendJsonResponse(['success' => true, 'vacancies' => $vacancies]);
            }
            break;
            
        case 'delete':
            if ($method === 'DELETE' || $method === 'POST') {
                $token = getAuthToken();
                $user_id = $userModel->getUserIdByToken($token);
                
                if (!$user_id) {
                    sendJsonResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                    break;
                }
                
                $id = intval($_GET['id'] ?? 0);
                if ($id > 0) {
                    $deleteResult = $vacancyModel->delete($id, $user_id);
                    if ($deleteResult) {
                        sendJsonResponse(['success' => true]);
                    } else {
                        sendJsonResponse(['success' => false, 'error' => 'Ошибка удаления вакансии'], 400);
                    }
                } else {
                    sendJsonResponse(['success' => false, 'error' => 'ID вакансии обязателен'], 400);
                }
            }
            break;
            
        default:
            sendJsonResponse(['success' => false, 'error' => 'Маршрут не найден'], 404);
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'error' => 'Внутренняя ошибка сервера: ' . $e->getMessage()], 500);
}
