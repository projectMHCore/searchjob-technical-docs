<?php
// Страница авторизации (MVC архитектура)
require_once __DIR__ . '/controllers/LoginController.php';

// Проверяем, авторизован ли пользователь (сессия уже запущена в контроллере)
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$controller = new LoginController();
$controller->index();
?>
