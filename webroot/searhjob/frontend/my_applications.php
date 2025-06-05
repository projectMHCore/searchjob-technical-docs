<?php
// Страница просмотра заявок соискателя (MVC архитектура)
require_once __DIR__ . '/controllers/ApplicationController.php';

// Создаем экземпляр контроллера и вызываем метод для отображения заявок
$controller = new ApplicationController();
$controller->myApplications();
?>

