<?php
// Обработка действий авторизации (MVC архитектура)
require_once __DIR__ . '/controllers/LoginController.php';

$controller = new LoginController();
$controller->handleRequest();
?>
