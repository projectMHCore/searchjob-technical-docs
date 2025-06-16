<?php
require_once __DIR__ . '/../models/UserModel.php';
session_start();

class ProfileController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Отображение профиля пользователя
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $result = $this->userModel->getProfile($_SESSION['token']);
          $data = [
            'profile' => $result['success'] ? ($result['profile'] ?? null) : null,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Ошибка загрузки профиля'),
            'success' => $_GET['success'] ?? null
        ];
        
        $this->render('profile', $data);
    }
    
    /**
     * Старый метод для обратной совместимости
     */
    public function handleRequest() {
        $this->index();
    }
    
    /**
     * Рендеринг представления
     */    private function render($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . "/../views/{$view}_view_new.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            $fallbackFile = __DIR__ . "/../views/{$view}_view.php";
            if (file_exists($fallbackFile)) {
                include $fallbackFile;
            } else {
                echo "Представление {$view} не найдено";
            }
        }
    }
}
