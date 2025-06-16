<?php
require_once __DIR__ . '/controllers/ApplicationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $controller = new ApplicationController();
    $controller->updateStatus();
} else {
    $controller = new ApplicationController();
    $controller->manageApplications();
}
?>
