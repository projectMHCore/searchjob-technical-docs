<?php
// Страница профиля пользователя (MVC архитектура)
require_once __DIR__ . '/controllers/ProfileController.php';

$controller = new ProfileController();
$controller->index();
?>
