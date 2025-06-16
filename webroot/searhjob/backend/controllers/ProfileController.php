<?php
require_once __DIR__ . '/../models/User.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../frontend/login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$userModel = new User();
$profile = $userModel->getProfile($user_id);
