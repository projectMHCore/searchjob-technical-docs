<?php
class ApplicationModel {
    private $apiBaseUrl;
      public function __construct() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $currentDir = dirname($_SERVER['REQUEST_URI']);
        $this->apiBaseUrl = $protocol . $host . str_replace('/frontend', '/backend', $currentDir) . '/controllers';
    }
      /**
     * Создание заявки на вакансию
     */
    public function createApplication($data, $token) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=apply';
        return $this->makeApiCall($url, 'POST', $data, $token);
    }
      /**
     * Получение заявок пользователя
     */
    public function getUserApplications($token, $filters = []) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=my_applications';
        
        if (!empty($filters['status'])) {
            $url .= '&status=' . urlencode($filters['status']);
        }
        if (!empty($filters['vacancy'])) {
            $url .= '&vacancy=' . urlencode($filters['vacancy']);
        }
        
        return $this->makeApiCall($url, 'GET', null, $token);
    }
    
    /**
     * Получение заявок на вакансии работодателя
     */
    public function getEmployerApplications($token, $filters = []) {
        $url = $this->apiBaseUrl . '/ApiController.php?action=employer_applications';
        
        if (!empty($filters['status'])) {
            $url .= '&status=' . urlencode($filters['status']);
        }
        if (!empty($filters['vacancy'])) {
            $url .= '&vacancy=' . urlencode($filters['vacancy']);
        }
        
        return $this->makeApiCall($url, 'GET', null, $token);
    }
    
    /**
     * Изменение статуса заявки
     */
    public function updateStatus($application_id, $status, $token) {
        $data = [
            'application_id' => $application_id,
            'status' => $status
        ];
        
        $url = $this->apiBaseUrl . '/ApiController.php?action=update_application_status';
        return $this->makeApiCall($url, 'PUT', $data, $token);
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
                ]
            ]
        ];
        
        if ($token) {
            $context['http']['header'][] = 'Authorization: Bearer ' . $token;
        }

        if ($data && in_array($method, ['POST', 'PUT'])) {
            $context['http']['content'] = json_encode($data);
        }
          try {
            $response = file_get_contents($url, false, stream_context_create($context));
            if ($response === false) {
                return ['success' => false, 'error' => 'Ошибка соединения с API'];
            }
            
            $result = json_decode($response, true);
            return $result !== null ? $result : ['success' => false, 'error' => 'Некорректный ответ API'];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Ошибка API: ' . $e->getMessage()];
        }
    }
}
