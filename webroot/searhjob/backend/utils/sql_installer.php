<?php
/**
 * Инсталлятор базы данных из SQL файла
 * Использует ваш экспортированный файл searhjob.sql для создания БД
 */

// Подключение конфигурации
$config = require __DIR__ . '/../config/db.php';

// Обработка AJAX запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'install_from_sql':
                echo json_encode(installFromSqlFile($config));
                break;
                
            case 'get_sql_info':
                echo json_encode(getSqlFileInfo());
                break;
                
            case 'backup_current':
                $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
                if ($db->connect_error) {
                    throw new Exception('Ошибка подключения к БД: ' . $db->connect_error);
                }
                echo json_encode(createBackup($db, $config));
                $db->close();
                break;
                
            case 'check_connection':
                echo json_encode(checkDatabaseConnection($config));
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    
    exit;
}

/**
 * Установка БД из SQL файла
 */
function installFromSqlFile($config) {
    $sqlFile = __DIR__ . '/../../searhjob.sql';
    
    if (!file_exists($sqlFile)) {
        return ['success' => false, 'message' => 'SQL файл не найден: ' . $sqlFile];
    }
      try {
        // Подключение к существующей БД
        $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        
        if ($db->connect_error) {
            throw new Exception('Ошибка подключения к БД: ' . $db->connect_error);
        }
        
        // Читаем SQL файл
        $sql = file_get_contents($sqlFile);
        
        if ($sql === false) {
            throw new Exception('Не удалось прочитать SQL файл');
        }
          // Удаляем комментарии и настройки MySQL
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $sql = preg_replace('/^#.*$/m', '', $sql);
        $sql = preg_replace('/^SET.*$/m', '', $sql);
        $sql = preg_replace('/^\/\*!.*?\*\/;$/m', '', $sql);
        
        // Удаляем команды CREATE DATABASE и USE DATABASE
        $sql = preg_replace('/^CREATE DATABASE.*$/mi', '', $sql);
        $sql = preg_replace('/^USE.*$/mi', '', $sql);
          // Разбиваем на отдельные запросы
        $allQueries = array_filter(array_map('trim', explode(';', $sql)));
        
        // Сортируем запросы для правильного порядка выполнения
        $createTableQueries = [];
        $insertQueries = [];
        $alterQueries = [];
        $otherQueries = [];
        
        foreach ($allQueries as $query) {
            if (empty($query) || strlen($query) < 10) {
                continue;
            }
            
            // Пропускаем команды, которые могут вызвать проблемы
            if (preg_match('/^(CREATE DATABASE|USE\s+|DROP DATABASE)/i', $query)) {
                continue;
            }
            
            // Классифицируем запросы
            if (preg_match('/^CREATE TABLE/i', $query)) {
                $createTableQueries[] = $query;
            } elseif (preg_match('/^INSERT INTO/i', $query)) {
                $insertQueries[] = $query;
            } elseif (preg_match('/^ALTER TABLE/i', $query)) {
                $alterQueries[] = $query;
            } else {
                $otherQueries[] = $query;
            }
        }
        
        // Сортируем CREATE TABLE запросы по зависимостям
        $sortedCreateQueries = sortTablesByDependencies($createTableQueries);
        
        // Объединяем все запросы в правильном порядке
        $queries = array_merge($sortedCreateQueries, $insertQueries, $alterQueries, $otherQueries);
        
        $executedQueries = 0;
        $errors = [];
          // Отключаем проверку внешних ключей временно
        $db->query("SET FOREIGN_KEY_CHECKS = 0");
        
        foreach ($queries as $query) {
            // Выполняем запрос
            if ($db->query($query)) {
                $executedQueries++;
            } else {
                // Если ошибка связана с внешними ключами, попробуем создать таблицу без них
                if (strpos($db->error, 'errno: 150') !== false && preg_match('/^CREATE TABLE/i', $query)) {
                    // Удаляем внешние ключи из запроса
                    $queryWithoutFK = preg_replace('/,\s*CONSTRAINT\s+`[^`]*`\s+FOREIGN KEY[^,)]+(\([^)]*\)\s+REFERENCES[^,)]+)/i', '', $query);
                    $queryWithoutFK = preg_replace('/,\s*FOREIGN KEY[^,)]+(\([^)]*\)\s+REFERENCES[^,)]+)/i', '', $queryWithoutFK);
                    
                    if ($db->query($queryWithoutFK)) {
                        $executedQueries++;
                        $errors[] = "Таблица создана без внешних ключей: " . substr($query, 0, 50) . "...";
                    } else {
                        $errors[] = "Ошибка в запросе: " . $db->error . " | Запрос: " . substr($query, 0, 100) . "...";
                    }
                } else {
                    $errors[] = "Ошибка в запросе: " . $db->error . " | Запрос: " . substr($query, 0, 100) . "...";
                }
            }
        }
        
        // Включаем обратно проверку внешних ключей
        $db->query("SET FOREIGN_KEY_CHECKS = 1");
        
        $db->close();
        
        if (empty($errors)) {
            return [
                'success' => true, 
                'message' => "База данных успешно установлена! Выполнено $executedQueries запросов.",
                'executed' => $executedQueries
            ];
        } else {
            return [
                'success' => false, 
                'message' => "Установка завершена с ошибками. Выполнено: $executedQueries запросов. Ошибки: " . implode('; ', array_slice($errors, 0, 3)),
                'executed' => $executedQueries,
                'errors' => $errors
            ];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Ошибка установки: ' . $e->getMessage()];
    }
}

/**
 * Сортировка CREATE TABLE запросов по зависимостям внешних ключей
 */
function sortTablesByDependencies($createTableQueries) {
    $tables = [];
    $dependencies = [];
    
    // Анализируем каждую таблицу
    foreach ($createTableQueries as $query) {
        // Извлекаем имя таблицы
        if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?([^`\s]+)`?\s*\(/i', $query, $matches)) {
            $tableName = $matches[1];
            $tables[$tableName] = $query;
            $dependencies[$tableName] = [];
            
            // Ищем внешние ключи
            if (preg_match_all('/FOREIGN KEY.*?REFERENCES\s+`?([^`\s]+)`?/i', $query, $fkMatches)) {
                $dependencies[$tableName] = $fkMatches[1];
            }
        }
    }
    
    // Сортируем таблицы топологически
    $sorted = [];
    $visited = [];
    
    function visit($table, &$tables, &$dependencies, &$sorted, &$visited) {
        if (isset($visited[$table])) {
            return;
        }
        
        $visited[$table] = true;
        
        // Сначала создаем все зависимые таблицы
        foreach ($dependencies[$table] as $dependency) {
            if (isset($tables[$dependency])) {
                visit($dependency, $tables, $dependencies, $sorted, $visited);
            }
        }
        
        // Затем добавляем текущую таблицу
        if (isset($tables[$table])) {
            $sorted[] = $tables[$table];
        }
    }
    
    foreach (array_keys($tables) as $table) {
        visit($table, $tables, $dependencies, $sorted, $visited);
    }
    
    return $sorted;
}

/**
 * Получение информации о SQL файле
 */
function getSqlFileInfo() {
    $sqlFile = __DIR__ . '/../../searhjob.sql';
    
    if (!file_exists($sqlFile)) {
        return ['success' => false, 'message' => 'SQL файл не найден'];
    }
    
    $content = file_get_contents($sqlFile);
    $size = filesize($sqlFile);
    $modified = filemtime($sqlFile);
    
    // Анализируем содержимое
    $createTableMatches = [];
    preg_match_all('/CREATE TABLE.*?`([^`]+)`/i', $content, $createTableMatches);
    $tables = $createTableMatches[1] ?? [];
    
    $insertMatches = [];
    preg_match_all('/INSERT INTO.*?`([^`]+)`/i', $content, $insertMatches);
    $dataInTables = array_unique($insertMatches[1] ?? []);
    
    return [
        'success' => true,
        'info' => [
            'file_path' => $sqlFile,
            'size' => round($size / 1024, 2) . ' KB',
            'modified' => date('Y-m-d H:i:s', $modified),
            'tables_count' => count($tables),
            'tables' => $tables,
            'data_tables' => $dataInTables,
            'has_data' => !empty($dataInTables)
        ]
    ];
}

/**
 * Проверка подключения к БД
 */
function checkDatabaseConnection($config) {
    try {
        $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        
        if ($db->connect_error) {
            return [
                'success' => false, 
                'message' => 'Ошибка подключения: ' . $db->connect_error,
                'connected' => false
            ];
        }
        
        // Получаем информацию о БД
        $info = [];
        
        $result = $db->query("SELECT VERSION() as version");
        if ($result) {
            $info['mysql_version'] = $result->fetch_assoc()['version'];
        }
        
        $result = $db->query("SELECT DATABASE() as db_name");
        if ($result) {
            $info['database'] = $result->fetch_assoc()['db_name'];
        }
        
        $result = $db->query("SHOW TABLES");
        $info['tables_count'] = $result ? $result->num_rows : 0;
        
        $db->close();
        
        return [
            'success' => true,
            'message' => 'Подключение успешно',
            'connected' => true,
            'info' => $info
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Ошибка: ' . $e->getMessage(),
            'connected' => false
        ];
    }
}

/**
 * Создание бэкапа текущей БД
 */
function createBackup($db, $config) {
    $backupDir = __DIR__ . '/../data/backups';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $filename = 'backup_before_install_' . date('Y-m-d_H-i-s') . '.sql';
    $filepath = $backupDir . '/' . $filename;
    
    // Получаем список таблиц
    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    if (empty($tables)) {
        return ['success' => true, 'message' => 'База данных пуста, бэкап не требуется', 'filename' => null];
    }
    
    $sql = "-- Backup created at " . date('Y-m-d H:i:s') . "\n";
    $sql .= "-- Database: " . $config['database'] . "\n";
    $sql .= "-- Created before SQL installation\n\n";
    
    foreach ($tables as $table) {
        // Структура таблицы
        $result = $db->query("SHOW CREATE TABLE `$table`");
        $row = $result->fetch_assoc();
        $sql .= "-- Table: $table\n";
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $row['Create Table'] . ";\n\n";
        
        // Данные таблицы
        $result = $db->query("SELECT * FROM `$table`");
        if ($result->num_rows > 0) {
            $sql .= "-- Data for table $table\n";
            while ($row = $result->fetch_assoc()) {
                $values = [];
                foreach ($row as $value) {
                    $values[] = is_null($value) ? 'NULL' : "'" . $db->real_escape_string($value) . "'";
                }
                $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }
    }
    
    if (file_put_contents($filepath, $sql)) {
        return ['success' => true, 'message' => "Бэкап создан: $filename", 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Ошибка создания бэкапа'];
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Установка базы данных - SearchJob</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .info-card h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 500;
            color: #666;
        }
        
        .info-value {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .status-connected {
            color: #27ae60;
        }
        
        .status-disconnected {
            color: #e74c3c;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-warning:hover {
            background: #e67e22;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .installation-steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .step {
            padding: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #dee2e6;
            background: white;
            border-radius: 0 5px 5px 0;
        }
        
        .step.active {
            border-left-color: #f39c12;
            background: #fff3cd;
        }
        
        .step.completed {
            border-left-color: #27ae60;
            background: #d4edda;
        }
        
        .step.error {
            border-left-color: #e74c3c;
            background: #f8d7da;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .tables-list {
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
        
        .table-item {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .table-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Установка базы данных</h1>
            <p>Установка базы данных из файла searhjob.sql</p>
        </div>
        
        <div class="content">
            <a href="database_manager.php" class="back-link">← Вернуться к управлению БД</a>
            
            <div id="alerts"></div>
            
            <div class="info-grid">
                <div class="info-card">
                    <h3>🗄️ Информация о базе данных</h3>
                    <div id="dbInfo">
                        <div class="loading">Загрузка информации о БД...</div>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3>📄 Информация о SQL файле</h3>
                    <div id="sqlInfo">
                        <div class="loading">Загрузка информации о файле...</div>
                    </div>
                </div>
            </div>
            
            <div class="installation-steps">
                <h3>Этапы установки:</h3>
                <div class="step" id="step1">
                    <strong>1. Проверка подключения к БД</strong>
                    <div>Проверяем возможность подключения к серверу базы данных</div>
                </div>
                <div class="step" id="step2">
                    <strong>2. Анализ SQL файла</strong>
                    <div>Анализируем структуру и содержимое файла searhjob.sql</div>
                </div>
                <div class="step" id="step3">
                    <strong>3. Создание бэкапа (опционально)</strong>
                    <div>Создаем резервную копию текущих данных</div>
                </div>
                <div class="step" id="step4">
                    <strong>4. Установка новой БД</strong>
                    <div>Выполняем SQL запросы из файла для создания новой структуры БД</div>
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <button class="btn btn-warning" onclick="createBackupBeforeInstall()">💾 Создать бэкап перед установкой</button>
                <button class="btn btn-success" onclick="installDatabase()">🚀 Установить базу данных</button>
                <button class="btn btn-primary" onclick="refreshInfo()">🔄 Обновить информацию</button>
            </div>
            
            <div class="alert alert-warning">
                <strong>⚠️ Внимание!</strong> Установка новой базы данных может удалить существующие данные. 
                Обязательно создайте бэкап перед установкой!
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            refreshInfo();
        });
        
        function showAlert(message, type = 'success') {
            const alertsContainer = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = message;
            alertsContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 10000);
        }
        
        function setStepStatus(stepId, status) {
            const step = document.getElementById(stepId);
            step.className = 'step ' + status;
        }
        
        function refreshInfo() {
            loadDatabaseInfo();
            loadSqlFileInfo();
        }
        
        function loadDatabaseInfo() {
            const dbInfo = document.getElementById('dbInfo');
            dbInfo.innerHTML = '<div class="loading">Проверка подключения...</div>';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=check_connection'
            })
            .then(response => response.json())
            .then(data => {
                if (data.connected) {
                    setStepStatus('step1', 'completed');
                    const info = data.info;
                    dbInfo.innerHTML = `
                        <div class="info-item">
                            <span class="info-label">Статус:</span>
                            <span class="info-value status-connected">✅ Подключено</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">База данных:</span>
                            <span class="info-value">${info.database}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">MySQL версия:</span>
                            <span class="info-value">${info.mysql_version}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Таблиц:</span>
                            <span class="info-value">${info.tables_count}</span>
                        </div>
                    `;
                } else {
                    setStepStatus('step1', 'error');
                    dbInfo.innerHTML = `
                        <div class="info-item">
                            <span class="info-label">Статус:</span>
                            <span class="info-value status-disconnected">❌ Ошибка подключения</span>
                        </div>
                        <div class="alert alert-danger">${data.message}</div>
                    `;
                }
            })
            .catch(error => {
                setStepStatus('step1', 'error');
                dbInfo.innerHTML = `<div class="alert alert-danger">Ошибка: ${error.message}</div>`;
            });
        }
        
        function loadSqlFileInfo() {
            const sqlInfo = document.getElementById('sqlInfo');
            sqlInfo.innerHTML = '<div class="loading">Анализ SQL файла...</div>';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_sql_info'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setStepStatus('step2', 'completed');
                    const info = data.info;
                    sqlInfo.innerHTML = `
                        <div class="info-item">
                            <span class="info-label">Размер файла:</span>
                            <span class="info-value">${info.size}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Изменен:</span>
                            <span class="info-value">${info.modified}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Таблиц в файле:</span>
                            <span class="info-value">${info.tables_count}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Есть данные:</span>
                            <span class="info-value">${info.has_data ? '✅ Да' : '❌ Нет'}</span>
                        </div>
                        ${info.tables.length > 0 ? `
                            <div style="margin-top: 10px;">
                                <strong>Таблицы:</strong>
                                <div class="tables-list">
                                    ${info.tables.map(table => `<div class="table-item">${table}</div>`).join('')}
                                </div>
                            </div>
                        ` : ''}
                    `;
                } else {
                    setStepStatus('step2', 'error');
                    sqlInfo.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                setStepStatus('step2', 'error');
                sqlInfo.innerHTML = `<div class="alert alert-danger">Ошибка: ${error.message}</div>`;
            });
        }
        
        function createBackupBeforeInstall() {
            setStepStatus('step3', 'active');
            showAlert('Создание бэкапа...', 'warning');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=backup_current'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setStepStatus('step3', 'completed');
                    showAlert('✅ ' + data.message, 'success');
                } else {
                    setStepStatus('step3', 'error');
                    showAlert('❌ Ошибка создания бэкапа: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                setStepStatus('step3', 'error');
                showAlert('❌ Ошибка создания бэкапа: ' + error.message, 'danger');
            });
        }
        
        function installDatabase() {
            if (!confirm('Вы уверены, что хотите установить новую базу данных? Это может удалить существующие данные!')) {
                return;
            }
            
            setStepStatus('step4', 'active');
            showAlert('🚀 Установка базы данных...', 'warning');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=install_from_sql'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setStepStatus('step4', 'completed');
                    showAlert(`✅ ${data.message} (Выполнено запросов: ${data.executed})`, 'success');
                    
                    // Обновляем информацию о БД
                    setTimeout(() => {
                        loadDatabaseInfo();
                    }, 2000);
                } else {
                    setStepStatus('step4', 'error');
                    showAlert('❌ ' + data.message, 'danger');
                    
                    if (data.errors && data.errors.length > 0) {
                        showAlert('Подробные ошибки:<br>' + data.errors.slice(0, 5).join('<br>'), 'danger');
                    }
                }
            })
            .catch(error => {
                setStepStatus('step4', 'error');
                showAlert('❌ Ошибка установки: ' + error.message, 'danger');
            });
        }
    </script>
</body>
</html>
