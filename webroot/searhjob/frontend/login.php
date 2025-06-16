<?php

require_once __DIR__ . '/controllers/LoginController.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$controller = new LoginController();
$controller->index();
?>
