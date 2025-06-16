<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/ApiLogController.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['action'] ?? '';
$userModel = new User();

log_api([
    'request' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'headers' => getRequestHeaders(),
    'body' => file_get_contents('php://input')
]);

switch ($path) {    case 'register':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $login = trim($data['login'] ?? '');
            $password = $data['password'] ?? '';
            $email = trim($data['email'] ?? '');
            $role = $data['role'] ?? 'job_seeker';
            $company_name = $data['company_name'] ?? null;
            
            if ($login && $password && $email) {
                $result = $userModel->register($login, $password, $email, $role, $company_name);
                if ($result['success']) {
                    echo json_encode(['success' => true, 'user_id' => $result['user_id']]);
                    log_api(['response' => json_encode(['success' => true, 'user_id' => $result['user_id']])]);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => $result['error']]);
                    log_api(['response' => json_encode(['success' => false, 'error' => $result['error']])]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Все поля обязательны']);
                log_api(['response' => json_encode(['success' => false, 'error' => 'Все поля обязательны'])]);
            }
        }
        break;
    case 'login':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $login = trim($data['login'] ?? '');
            $password = $data['password'] ?? '';            if ($login && $password) {
                $result = $userModel->login($login, $password);
                
                if ($result['success']) {
                    $token = bin2hex(random_bytes(16));
                    $userModel->saveToken($result['user_id'], $token);
                    
                    $response = [
                        'success' => true, 
                        'token' => $token,
                        'user_id' => $result['user_id'],
                        'role' => $result['role'] ?? 'job_seeker'
                    ];
                    
                    echo json_encode($response);
                    log_api(['response' => json_encode($response)]);
                } else {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'error' => $result['error']]);
                    log_api(['response' => json_encode(['success' => false, 'error' => $result['error']])]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Все поля обязательны']);
                log_api(['response' => json_encode(['success' => false, 'error' => 'Все поля обязательны'])]);            }
        }
        break;
    case 'profile':
        if ($method === 'GET') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);            $user_id = $userModel->getUserIdByToken($token);
            if ($user_id) {
                $profile = $userModel->getProfile($user_id);
                if ($profile) {
                    $profileArray = is_object($profile) ? (array)$profile : $profile;
                    echo json_encode(['success' => true, 'profile' => $profileArray]);
                    log_api(['response' => json_encode(['success' => true, 'profile' => $profileArray])]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Профиль не найден']);
                    log_api(['response' => json_encode(['success' => false, 'error' => 'Профиль не найден'])]);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);                log_api(['response' => json_encode(['success' => false, 'error' => 'Неавторизовано'])]);
            }        }
        break;    case 'my_applications':
        if ($method === 'GET') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
            $user_id = $userModel->getUserIdByToken($token);
            if ($user_id) {
                $filters = [
                    'status' => $_GET['status'] ?? '',
                    'vacancy' => $_GET['vacancy'] ?? ''
                ];
                
                require_once __DIR__ . '/../models/JobApplication.php';
                $applicationModel = new JobApplication();
                $applications = $applicationModel->getUserApplications($user_id, $filters);
                echo json_encode(['success' => true, 'applications' => $applications]);
                log_api(['response' => json_encode(['success' => true, 'applications_count' => count($applications)])]);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
                log_api(['response' => json_encode(['success' => false, 'error' => 'Неавторизовано'])]);
            }
        }
        break;
    case 'apply':
        if ($method === 'POST') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
            $user_id = $userModel->getUserIdByToken($token);
            if ($user_id) {
                $input = json_decode(file_get_contents('php://input'), true);
                $vacancy_id = $input['vacancy_id'] ?? 0;
                $cover_letter = $input['cover_letter'] ?? '';
                  if ($vacancy_id) {
                    require_once __DIR__ . '/../models/JobApplication.php';
                    $applicationModel = new JobApplication();
                    $result = $applicationModel->create($vacancy_id, $user_id, $cover_letter);
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Заявка успешно подана']);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Ошибка при подаче заявки']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Не указана вакансия']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
            }
        }
        break;
    case 'update_application_status':
        if ($method === 'PUT') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
            $user_id = $userModel->getUserIdByToken($token);
            if ($user_id) {
                $input = json_decode(file_get_contents('php://input'), true);
                $application_id = $input['application_id'] ?? 0;
                $status = $input['status'] ?? '';
                
                if ($application_id && $status) {
                    require_once __DIR__ . '/../models/JobApplication.php';
                    $applicationModel = new JobApplication();
                    $result = $applicationModel->updateStatus($application_id, $status, $user_id);
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Статус заявки обновлен']);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Ошибка при обновлении статуса']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Неверные параметры']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
            }
        }
        break;    case 'employer_applications':
        if ($method === 'GET') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
            $user_id = $userModel->getUserIdByToken($token);
            if ($user_id) {
                $filters = [
                    'status' => $_GET['status'] ?? '',
                    'vacancy' => $_GET['vacancy'] ?? ''
                ];
                require_once __DIR__ . '/../models/JobApplication.php';
                $applicationModel = new JobApplication();                $applications = $applicationModel->getEmployerApplications($user_id, $filters);
                echo json_encode(['success' => true, 'applications' => $applications]);
                log_api(['response' => json_encode(['success' => true, 'applications_count' => count($applications)])]);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
                log_api(['response' => json_encode(['success' => false, 'error' => 'Неавторизовано'])]);
            }
        }
        break;    case 'candidate_profile':
        if ($method === 'GET') {
            error_log("candidate_profile endpoint called");
            error_log("GET params: " . print_r($_GET, true));
            error_log("Session: " . print_r($_SESSION ?? [], true));
            
            $headers = getRequestHeaders();
            $authHeader = $headers['Authorization'] ?? '';
            $token = str_replace('Bearer ', '', $authHeader);
            
            error_log("Auth header: " . $authHeader);
            error_log("Token: " . $token);
            
            $employer_id = $userModel->getUserIdByToken($token);
            
            error_log("Employer ID: " . $employer_id);
            
            if ($employer_id) {
                $candidate_id = intval($_GET['candidate_id'] ?? 0);
                
                error_log("Candidate ID: " . $candidate_id);
                
                if ($candidate_id > 0) {
                    require_once __DIR__ . '/../models/JobApplication.php';
                    $applicationModel = new JobApplication();
                    
                    $hasAccess = $applicationModel->hasEmployerAccessToCandidate($employer_id, $candidate_id);
                    
                    error_log("Has access: " . ($hasAccess ? 'true' : 'false'));
                    
                    if ($hasAccess) {
                        $profile = $userModel->getProfile($candidate_id);
                        if ($profile) {
                            $profileArray = is_object($profile) ? (array)$profile : $profile;
                            echo json_encode(['success' => true, 'profile' => $profileArray]);
                            log_api(['response' => json_encode(['success' => true, 'profile_loaded' => true])]);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'error' => 'Профиль не найден']);
                            log_api(['response' => json_encode(['success' => false, 'error' => 'Профиль не найден'])]);
                        }
                    } else {
                        http_response_code(403);
                        echo json_encode(['success' => false, 'error' => 'Нет доступа к профилю']);
                        log_api(['response' => json_encode(['success' => false, 'error' => 'Нет доступа к профилю'])]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Неверный ID кандидата']);
                    log_api(['response' => json_encode(['success' => false, 'error' => 'Неверный ID кандидата'])]);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Неавторизовано']);
                log_api(['response' => json_encode(['success' => false, 'error' => 'Неавторизовано'])]);
            }        }
        break;
        
    case 'test':
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'API is working',
            'timestamp' => date('Y-m-d H:i:s'),
            'server' => $_SERVER['SERVER_NAME'] ?? 'unknown'
        ]);
        log_api(['response' => json_encode(['success' => true, 'message' => 'API test successful'])]);
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found']);
        log_api(['response' => json_encode(['success' => false, 'error' => 'Not found'])]);
}
