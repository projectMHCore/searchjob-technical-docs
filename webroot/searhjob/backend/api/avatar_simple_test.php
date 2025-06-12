<?php
/**
 * Простой тест API аватаров
 */

session_start();

// Устанавливаем заголовки
header('Content-Type: application/json');

// Проверяем авторизацию
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
