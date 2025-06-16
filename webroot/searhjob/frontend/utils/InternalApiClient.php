<?php

class InternalApiClient {
    
    /**
     * Выполнение внутреннего API вызова к VacancyApiController
     */
    public static function callVacancyApi($action, $method = 'GET', $data = [], $params = []) {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_GET['action'] = $action;
        foreach ($params as $key => $value) {
            $_GET[$key] = $value;
        }
        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            $backup_input = null;
            ob_start();
            echo json_encode($data);
            $input_data = ob_get_contents();
            ob_end_clean();
        }
        ob_start();
        
        try {
            require_once __DIR__ . '/../backend/controllers/VacancyApiController.php';
        } catch (Exception $e) {
            ob_end_clean();
            return ['success' => false, 'error' => 'Ошибка вызова API: ' . $e->getMessage()];
        }
        
        $output = ob_get_contents();
        ob_end_clean();
    
        unset($_GET['action']);
        foreach ($params as $key => $value) {
            unset($_GET[$key]);
        }
        $result = json_decode($output, true);
        return $result !== null ? $result : ['success' => false, 'error' => 'Некорректный ответ API'];
    }
    
    /**
     * Упрощенный вызов для получения списка вакансий
     */
    public static function getVacancies($filters = []) {
        return self::callVacancyApi('list', 'GET', [], $filters);
    }
    
    /**
     * Упрощенный вызов для получения вакансии по ID
     */
    public static function getVacancy($id) {
        return self::callVacancyApi('detail', 'GET', [], ['id' => $id]);
    }
    
    /**
     * Упрощенный вызов для создания вакансии
     */
    public static function createVacancy($data, $token) {
        $_SERVER['HTTP_AUTHORIZATION'] = $token;
        $result = self::callVacancyApi('create', 'POST', $data);
        unset($_SERVER['HTTP_AUTHORIZATION']);
        return $result;
    }
}
?>
