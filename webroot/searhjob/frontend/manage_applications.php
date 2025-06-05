<?php
// Страница управления заявками для работодателя (MVC архитектура)
require_once __DIR__ . '/controllers/ApplicationController.php';

// Обработка обновления статуса через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $controller = new ApplicationController();
    $controller->updateStatus();
} else {
    // Отображение страницы управления заявками
    $controller = new ApplicationController();
    $controller->manageApplications();
}
?>
