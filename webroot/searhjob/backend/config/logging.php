<?php
/**
 * Конфигурация логирования для SearchJob
 */
return [
    // Уровни логирования (все включены для разработки)
    'levels' => [
        'debug' => true,
        'info' => true,
        'error' => true,
        'api' => true,
        'database' => true
    ],
    
    // Максимальный размер файла лога (в байтах)
    'max_file_size' => 10 * 1024 * 1024, // 10MB
    
    // Количество дней хранения логов
    'retention_days' => 30,
    
    // Формат времени
    'timestamp_format' => 'Y-m-d H:i:s',
    
    // Логировать в файл
    'log_to_file' => true,
    
    // Логировать в error_log PHP
    'log_to_error_log' => true,
    
    // Путь к директории логов (относительно utils)
    'log_dir' => '../logs'
];
