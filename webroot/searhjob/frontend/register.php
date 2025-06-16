<?php
require_once __DIR__ . '/controllers/RegisterController.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$controller = new RegisterController();
$controller->index();
?>
