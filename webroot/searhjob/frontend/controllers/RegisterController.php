<?php
// Контроллер для регистрации пользователей (MVC архитектура)
require_once __DIR__ . '/../models/UserModel.php';
session_start();

class RegisterController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Отображение формы регистрации
     */
    public function index() {
        $data = [
            'error' => $_GET['error'] ?? null,
            'success' => $_GET['success'] ?? null
        ];
        
        $this->render('register', $data);
    }
      /**
     * Обработка регистрации
     */
    public function store() {
        // Включаем буферизацию для предотвращения проблем с заголовками
        ob_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $role = $_POST['role'] ?? 'job_seeker';
            $company_name = trim($_POST['company_name'] ?? '');
            
            // Валидация
            if (!$login || !$password || !$email) {
                $error = 'Заполните все обязательные поля!';
            } elseif ($password !== $confirm_password) {
                $error = 'Пароли не совпадают!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Некорректный email!';
            } elseif ($role === 'employer' && !$company_name) {
                $error = 'Для работодателя обязательно указание названия компании!';
            } else {
                $data = [
                    'login' => $login,
                    'password' => $password,
                    'email' => $email,
                    'role' => $role,
                    'company_name' => $company_name
                ];
                  $result = $this->userModel->register($data);
                
                if ($result['success']) {
                    ob_end_clean(); // Очищаем буфер перед редиректом
                    header('Location: register.php?success=registered');
                    exit;
                } else {
                    $error = $result['error'];
                }
            }
        } else {
            $error = 'Некорректный запрос!';        }
        
        // Очищаем буфер перед редиректом
        ob_end_clean();
        // Передаём ошибку обратно на форму
        header('Location: register.php?error=' . urlencode($error));
        exit;
    }
    
    /**
     * Обработка запросов (роутинг)
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'store';
        
        switch ($action) {
            case 'store':
                $this->store();
                break;
            default:
                $this->store();
                break;
        }
    }

    /**
     * Рендеринг представления
     */    private function render($view, $data = []) {
        // Извлекаем переменные для использования в представлении
        extract($data);
        
        // Подключаем новую профессиональную версию представления
        $viewFile = __DIR__ . "/../views/{$view}_view_new.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Fallback на старую версию, если новая не найдена
            $fallbackFile = __DIR__ . "/../views/{$view}_view.php";
            if (file_exists($fallbackFile)) {
                include $fallbackFile;
            } else {
                echo "Представление {$view} не найдено";
            }
        }
    }
}
