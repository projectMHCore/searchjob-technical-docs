<?php
// API-контроллер для работы с вакансиями (поддержка JSON и XML)
require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/ApiLogController.php';

$format = $_GET['format'] ?? 'json';
$format = strtolower($format);

if ($format === 'xml') {
    header('Content-Type: application/xml; charset=utf-8');
} else {
    header('Content-Type: application/json; charset=utf-8');
}

log_api([
    'request' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'body' => file_get_contents('php://input'),
    'format' => $format,
    'time' => date('Y-m-d H:i:s')
]);

function getRequestHeaders() {
    $headers = [];
    if (function_exists('getallheaders')) {
        return getallheaders();
    }
    
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') === 0) {
            $header = str_replace('_', '-', substr($key, 5));
            $headers[$header] = $value;
        }
    }
    return $headers;
}

/**
 * Функция для парсинга XML входных данных
 */
function parseXmlInput() {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        return [];
    }
    
    try {
        $xml = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($xml), true);
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Преобразование массива в XML
 */
function arrayToXml($data, $rootElement = 'response', $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement('<' . $rootElement . '/>');
    }
    
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            arrayToXml($value, $key, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
    
    return $xml;
}

/**
 * Функция для отправки XML ответа
 */
function sendXmlResponse($data, $statusCode = 200) {
    global $path, $method;
    
    http_response_code($statusCode);
    
    $xml = arrayToXml($data);
    $xmlResponse = $xml->asXML();
    
    log_api([
        'response' => $data,
        'response_xml' => $xmlResponse,
        'path' => $path,
        'method' => $method,
        'status_code' => $statusCode,
        'format' => 'xml',
        'time' => date('Y-m-d H:i:s')
    ]);
    
    echo $xmlResponse;
}

/**
 * Функция для отправки ответа в нужном формате (JSON или XML)
 */
function sendResponse($data, $statusCode = 200) {
    global $format;
    
    if ($format === 'xml') {
        sendXmlResponse($data, $statusCode);
    } else {
        sendJsonResponse($data, $statusCode);
    }
}

/**
 * Функция для отправки JSON ответа с логированием
 */
