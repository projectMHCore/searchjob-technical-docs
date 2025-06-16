<?php
class UserModel {
    private $apiBaseUrl;
      public function __construct() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $currentDir = dirname($_SERVER['REQUEST_URI']);
        $this->apiBaseUrl = $protocol . $host . str_replace('/frontend', '/backend', $currentDir) . '/controllers';
        error_log("UserModel API Base URL: " . $this->apiBaseUrl);
    }
    
    /**
     * Авторизация пользователя
     */
    public function login($login, $password) {
        $data = [
            'login' => $login,
            'password' => $password
        ];
        
        $url = $this->apiBaseUrl . '/ApiController.php?action=login';
        return $this->makeApiCall($url, 'POST', $data);
    }
    
    /**
     * Регистрация пользователя
     */
    public function register($data) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=register';
        return $this->makeApiCall($url, 'POST', $data);
    }
    
    /**
     * Получение профиля пользователя
     */
    public function getProfile($token) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=profile';
        return $this->makeApiCall($url, 'GET', null, $token);
    }
    
    /**
     * Обновление профиля пользователя
     */
    public function updateProfile($data, $token) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=update_profile';
        return $this->makeApiCall($url, 'PUT', $data, $token);
    }
    
    /**
     * Получение списка пользователей (для админов)
     */
    public function getAllUsers($token) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=users';
        return $this->makeApiCall($url, 'GET', null, $token);
    }
      /**
     * Выполнение API запроса
     */    
    private function makeApiCall($url, $method, $data = null, $token = null) {
        $context = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ],
                'timeout' => 30,
                'ignore_errors' => true
            ]
        ];
        if ($token) {
            $context['http']['header'][] = 'Authorization: Bearer ' . $token;
        }
        if ($data && in_array($method, ['POST', 'PUT'])) {
            $context['http']['content'] = json_encode($data);
        }
          try {
            error_log("API Request to: $url");
            error_log("Method: $method");
            if ($data) {
                error_log("Data: " . json_encode($data));
            }           
            $response = @file_get_contents($url, false, stream_context_create($context));
            
            if ($response === false) {
                $error = error_get_last();
                error_log("API Connection error: " . print_r($error, true));
                return ['success' => false, 'error' => 'Ошибка соединения с API: ' . ($error['message'] ?? 'Неизвестная ошибка')];
            }
            
            $http_response_header = $http_response_header ?? [];
            if (!empty($http_response_header) && isset($http_response_header[0])) {
                $status_line = $http_response_header[0];
                if (strpos($status_line, '400') !== false || strpos($status_line, '500') !== false) {
                    error_log("HTTP Error: " . $status_line);
                    error_log("Response: " . $response);
                }
            }
              error_log("API Response: " . $response);
            
            $result = json_decode($response, true);
            if ($result === null) {
                error_log("JSON decode error for response: " . $response);
                return ['success' => false, 'error' => 'Некорректный ответ API: ' . substr($response, 0, 200)];
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("API Exception: " . $e->getMessage());
            return ['success' => false, 'error' => 'Ошибка API: ' . $e->getMessage()];
        }
    }
}
