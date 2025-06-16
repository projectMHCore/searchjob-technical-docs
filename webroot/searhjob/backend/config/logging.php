<?php
/**
 * Конфигурация логирования 
 */
return [
    'levels' => [
        'debug' => true,
        'info' => true,
        'error' => true,
        'api' => true,
        'database' => true
    ],
    
    'max_file_size' => 10 * 1024 * 1024, // 10MB
    
    'retention_days' => 30,
    
    'timestamp_format' => 'Y-m-d H:i:s',
    
    'log_to_file' => true,
    
    'log_to_error_log' => true,
    
    'log_dir' => '../logs'
];
