<?php
/**
 * Утилита для внутренних API вызовов без HTTP
 * Используется для вызова backend контроллеров напрямую
 */

class InternalApiClient {
    
    /**
     * Выполнение внутреннего API вызова к VacancyApiController
     */
    public static function callVacancyApi($action, $method = 'GET', $data = [], $params = []) {
        // Имитируем HTTP запрос для backend контроллера
        $_SERVER['REQUEST_METHOD'] = $method;
        $_GET['action'] = $action;
        
        // Добавляем параметры в $_GET
        foreach ($params as $key => $value) {
            $_GET[$key] = $value;
        }
        
        // Для POST/PUT запросов помещаем данные в буфер
        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            $backup_input = null;
            // Сохраняем оригинальный input если есть
            ob_start();
            echo json_encode($data);
            $input_data = ob_get_contents();
            ob_end_clean();
        }
        
        // Захватываем вывод контроллера
        ob_start();
        
        try {
            // Подключаем и вызываем контроллер
            require_once __DIR__ . '/../backend/controllers/VacancyApiController.php';
        } catch (Exception $e) {
            // В случае ошибки возвращаем JSON с ошибкой
            ob_end_clean();
            return ['success' => false, 'error' => 'Ошибка вызова API: ' . $e->getMessage()];
        }
        
        $output = ob_get_contents();
        ob_end_clean();
        
        // Очищаем $_GET и $_SERVER
        unset($_GET['action']);
        foreach ($params as $key => $value) {
            unset($_GET[$key]);
        }
          // Декодируем JSON ответ
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
        // Добавляем токен в заголовки
        $_SERVER['HTTP_AUTHORIZATION'] = $token;
        $result = self::callVacancyApi('create', 'POST', $data);
        unset($_SERVER['HTTP_AUTHORIZATION']);
        return $result;
    }
}
?>
