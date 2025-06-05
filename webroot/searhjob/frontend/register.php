<?php
// Страница регистрации (MVC архитектура)
require_once __DIR__ . '/controllers/RegisterController.php';

// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$controller = new RegisterController();
$controller->index();
?>
