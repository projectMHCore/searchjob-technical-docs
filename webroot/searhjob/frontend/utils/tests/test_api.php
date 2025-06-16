<?php
// Тест API соединения
require_once __DIR__ . '/../../models/UserModel.php';

echo "<h2>Тест API соединения SearchJob</h2>";
echo "<hr>";

$userModel = new UserModel();

// Получаем информацию о соединении
$reflection = new ReflectionClass($userModel);
$apiBaseUrlProperty = $reflection->getProperty('apiBaseUrl');
$apiBaseUrlProperty->setAccessible(true);
$apiBaseUrl = $apiBaseUrlProperty->getValue($userModel);

echo "<p><strong>API Base URL:</strong> " . htmlspecialchars($apiBaseUrl) . "</p>";

// Проверяем доступность API
$testUrl = $apiBaseUrl . '/ApiController.php?action=test';
echo "<p><strong>Test URL:</strong> " . htmlspecialchars($testUrl) . "</p>";

// Проверяем, существует ли файл
$backendPath = str_replace($_SERVER['HTTP_HOST'], '', $testUrl);
$backendPath = parse_url($backendPath, PHP_URL_PATH);
$fullPath = $_SERVER['DOCUMENT_ROOT'] . $backendPath;

echo "<p><strong>Full path:</strong> " . htmlspecialchars($fullPath) . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($fullPath) ? "YES" : "NO") . "</p>";

if (file_exists($fullPath)) {
    echo "<p style='color: green;'>✅ API файл найден</p>";
} else {
    echo "<p style='color: red;'>❌ API файл не найден</p>";
}

// Попробуем сделать тестовый запрос
echo "<hr>";
echo "<h3>Тестовый запрос к API:</h3>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>HTTP Code:</strong> " . $httpCode . "</p>";
if ($error) {
    echo "<p style='color: red;'><strong>cURL Error:</strong> " . htmlspecialchars($error) . "</p>";
}
if ($response) {
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} else {
    echo "<p style='color: red;'>No response received</p>";
}
?>
