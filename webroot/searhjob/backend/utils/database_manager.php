<?php
$config = require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    try {
        $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        
        if ($db->connect_error) {
            throw new Exception('Ошибка подключения к БД: ' . $db->connect_error);
        }
        
        switch ($action) {
            case 'get_tables':
                echo json_encode(getTablesList($db));
                break;
                
            case 'get_table_data':
                $table = $_POST['table'] ?? '';
                echo json_encode(getTableData($db, $table));
                break;
                
            case 'backup_database':
                echo json_encode(createBackup($db, $config));
                break;
                
            case 'reinstall_database':
                echo json_encode(reinstallDatabase($db));
                break;
                
            case 'get_db_stats':
                echo json_encode(getDatabaseStats($db));
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
        }
        
        $db->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    
    exit;
}

/**
 * Получение списка таблиц
 */
function getTablesList($db) {
    $result = $db->query("SHOW TABLES");
    $tables = [];
    
    while ($row = $result->fetch_array()) {
        $tableName = $row[0];
        
        $countResult = $db->query("SELECT COUNT(*) as count FROM `$tableName`");
        $count = $countResult->fetch_assoc()['count'];
        
        $structureResult = $db->query("DESCRIBE `$tableName`");
        $structure = [];
        while ($field = $structureResult->fetch_assoc()) {
            $structure[] = $field;
        }
        
        $tables[] = [
            'name' => $tableName,
            'count' => $count,
            'structure' => $structure
        ];
    }
    
    return ['success' => true, 'tables' => $tables];
}

/**
 * Получение данных из таблицы
 */
function getTableData($db, $table) {
    if (empty($table)) {
        return ['success' => false, 'message' => 'Не указана таблица'];
    }
    
    $tablesResult = $db->query("SHOW TABLES LIKE '$table'");
    if ($tablesResult->num_rows === 0) {
        return ['success' => false, 'message' => 'Таблица не найдена'];
    }
    
    $result = $db->query("SELECT * FROM `$table` LIMIT 100");
    $data = [];
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    return ['success' => true, 'data' => $data];
}

/**
 * Создание бэкапа БД
 */
