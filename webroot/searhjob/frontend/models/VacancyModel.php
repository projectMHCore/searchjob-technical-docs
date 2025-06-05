<?php
// Клиентская модель для работы с вакансиями
class VacancyModel {
    private $apiBaseUrl;    public function __construct() {
        // Определяем базовый URL для API запросов
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $currentDir = dirname($_SERVER['REQUEST_URI']);
        
        // Строим путь к backend API
        $this->apiBaseUrl = $protocol . $host . str_replace('/frontend', '/backend', $currentDir) . '/controllers/VacancyApiController.php';
    }
    
    /**
     * Получение всех вакансий
     */
    public function getAllVacancies($filters = []) {
        $url = $this->apiBaseUrl . '?action=list';
        
        // Добавляем фильтры к URL
        if (!empty($filters)) {
            $url .= '&' . http_build_query($filters);
        }
        
        return $this->makeApiCall($url, 'GET');
    }
    
    /**
     * Получение конкретной вакансии
     */    public function getVacancy($id) {
        $url = $this->apiBaseUrl . "?action=detail&id=" . intval($id);
        return $this->makeApiCall($url, 'GET');
    }
    
    /**
     * Создание вакансии
     */
    public function createVacancy($data, $token) {
        $url = $this->apiBaseUrl . '?action=create';
        return $this->makeApiCall($url, 'POST', $data, $token);
    }
    
    /**
     * Обновление вакансии
     */
    public function updateVacancy($id, $data, $token) {
        $url = $this->apiBaseUrl . "?action=update&id=" . intval($id);
        return $this->makeApiCall($url, 'PUT', $data, $token);
    }
    
    /**
     * Удаление вакансии
     */
    public function deleteVacancy($id, $token) {
        $url = $this->apiBaseUrl . "?action=delete&id=" . intval($id);
        return $this->makeApiCall($url, 'DELETE', null, $token);
    }
    
    /**
     * Получение вакансий конкретного работодателя
     */
    public function getEmployerVacancies($token) {
        $url = $this->apiBaseUrl . '?action=my_vacancies';
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
                ]
            ]
        ];
        
        // Добавляем токен авторизации если есть
        if ($token) {
            $context['http']['header'][] = 'Authorization: Bearer ' . $token;
        }
        
        // Добавляем данные для POST/PUT запросов
        if ($data && in_array($method, ['POST', 'PUT'])) {
            $context['http']['content'] = json_encode($data);
        }        try {
            $response = file_get_contents($url, false, stream_context_create($context));
            if ($response === false) {
                return ['success' => false, 'error' => 'Ошибка соединения с API'];
            }
            
            // Временная отладка - сохраним ответ в лог
            file_put_contents(__DIR__ . '/../debug_api_response.log', 
                date('Y-m-d H:i:s') . " URL: $url\nResponse: $response\n\n", FILE_APPEND);
            
            $result = json_decode($response, true);
            
            // Дополнительная отладка
            if ($result === null) {
                file_put_contents(__DIR__ . '/../debug_api_response.log', 
                    date('Y-m-d H:i:s') . " JSON DECODE ERROR for: $response\n\n", FILE_APPEND);
                return ['success' => false, 'error' => 'Некорректный JSON ответ API: ' . $response];
            }
            
            return $result;
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Ошибка API: ' . $e->getMessage()];
        }
    }
}