function sendJsonResponse($data, $statusCode = 200) {
    global $path, $method;
    http_response_code($statusCode);
    $jsonResponse = json_encode($data);
    log_api([
        'response' => $data,
        'path' => $path,
        'method' => $method,
        'status_code' => $statusCode,
        'format' => 'json',
        'time' => date('Y-m-d H:i:s')
    ]);
    echo $jsonResponse;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['action'] ?? '';
$vacancyModel = new Vacancy();
$userModel = new User();
try {
    switch ($path) {    case 'create':        if ($method === 'POST') {            // Получаем токен из заголовков
            $headers = getRequestHeaders();
            $token = '';
            if (isset($headers['Authorization'])) {
                $token = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $token = $headers['authorization'];
            }
            $token = str_replace('Bearer ', '', $token);
            error_log("VacancyAPI CREATE DEBUG - Token received: " . $token);
            error_log("VacancyAPI CREATE DEBUG - Headers: " . print_r($headers, true));
            $user_id = $userModel->getUserIdByToken($token);
            error_log("VacancyAPI CREATE DEBUG - User ID from token: " . ($user_id ? $user_id : 'NULL'));
            
            if (!$user_id) {
                sendResponse(['success' => false, 'error' => 'Неавторизовано - токен не найден'], 401);
                break;
            }
            if ($format === 'xml') {
                $data = parseXmlInput();
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }
            
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $salary = trim($data['salary'] ?? '');
            $location = trim($data['location'] ?? '');
            $company = trim($data['company'] ?? '');
            $requirements = trim($data['requirements'] ?? '');
            $employment_type = trim($data['employment_type'] ?? '');            if ($title && $description) {
                $createData = [
                    'employer_id' => $user_id,
                    'title' => $title,
                    'description' => $description,
                    'salary' => $salary,
                    'location' => $location,
                    'company' => $company,
                    'requirements' => $requirements,
                    'employment_type' => $employment_type
                ];
                
                $vacancyId = $vacancyModel->create($createData);

                if ($vacancyId) {
                    sendResponse(['success' => true, 'vacancy_id' => $vacancyId]);
                } else {
                    sendResponse(['success' => false, 'error' => 'Ошибка создания вакансии'], 400);
                }} else {
                sendResponse(['success' => false, 'error' => 'Название и описание обязательны'], 400);
            }
        }
        break;    case 'list':
        if ($method === 'GET') {
            $search = trim($_GET['search'] ?? '');
            $employer = intval($_GET['employer'] ?? 0);
            
            if ($employer > 0) {
                $vacancies = $vacancyModel->getVacanciesByEmployer($employer);
                $vacancies = array_filter($vacancies, function($v) { return $v['is_active'] == 1; });
            } elseif ($search) {
                $vacancies = $vacancyModel->search($search);
            } else {
                $vacancies = $vacancyModel->getAll();
            }
            sendResponse(['success' => true, 'vacancies' => $vacancies]);
        }
        break;case 'detail':
        if ($method === 'GET') {
            $id = intval($_GET['id'] ?? 0);
            $vacancy = $vacancyModel->getById($id);
            if ($vacancy) {
                sendResponse(['success' => true, 'vacancy' => $vacancy]);
            } else {
                sendResponse(['success' => false, 'error' => 'Вакансия не найдена'], 404);
            }
        }
        break;    case 'update':
        if ($method === 'PUT' || $method === 'POST') {
            $headers = getRequestHeaders();
            $token = '';
            if (isset($headers['Authorization'])) {
                $token = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $token = $headers['authorization'];
            }
            $token = str_replace('Bearer ', '', $token);
            error_log("VacancyAPI UPDATE DEBUG - Token received: " . $token);
            error_log("VacancyAPI UPDATE DEBUG - Headers: " . print_r($headers, true));
            $user_id = $userModel->getUserIdByToken($token);
            error_log("VacancyAPI UPDATE DEBUG - User ID from token: " . ($user_id ? $user_id : 'NULL'));
            
            if (!$user_id) {
                sendResponse(['success' => false, 'error' => 'Неавторизовано - токен не найден'], 401);
                break;
            }
            
            $id = intval($_GET['id'] ?? 0);
            error_log("VacancyAPI UPDATE DEBUG - Vacancy ID: " . $id);
            
            if ($id > 0) {
                if ($format === 'xml') {
                    $data = parseXmlInput();
                } else {
                    $data = json_decode(file_get_contents('php://input'), true);
                }
                
                error_log("VacancyAPI UPDATE DEBUG - Input data: " . print_r($data, true));
                
                $title = trim($data['title'] ?? '');
                $description = trim($data['description'] ?? '');
                $salary = trim($data['salary'] ?? '');
                $location = trim($data['location'] ?? '');
                $company = trim($data['company'] ?? '');
                $requirements = trim($data['requirements'] ?? '');
                $employment_type = trim($data['employment_type'] ?? '');
                
                if ($title && $description) {
                    $updateData = [
                        'title' => $title,
                        'description' => $description,
                        'salary' => $salary,
                        'location' => $location,
                        'company' => $company,
                        'requirements' => $requirements,
                        'employment_type' => $employment_type
                    ];
                    
                    error_log("VacancyAPI UPDATE DEBUG - Update data: " . print_r($updateData, true));
                    
                    $updateResult = $vacancyModel->update($id, $updateData, $user_id);
                    
                    error_log("VacancyAPI UPDATE DEBUG - Update result: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
                    
                    if ($updateResult) {
                        sendResponse(['success' => true]);
                    } else {
                        sendResponse(['success' => false, 'error' => 'Ошибка обновления вакансии или нет прав доступа'], 400);
                    }
                } else {
                    sendResponse(['success' => false, 'error' => 'Название и описание обязательны'], 400);
                }
            } else {
                sendResponse(['success' => false, 'error' => 'ID вакансии обязателен'], 400);
            }
        }
        break;case 'my_vacancies':
        if ($method === 'GET') {
            $headers = getRequestHeaders();
            $token = '';
            if (isset($headers['Authorization'])) {
                $token = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $token = $headers['authorization'];
            }
            $token = str_replace('Bearer ', '', $token);
            $user_id = $userModel->getUserIdByToken($token);
            if (!$user_id) {
                sendResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                break;
            }
            
            $vacancies = $vacancyModel->getVacanciesByEmployer($user_id);
            sendResponse(['success' => true, 'vacancies' => $vacancies]);
        }
        break;    case 'delete':
        if ($method === 'DELETE' || $method === 'GET') {
            $headers = getRequestHeaders();
            $token = '';
            if (isset($headers['Authorization'])) {
                $token = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $token = $headers['authorization'];
            }
            $token = str_replace('Bearer ', '', $token);
            $user_id = $userModel->getUserIdByToken($token);
            if (!$user_id) {
                sendResponse(['success' => false, 'error' => 'Неавторизовано'], 401);
                break;
            }
            
            $id = intval($_GET['id'] ?? 0);
            if ($id > 0) {
                $deleteResult = $vacancyModel->delete($id, $user_id);
                if ($deleteResult) {
                    sendResponse(['success' => true]);
                } else {
                    sendResponse(['success' => false, 'error' => 'Ошибка удаления вакансии'], 400);
                }
            } else {
                sendResponse(['success' => false, 'error' => 'ID вакансии обязателен'], 400);
            }
        }
        break;    default:
        sendResponse(['success' => false, 'error' => 'Not found'], 404);
}
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера: ' . $e->getMessage()]);
}
