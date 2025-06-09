<?php
// Сторінка управління заявками для роботодавця (MVC архітектура)
require_once __DIR__ . '/controllers/ApplicationController.php';

// Обробка оновлення статусу через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $controller = new ApplicationController();
    $controller->updateStatus();
} else {
    // Відображення сторінки управління заявками
    $controller = new ApplicationController();
    $controller->manageApplications();
}
?>
