<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Не авторизован', 'session' => $_SESSION]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'API работает',
    'user_id' => $_SESSION['user_id'],
    'test_time' => date('Y-m-d H:i:s'),
    'session_data' => $_SESSION
]);
?>
