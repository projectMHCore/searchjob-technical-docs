<?php
/**
 * ГЛОБАЛЬНЫЙ СИСТЕМНЫЙ ТЕСТ SearchJob
 * Комплексная проверка всех компонентов системы
 * Создано: 5 июня 2025 г.
 */

// Запускаем сессию в самом начале, до вывода контента
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Настройки для безопасности (отключаем отображение ошибок для продакшена)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Отключаем для безопасности
ini_set('expose_php', 0); // Скрываем версию PHP
ini_set('max_execution_time', 300); // 5 минут

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Глобальный системный тест SearchJob</title>
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
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 300;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .content {
            padding: 30px;
        }
        
        .test-section {
            margin-bottom: 40px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .test-header {
            background: #f8fafc;
            padding: 20px;
            border-bottom: 1px solid #e1e8ed;
        }
        
        .test-header h2 {
            margin: 0;
            color: #2d3748;
            font-size: 1.5rem;
        }
        
        .test-body {
            padding: 25px;
        }
        
        .status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 10px;
        }
        
        .status.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .status.error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .status.warning {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        
        .status.info {
            background: #dbeafe;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .info-card h4 {
            margin: 0 0 15px 0;
            color: #374151;
        }
        
        .code-block {
            background: #1a202c;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            margin: 15px 0;
        }
        
        .table-container {
            overflow-x: auto;
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background: #f1f5f9;
            font-weight: 600;
            color: #374151;
        }
        
        .metric {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .metric:last-child {
            border-bottom: none;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 0.3s ease;
        }
        
        .timestamp {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 20px;
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Глобальный системный тест</h1>
            <p>SearchJob Application - Комплексная диагностика системы</p>
        </div>
        
        <div class="content">
            <?php
            $startTime = microtime(true);
            $totalTests = 0;
            $passedTests = 0;
            $errors = [];
            
            // Функция для вывода статуса
            function displayStatus($condition, $successMsg, $errorMsg) {
                global $totalTests, $passedTests, $errors;
                $totalTests++;
                
                if ($condition) {
                    $passedTests++;
                    echo '<span class="status success">✅ ' . $successMsg . '</span>';
                    return true;
                } else {
                    $errors[] = $errorMsg;
                    echo '<span class="status error">❌ ' . $errorMsg . '</span>';
                    return false;
                }
            }
            ?>
            
            <!-- 1. PHP Environment Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>🐘 PHP Environment</h2>
                </div>
                <div class="test-body">
                    <?php
                    displayStatus(version_compare(PHP_VERSION, '7.4.0', '>='), 
                        'PHP версия: ' . PHP_VERSION, 
                        'PHP версия устарела: ' . PHP_VERSION);
                    
                    echo '<br>';
                    displayStatus(extension_loaded('mysqli'), 
                        'MySQL расширение загружено', 
                        'MySQL расширение отсутствует');
                    
                    echo '<br>';
                    displayStatus(extension_loaded('curl'), 
                        'CURL расширение загружено', 
                        'CURL расширение отсутствует');
                    
                    echo '<br>';
                    displayStatus(extension_loaded('xml'), 
                        'XML расширение загружено', 
                        'XML расширение отсутствует');
                    ?>
                    
                    <div class="info-grid">
                        <div class="info-card">
                            <h4>📊 PHP Конфигурация</h4>
                            <div class="metric">
                                <span>Версия PHP:</span>
                                <strong><?= PHP_VERSION ?></strong>
                            </div>
                            <div class="metric">
                                <span>Memory Limit:</span>
                                <strong><?= ini_get('memory_limit') ?></strong>
                            </div>
                            <div class="metric">
                                <span>Max Execution Time:</span>
                                <strong><?= ini_get('max_execution_time') ?>s</strong>
                            </div>
                            <div class="metric">
                                <span>Upload Max Size:</span>
                                <strong><?= ini_get('upload_max_filesize') ?></strong>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <h4>🔧 Загруженные расширения</h4>
                            <?php
                            $required_extensions = ['mysqli', 'curl', 'xml', 'json', 'session'];
                            foreach ($required_extensions as $ext) {
                                $loaded = extension_loaded($ext);
                                echo '<div class="metric">';
                                echo '<span>' . $ext . ':</span>';
                                echo '<span class="status ' . ($loaded ? 'success' : 'error') . '">';
                                echo $loaded ? '✅' : '❌';
                                echo '</span>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
              <!-- 2. Database Connection Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>🗄️ Database Connection</h2>
                </div>
                <div class="test-body">
                    <?php                    try {
                        $config = require __DIR__ . '/../../../backend/config/db.php';
                        
                        $dbConnected = false;
                        $dbError = '';
                        $dbInfo = [];
                        $conn = null;
                        
                        // Создаем подключение к базе данных
                        $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
                        
                        if ($conn->connect_error) {
                            $dbError = $conn->connect_error;
                        } else {
                            $dbConnected = true;
                            
                            // Получаем информацию о базе данных
                                $result = $conn->query("SELECT VERSION() as version");
                                if ($result && $row = $result->fetch_assoc()) {
                                    $dbInfo['version'] = $row['version'];
                                }
                                
                                $result = $conn->query("SELECT DATABASE() as db_name");
                                if ($result && $row = $result->fetch_assoc()) {
                                    $dbInfo['database'] = $row['db_name'];
                                }
                                  $dbInfo['host'] = $conn->host_info;
                                $dbInfo['charset'] = $conn->character_set_name();
                        }
                        
                        displayStatus($dbConnected, 
                            'Подключение к базе данных успешно', 
                            'Ошибка подключения к БД: ' . $dbError);
                        
                        if ($dbConnected) {
                            echo '<div class="info-grid">';
                            echo '<div class="info-card">';
                            echo '<h4>📈 Информация о базе данных</h4>';
                            foreach ($dbInfo as $key => $value) {
                                echo '<div class="metric">';
                                echo '<span>' . ucfirst($key) . ':</span>';
                                echo '<strong>' . htmlspecialchars($value) . '</strong>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                        
                    } catch (Exception $e) {
                        displayStatus(false, '', 'Ошибка подключения к БД: ' . $e->getMessage());
                    }
                    ?>
                </div>
            </div>
            
            <!-- 3. Database Tables Test -->
            <div class="test-section">                <div class="test-header">
                    <h2>📋 Database Tables</h2>
                </div>
                <div class="test-body">
                    <?php
                    if (isset($conn) && $dbConnected) {
                        $expectedTables = ['users', 'vacancies', 'applications'];
                        $existingTables = [];
                        
                        $result = $conn->query("SHOW TABLES");
                        if ($result) {
                            while ($row = $result->fetch_array()) {
                                $existingTables[] = $row[0];
                            }
                        }
                        
                        echo '<div class="table-container">';
                        echo '<table>';
                        echo '<thead><tr><th>Таблица</th><th>Статус</th><th>Записей</th><th>Действия</th></tr></thead>';
                        echo '<tbody>';
                        
                        foreach ($expectedTables as $table) {
                            $exists = in_array($table, $existingTables);
                            $count = 0;
                            
                            if ($exists) {
                                $countResult = $conn->query("SELECT COUNT(*) as count FROM `$table`");
                                if ($countResult && $row = $countResult->fetch_assoc()) {
                                    $count = $row['count'];
                                }
                            }
                            
                            echo '<tr>';
                            echo '<td><strong>' . $table . '</strong></td>';
                            echo '<td>';
                            if ($exists) {
                                echo '<span class="status success">✅ Существует</span>';
                            } else {
                                echo '<span class="status error">❌ Отсутствует</span>';
                            }
                            echo '</td>';
                            echo '<td>' . ($exists ? number_format($count) : 'N/A') . '</td>';
                            echo '<td>';
                            if ($exists) {
                                echo '<span class="status info">👀 Просмотр</span>';
                            } else {
                                echo '<span class="status warning">🔧 Создать</span>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        
                        $allTablesExist = count(array_intersect($expectedTables, $existingTables)) === count($expectedTables);
                        displayStatus($allTablesExist, 
                            'Все необходимые таблицы присутствуют', 
                            'Отсутствуют некоторые таблицы БД');
                    } else {
                        echo '<span class="status error">❌ Невозможно проверить таблицы - нет подключения к БД</span>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- 4. API Endpoints Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>🔗 API Endpoints</h2>
                </div>
                <div class="test-body">
                    <?php                    $apiEndpoints = [
                        'ApiController.php' => '../../../backend/controllers/ApiController.php',
                        'XmlApiController.php' => '../../../backend/controllers/XmlApiController.php',
                        'VacancyApiController.php' => '../../../backend/controllers/VacancyApiController.php',
                        'ProfileController.php' => '../../../backend/controllers/ProfileController.php'
                    ];
                    
                    echo '<div class="table-container">';
                    echo '<table>';
                    echo '<thead><tr><th>API Контроллер</th><th>Статус файла</th><th>Размер</th><th>Последнее изменение</th></tr></thead>';
                    echo '<tbody>';
                    
                    foreach ($apiEndpoints as $name => $path) {
                        $fullPath = __DIR__ . '/' . $path;
                        $exists = file_exists($fullPath);
                        
                        echo '<tr>';
                        echo '<td><strong>' . $name . '</strong></td>';
                        echo '<td>';
                        if ($exists) {
                            echo '<span class="status success">✅ Доступен</span>';
                            displayStatus(true, '', '');
                        } else {
                            echo '<span class="status error">❌ Отсутствует</span>';
                            displayStatus(false, '', 'API файл не найден: ' . $name);
                        }
                        echo '</td>';
                        echo '<td>' . ($exists ? number_format(filesize($fullPath)) . ' байт' : 'N/A') . '</td>';
                        echo '<td>' . ($exists ? date('Y-m-d H:i:s', filemtime($fullPath)) : 'N/A') . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    ?>
                </div>
            </div>
            
            <!-- 5. File System Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>📁 File System</h2>
                </div>
                <div class="test-body">
                    <?php                    $directories = [
                        'backend/logs' => '../../../backend/logs',
                        'backend/xml' => '../../../backend/xml',
                        'backend/data' => '../../../backend/data',
                        'frontend/assets' => '../../assets'
                    ];
                    
                    foreach ($directories as $name => $path) {
                        $fullPath = __DIR__ . '/' . $path;
                        $exists = is_dir($fullPath);
                        $writable = $exists ? is_writable($fullPath) : false;
                        
                        echo '<div style="margin: 10px 0;">';
                        echo '<strong>' . $name . ':</strong> ';
                        
                        if ($exists && $writable) {
                            displayStatus(true, 'Существует и доступна для записи', '');
                        } elseif ($exists) {
                            displayStatus(false, '', 'Директория существует, но не доступна для записи: ' . $name);
                        } else {
                            displayStatus(false, '', 'Директория не существует: ' . $name);
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- 6. XML API Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>🗂️ XML API</h2>
                </div>
                <div class="test-body">
                    <?php
                    $xmlApiFile = __DIR__ . '/../../../backend/controllers/XmlApiController.php';
                    $xmlExists = file_exists($xmlApiFile);
                    
                    displayStatus($xmlExists, 
                        'XML API контроллер найден', 
                        'XML API контроллер отсутствует');
                    
                    if ($xmlExists) {                        echo '<br>';
                        $xmlDir = __DIR__ . '/../../../backend/xml';
                        $xmlDirExists = is_dir($xmlDir);
                        
                        displayStatus($xmlDirExists, 
                            'XML директория существует', 
                            'XML директория отсутствует');
                          if ($xmlDirExists) {
                            $xmlFiles = glob($xmlDir . '/*.xml');
                            echo '<div class="info-card" style="margin-top: 20px;">';
                            echo '<h4>📄 XML файлы в системе</h4>';
                            echo '<div class="metric">';
                            echo '<span>Количество XML файлов:</span>';
                            echo '<strong>' . count($xmlFiles) . '</strong>';
                            echo '</div>';
                            if (count($xmlFiles) > 0) {
                                echo '<div class="code-block">';
                                foreach (array_slice($xmlFiles, 0, 5) as $file) {
                                    echo basename($file) . ' (' . number_format(filesize($file)) . ' байт)<br>';
                                }
                                if (count($xmlFiles) > 5) {
                                    echo '... и еще ' . (count($xmlFiles) - 5) . ' файлов';
                                }
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <!-- 7. Security Test -->
            <div class="test-section">
                <div class="test-header">
                    <h2>🔒 Security</h2>
                </div>
                <div class="test-body">
                    <?php                    // Проверка настроек безопасности
                    $securityChecks = [
                        'display_errors' => ['off', 'Отображение ошибок отключено', 'Отображение ошибок включено'],
                        'allow_url_include' => ['off', 'URL include отключен', 'URL include включен (небезопасно)']
                    ];
                      foreach ($securityChecks as $setting => $check) {
                        $value = ini_get($setting);
                        $secure = ($value == $check[0] || $value == '' || $value == '0');
                        
                        displayStatus($secure, $check[1], $check[2]);
                        echo '<br>';
                    }
                    
                    // Проверка сессий
                    displayStatus(session_status() === PHP_SESSION_ACTIVE, 
                        'Сессии работают корректно', 
                        'Проблемы с сессиями');
                    ?>
                </div>
            </div>            
            <!-- Summary -->
            <div class="test-section">
                <div class="test-header">
                    <h2>📊 Итоговый результат</h2>
                </div>
                <div class="test-body">
                    <?php
                    $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100) : 0;
                    ?>
                    
                    <div class="info-grid">
                        <div class="info-card">
                            <h4>📈 Статистика тестов</h4>
                            <div class="metric">
                                <span>Всего тестов:</span>
                                <strong><?= $totalTests ?></strong>
                            </div>
                            <div class="metric">
                                <span>Пройдено:</span>
                                <strong style="color: #059669;"><?= $passedTests ?></strong>
                            </div>
                            <div class="metric">
                                <span>Не пройдено:</span>
                                <strong style="color: #dc2626;"><?= $totalTests - $passedTests ?></strong>
                            </div>
                            <div class="metric">
                                <span>Успешность:</span>
                                <strong><?= $successRate ?>%</strong>
                            </div>
                            
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $successRate ?>%;"></div>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <h4>🎯 Общая оценка</h4>
                            <?php
                            if ($successRate >= 90) {
                                echo '<span class="status success">🏆 Отлично</span>';
                                echo '<p>Система работает стабильно, все ключевые компоненты функционируют корректно.</p>';
                            } elseif ($successRate >= 70) {
                                echo '<span class="status warning">⚠️ Хорошо</span>';
                                echo '<p>Система работает, но есть несколько проблем, которые стоит исправить.</p>';
                            } else {
                                echo '<span class="status error">🚨 Требует внимания</span>';
                                echo '<p>Обнаружены критические проблемы, система требует диагностики.</p>';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($errors)): ?>
                    <div style="margin-top: 30px;">
                        <h4>🚨 Обнаруженные проблемы:</h4>
                        <div class="code-block" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                            <?php foreach ($errors as $error): ?>
                                • <?= htmlspecialchars($error) ?><br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="timestamp">
                <strong>Тест выполнен:</strong> <?= date('Y-m-d H:i:s') ?><br>
                <strong>Время выполнения:</strong> <?= round(microtime(true) - $startTime, 3) ?> секунд<br>
                <strong>Сервер:</strong> <?= $_SERVER['SERVER_NAME'] ?? 'localhost' ?> | 
                <strong>PHP:</strong> <?= PHP_VERSION ?>
            </div>
        </div>
    </div>

    <script>
        // Автообновление каждые 30 секунд (опционально)
        // setInterval(() => location.reload(), 30000);
        
        // Подсветка при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.test-section');
            sections.forEach((section, index) => {
                setTimeout(() => {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(20px)';
                    section.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });
        });
    </script>
</body>
</html>
