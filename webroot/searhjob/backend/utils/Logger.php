<?php
/**
 * Централизованная система логирования для SearchJob
 */
class Logger {
    private static $logDir;
    
    public static function init() {
        self::$logDir = __DIR__ . '/../logs';
        
        // Создаем директорию логов если не существует
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }
    
    /**
     * Запись общих логов приложения
     */
    public static function info($message, $context = []) {
        self::writeLog('info', $message, $context);
    }
    
    /**
     * Запись ошибок
     */
    public static function error($message, $context = []) {
        self::writeLog('error', $message, $context);
    }
    
    /**
     * Запись отладочной информации
     */
    public static function debug($message, $context = []) {
        self::writeLog('debug', $message, $context);
    }
    
    /**
     * Запись API логов
     */
    public static function api($message, $context = []) {
        self::writeLog('api', $message, $context);
    }
    
    /**
     * Запись логов базы данных
     */
    public static function database($message, $context = []) {
        self::writeLog('database', $message, $context);
    }
    
    /**
     * Основной метод записи логов
     */
    private static function writeLog($level, $message, $context = []) {
        if (!self::$logDir) {
            self::init();
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logFile = self::$logDir . '/' . $level . '_' . date('Y-m-d') . '.log';
        
        $logEntry = sprintf(
            "[%s] %s: %s%s\n",
            $timestamp,
            strtoupper($level),
            $message,
            !empty($context) ? ' | Context: ' . json_encode($context) : ''
        );
        
        // Записываем в файл
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Также записываем в стандартный error_log для критических ошибок
        if ($level === 'error') {
            error_log($message);
        }
    }
    
    /**
     * Очистка старых логов (старше 30 дней)
     */
    public static function cleanOldLogs($days = 30) {
        if (!self::$logDir) {
            self::init();
        }
        
        $files = glob(self::$logDir . '/*.log');
        $cutoff = time() - ($days * 24 * 60 * 60);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }
}

// Инициализируем при загрузке
Logger::init();
