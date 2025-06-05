<?php
// XML API-контроллер для работы с вакансиями (согласно требованиям Lab-2)
require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/ApiLogController.php';

header('Content-Type: application/xml; charset=utf-8');

// Логируем запрос
log_api([
    'request' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'body' => file_get_contents('php://input'),
    'time' => date('Y-m-d H:i:s'),
    'format' => 'XML'
]);

// Совместимая функция для получения заголовков
function getRequestHeaders() {
    $headers = [];
    if (function_exists('getallheaders')) {
        return getallheaders();
    }
    
    // Альтернативный способ для хостингов без getallheaders
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
    
    // Устанавливаем код статуса
    http_response_code($statusCode);
    
    // Преобразуем данные в XML
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;
    
    $root = $xml->createElement('response');
    $xml->appendChild($root);
    
    arrayToXml($data, $xml, $root);
    
    $xmlResponse = $xml->saveXML();
    
    // Логируем ответ
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
        
        // Преобразуем SimpleXMLElement в массив
        return json_decode(json_encode($xml), true);
    } catch (Exception $e) {
        error_log("Ошибка парсинга XML: " . $e->getMessage());
        return [];
    }
}

// Получаем токен авторизации
$headers = getRequestHeaders();
$authToken = null;
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    if (strpos($authHeader, 'Bearer ') === 0) {
        $authToken = substr($authHeader, 7);
    }
}

// Определяем действие
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$vacancyModel = new Vacancy();
$userModel = new User();

// Маршрутизация запросов
switch ($action) {
    case 'list':
        // GET /xml-api?action=list - получение списка вакансий
        if ($method === 'GET') {
            $vacancies = $vacancyModel->getAll();
            sendXmlResponse(['success' => true, 'vacancies' => $vacancies]);
        }
        break;
        
    case 'list_xml':
        // GET /xml-api?action=list_xml - получение вакансий из XML файлов
        if ($method === 'GET') {
            $vacancies = $vacancyModel->getAllVacanciesFromXML();
            sendXmlResponse(['success' => true, 'vacancies' => $vacancies, 'source' => 'xml']);
        }
        break;
        
    case 'get':
        // GET /xml-api?action=get&id=123 - получение конкретной вакансии
        if ($method === 'GET') {
            $id = intval($_GET['id'] ?? 0);
            if ($id > 0) {
                // Сначала пытаемся получить из XML
                $vacancy = $vacancyModel->getVacancyFromXML($id);
                if (!$vacancy) {
                    // Если нет в XML, получаем из БД
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
        // POST /xml-api?action=create - создание вакансии
        if ($method === 'POST') {
            // Проверяем авторизацию
            if (!$authToken) {
                sendXmlResponse(['success' => false, 'error' => 'Требуется авторизация'], 401);
            }
              $user = $userModel->getUserByToken($authToken);
            if (!$user || $user['role'] !== 'employer') {
                sendXmlResponse(['success' => false, 'error' => 'Доступ запрещен'], 403);
            }
            
            $user_id = $user['id'];
            
            // Парсим XML данные
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
        // PUT /xml-api?action=update&id=123 - обновление вакансии
        if ($method === 'PUT') {
            // Проверяем авторизацию
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
            
            // Парсим XML данные
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
        // DELETE /xml-api?action=delete&id=123 - удаление вакансии
        if ($method === 'DELETE') {
            // Проверяем авторизацию
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