function createBackup($db, $config) {
    $backupDir = __DIR__ . '/../data/backups';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $filepath = $backupDir . '/' . $filename;
    
    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    $sql = "-- Backup created at " . date('Y-m-d H:i:s') . "\n";
    $sql .= "-- Database: " . $config['database'] . "\n\n";
    
    foreach ($tables as $table) {
        $result = $db->query("SHOW CREATE TABLE `$table`");
        $row = $result->fetch_assoc();
        $sql .= "-- Table: $table\n";
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $row['Create Table'] . ";\n\n";
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

/**
 * Переустановка БД
 */
function reinstallDatabase($db) {
    $result = $db->query("SHOW TABLES");
    $tables = [];
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    foreach ($tables as $table) {
        $db->query("DROP TABLE IF EXISTS `$table`");
    }
    $db->query("SET FOREIGN_KEY_CHECKS = 1");
    ob_start();
    include __DIR__ . '/init_database.php';
    $output = ob_get_clean();
    
    return ['success' => true, 'message' => 'База данных переустановлена', 'output' => $output];
}

/**
 * Получение статистики БД
 */
function getDatabaseStats($db) {
    $stats = [];
    $result = $db->query("SELECT VERSION() as version");
    $stats['mysql_version'] = $result->fetch_assoc()['version'];
    $result = $db->query("
        SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS size_mb
        FROM information_schema.tables 
        WHERE table_schema = DATABASE()
    ");
    $stats['size_mb'] = $result->fetch_assoc()['size_mb'];
    $result = $db->query("SHOW TABLES");
    $stats['tables_count'] = $result->num_rows;
    
    return ['success' => true, 'stats' => $stats];
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление базой данных - SearchJob</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .stat-value {
            font-size: 1.8em;
            font-weight: bold;
            color: #3498db;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
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
        
        .tables-section {
            margin-top: 30px;
        }
        
        .table-item {
            background: #f8f9fa;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        
        .table-header {
            background: #e9ecef;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header:hover {
            background: #dee2e6;
        }
        
        .table-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .table-count {
            background: #3498db;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .table-details {
            padding: 20px;
            display: none;
        }
        
        .structure-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .structure-table th,
        .structure-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .structure-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            display: block;
        }
        
        .data-table thead,
        .data-table tbody {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .data-table th,
        .data-table td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .data-table th {
            background: #f8f9fa;
            position: sticky;
            top: 0;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ Управление базой данных</h1>
            <p>Просмотр структуры, создание бэкапов и переустановка базы данных</p>
        </div>
        
        <div class="content">
            <a href="../../../frontend/" class="back-link">← Вернуться к приложению</a>
            
            <div id="alerts"></div>
            
            <div class="stats-grid" id="statsGrid">
                <div class="loading">Загрузка статистики...</div>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="loadTables()">🔍 Просмотреть таблицы</button>
                <button class="btn btn-success" onclick="createBackup()">💾 Создать бэкап</button>
                <button class="btn btn-warning" onclick="if(confirm('Вы уверены? Это действие нельзя отменить!')) reinstallDatabase()">🔄 Переустановить БД</button>
                <button class="btn btn-primary" onclick="loadStats()">📊 Обновить статистику</button>
            </div>
            
            <div class="tables-section">
                <h2>Таблицы базы данных</h2>
                <div id="tablesContainer">
                    <div class="loading">Нажмите "Просмотреть таблицы" для загрузки</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
        });
        
        function showAlert(message, type = 'success') {
            const alertsContainer = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertsContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        function loadStats() {
            const statsGrid = document.getElementById('statsGrid');
            statsGrid.innerHTML = '<div class="loading">Загрузка статистики...</div>';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_db_stats'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.stats;
                    statsGrid.innerHTML = `
                        <div class="stat-card">
                            <h3>Версия MySQL</h3>
                            <div class="stat-value">${stats.mysql_version}</div>
                        </div>
                        <div class="stat-card">
                            <h3>Размер БД</h3>
                            <div class="stat-value">${stats.size_mb} МБ</div>
                        </div>
                        <div class="stat-card">
                            <h3>Количество таблиц</h3>
                            <div class="stat-value">${stats.tables_count}</div>
                        </div>
                    `;
                } else {
                    showAlert('Ошибка загрузки статистики: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Ошибка загрузки статистики: ' + error.message, 'danger');
            });
        }
        
        function loadTables() {
            const tablesContainer = document.getElementById('tablesContainer');
            tablesContainer.innerHTML = '<div class="loading">Загрузка таблиц...</div>';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_tables'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTables(data.tables);
                } else {
                    showAlert('Ошибка загрузки таблиц: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Ошибка загрузки таблиц: ' + error.message, 'danger');
            });
        }
        
        function renderTables(tables) {
            const tablesContainer = document.getElementById('tablesContainer');
            
            if (tables.length === 0) {
                tablesContainer.innerHTML = '<div class="alert alert-warning">Таблицы не найдены</div>';
                return;
            }
            
            let html = '';
            tables.forEach(table => {
                html += `
                    <div class="table-item">
                        <div class="table-header" onclick="toggleTableDetails('${table.name}')">
                            <span class="table-name">${table.name}</span>
                            <span class="table-count">${table.count} записей</span>
                        </div>
                        <div class="table-details" id="details-${table.name}">
                            <h4>Структура таблицы:</h4>
                            <table class="structure-table">
                                <thead>
                                    <tr>
                                        <th>Поле</th>
                                        <th>Тип</th>
                                        <th>Null</th>
                                        <th>Ключ</th>
                                        <th>По умолчанию</th>
                                        <th>Extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${table.structure.map(field => `
                                        <tr>
                                            <td><strong>${field.Field}</strong></td>
                                            <td>${field.Type}</td>
                                            <td>${field.Null}</td>
                                            <td>${field.Key}</td>
                                            <td>${field.Default || ''}</td>
                                            <td>${field.Extra}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                            <button class="btn btn-primary" onclick="loadTableData('${table.name}')">📋 Показать данные</button>
                            <div id="data-${table.name}"></div>
                        </div>
                    </div>
                `;
            });
            
            tablesContainer.innerHTML = html;
        }
        
        function toggleTableDetails(tableName) {
            const details = document.getElementById(`details-${tableName}`);
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }
        
        function loadTableData(tableName) {
            const dataContainer = document.getElementById(`data-${tableName}`);
            dataContainer.innerHTML = '<div class="loading">Загрузка данных...</div>';
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_table_data&table=${encodeURIComponent(tableName)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTableData(data.data, dataContainer);
                } else {
                    dataContainer.innerHTML = `<div class="alert alert-danger">Ошибка: ${data.message}</div>`;
                }
            })
            .catch(error => {
                dataContainer.innerHTML = `<div class="alert alert-danger">Ошибка: ${error.message}</div>`;
            });
        }
        
        function renderTableData(data, container) {
            if (data.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">Нет данных в таблице</div>';
                return;
            }
            
            const columns = Object.keys(data[0]);
            
            let html = `
                <h4>Данные таблицы (показано до 100 записей):</h4>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                ${columns.map(col => `<th>${col}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(row => `
                                <tr>
                                    ${columns.map(col => `<td title="${String(row[col]).replace(/"/g, '&quot;')}">${String(row[col]).substring(0, 50)}${String(row[col]).length > 50 ? '...' : ''}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = html;
        }
        
        function createBackup() {
            showAlert('Создание бэкапа...', 'warning');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=backup_database'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                } else {
                    showAlert('Ошибка создания бэкапа: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Ошибка создания бэкапа: ' + error.message, 'danger');
            });
        }
        
        function reinstallDatabase() {
            showAlert('Переустановка базы данных...', 'warning');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=reinstall_database'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    loadStats();
                    loadTables();
                } else {
                    showAlert('Ошибка переустановки: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Ошибка переустановки: ' + error.message, 'danger');
            });
        }
    </script>
</body>
</html>
