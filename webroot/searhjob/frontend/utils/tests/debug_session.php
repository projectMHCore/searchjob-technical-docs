<?php
session_start();
echo "<h2>Отладка сессии</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['token'])) {
    echo "<h3>Тестирование API с токеном</h3>";
    $token = $_SESSION['token'];
    
    // Тестируем API вакансий
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $currentDir = dirname($_SERVER['REQUEST_URI']);
    $apiUrl = $protocol . $host . str_replace('/frontend', '/backend', $currentDir) . '/controllers/VacancyApiController.php?action=my_vacancies';
    
    echo "URL API: " . $apiUrl . "<br>";
    echo "Токен: " . $token . "<br>";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]
        ]
    ]);
    
    $response = file_get_contents($apiUrl, false, $context);
    
    echo "<h4>Ответ API:</h4>";
    echo "<pre>";
    echo htmlspecialchars($response);
    echo "</pre>";
}
?>
