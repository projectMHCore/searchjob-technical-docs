<?php
// XML API-контроллер для работы с вакансиями
require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/ApiLogController.php';

header('Content-Type: application/xml; charset=utf-8');

log_api([
    'request' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'body' => file_get_contents('php://input'),
    'time' => date('Y-m-d H:i:s'),
    'format' => 'XML'
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
 * Функция для отправки XML ответа с логированием
 */
function sendXmlResponse($data, $statusCode = 200) {
    global $path, $method;
    http_response_code($statusCode);
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;
    
    $root = $xml->createElement('response');
    $xml->appendChild($root);
    
    arrayToXml($data, $xml, $root);
    
    $xmlResponse = $xml->saveXML();
    log_api([
        'response' => $data,
        'path' => $path,
        'method' => $method,
        'status_code' => $statusCode,
        'time' => date('Y-m-d H:i:s'),
        'format' => 'XML'
    ]);
    
    echo $xmlResponse;
    exit;
}

/**
 * Рекурсивная функция для преобразования массива в XML
 */
function arrayToXml($array, $xml, $parent) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $element = $xml->createElement('item');
            } else {
                $element = $xml->createElement($key);
            }
            $parent->appendChild($element);
            arrayToXml($value, $xml, $element);
        } else {
            $element = $xml->createElement($key, htmlspecialchars((string)$value));
            $parent->appendChild($element);
        }
    }
}

/**
 * Функция для парсинга XML данных из запроса
 */
function parseXmlInput() {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        return [];
    }
    
    try {
        $xml = simplexml_load_string($input);
        if ($xml === false) {
            return [];
        }
        return json_decode(json_encode($xml), true);
    } catch (Exception $e) {
        error_log("Ошибка парсинга XML: " . $e->getMessage());
        return [];
    }
}

$headers = getRequestHeaders();
$authToken = null;
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    if (strpos($authHeader, 'Bearer ') === 0) {
        $authToken = substr($authHeader, 7);
    }
}
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$vacancyModel = new Vacancy();
$userModel = new User();
switch ($action) {
    case 'list':
        if ($method === 'GET') {
            $vacancies = $vacancyModel->getAll();
            sendXmlResponse(['success' => true, 'vacancies' => $vacancies]);
        }
        break;
        
    case 'list_xml':
        if ($method === 'GET') {
            $vacancies = $vacancyModel->getAllVacanciesFromXML();
            sendXmlResponse(['success' => true, 'vacancies' => $vacancies, 'source' => 'xml']);
        }
        break;
        
    case 'get':
        if ($method === 'GET') {
            $id = intval($_GET['id'] ?? 0);
            if ($id > 0) {
                $vacancy = $vacancyModel->getVacancyFromXML($id);
                if (!$vacancy) {
                    $vacancy = $vacancyModel->getById($id);
                }
                
                if ($vacancy) {
                    sendXmlResponse(['success' => true, 'vacancy' => $vacancy]);
                } else {
                    sendXmlResponse(['success' => false, 'error' => 'Вакансия не найдена'], 404);
                }
            } else {
                sendXmlResponse(['success' => false, 'error' => 'Некорректный ID'], 400);
            }
        }
        break;
        
    case 'create':
        if ($method === 'POST') {
            if (!$authToken) {
                sendXmlResponse(['success' => false, 'error' => 'Требуется авторизация'], 401);
            }
              $user = $userModel->getUserByToken($authToken);
            if (!$user || $user['role'] !== 'employer') {
                sendXmlResponse(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            
            $user_id = $user['id'];
    
            $data = parseXmlInput();
            
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            $salary = trim($data['salary'] ?? '');
            $location = trim($data['location'] ?? '');
            $company = trim($data['company'] ?? '');
            $requirements = trim($data['requirements'] ?? '');
            $employment_type = trim($data['employment_type'] ?? '');
            
            if ($title && $description) {
                $vacancyId = $vacancyModel->create([
                    'employer_id' => $user_id,
                    'title' => $title,
                    'description' => $description,
                    'salary' => $salary,
                    'location' => $location,
                    'company' => $company,
                    'requirements' => $requirements,
                    'employment_type' => $employment_type
                ]);
                
                if ($vacancyId) {
                    sendXmlResponse(['success' => true, 'vacancy_id' => $vacancyId]);
                } else {
                    sendXmlResponse(['success' => false, 'error' => 'Ошибка создания вакансии'], 500);
                }
            } else {
                sendXmlResponse(['success' => false, 'error' => 'Заполните обязательные поля'], 400);
            }
        }
        break;
        
    case 'update':
        if ($method === 'PUT') {
            if (!$authToken) {
                sendXmlResponse(['success' => false, 'error' => 'Требуется авторизация'], 401);
            }
              $user = $userModel->getUserByToken($authToken);
            if (!$user || $user['role'] !== 'employer') {
                sendXmlResponse(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                sendXmlResponse(['success' => false, 'error' => 'Некорректный ID'], 400);
            }
            
            $data = parseXmlInput();
            
            $updateData = [
                'title' => trim($data['title'] ?? ''),
                'description' => trim($data['description'] ?? ''),
                'salary' => trim($data['salary'] ?? ''),
                'location' => trim($data['location'] ?? ''),
                'company' => trim($data['company'] ?? ''),
                'requirements' => trim($data['requirements'] ?? ''),
                'employment_type' => trim($data['employment_type'] ?? '')
            ];
              if ($updateData['title'] && $updateData['description']) {
                $result = $vacancyModel->update($id, $updateData, $user['id']);
                
                if ($result) {
                    sendXmlResponse(['success' => true, 'message' => 'Вакансия обновлена']);
                } else {
                    sendXmlResponse(['success' => false, 'error' => 'Ошибка обновления вакансии'], 500);
                }
            } else {
                sendXmlResponse(['success' => false, 'error' => 'Заполните обязательные поля'], 400);
            }
        }
        break;
        
    case 'delete':
        if ($method === 'DELETE') {
            if (!$authToken) {
                sendXmlResponse(['success' => false, 'error' => 'Требуется авторизация'], 401);
            }
              $user = $userModel->getUserByToken($authToken);
            if (!$user || $user['role'] !== 'employer') {
                sendXmlResponse(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                sendXmlResponse(['success' => false, 'error' => 'Некорректный ID'], 400);
            }
            
            $result = $vacancyModel->delete($id, $user['id']);
            
            if ($result) {
                sendXmlResponse(['success' => true, 'message' => 'Вакансия удалена']);
            } else {
                sendXmlResponse(['success' => false, 'error' => 'Ошибка удаления вакансии'], 500);
            }
        }
        break;
        
    default:
        sendXmlResponse(['success' => false, 'error' => 'Неизвестное действие'], 400);
}
?>
