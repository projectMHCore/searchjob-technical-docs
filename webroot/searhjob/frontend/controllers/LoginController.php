<?php
require_once __DIR__ . '/../models/UserModel.php';
session_start();

class LoginController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Отображение формы авторизации
     */
    public function index() {
        $data = [
            'error' => $_GET['error'] ?? null
        ];
        
        $this->render('login', $data);
    }
      /**
     * Обработка авторизации
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if ($login && $password) {
                $result = $this->userModel->login($login, $password);
                
                if ($result['success']) {
                    $_SESSION['user_id'] = $result['user_id'] ?? null;
                    $_SESSION['token'] = $result['token'] ?? null;
                    $_SESSION['user_role'] = $result['role'] ?? null;
                    $_SESSION['user_login'] = $login;
                    
                    header('Location: profile.php');
                    exit;
                } else {
                    $error = $result['error'] ?? 'Ошибка авторизации';
                }
            } else {
                $error = 'Заполните все поля!';
            }
        } else {
            $error = 'Некорректный запрос!';
        }
        header('Location: login.php?error=' . urlencode($error ?? 'Неизвестная ошибка'));
        exit;
    }
    
    /**
     * Выход из системы
     */
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
      /**
     * Рендеринг представления
     */
    private function render($view, $data = []) {
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
            }        }
    }
    
    /**
     * Обработка запросов (роутинг)
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'authenticate':
                $this->authenticate();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                $this->index();
                break;
        }
    }
}
