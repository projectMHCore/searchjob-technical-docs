<?php
// API-контроллер для логирования сообщений (логирование всех запросов и ответов)
$logDir = __DIR__ . '/../logs/';
if (!is_dir($logDir)) mkdir($logDir, 0777, true);
$logFile = $logDir . 'api.log';

function log_api($data) {
    global $logFile;
    $entry = date('Y-m-d H:i:s') . ' ' . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
}

// Пример использования:
// log_api(['request' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD'], 'body' => file_get_contents('php://input')]);
