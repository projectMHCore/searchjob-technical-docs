<?php
/**
 * Скрипт проверки путей после реорганизации файлов
 */

echo "<h2>🔍 Проверка путей после реорганизации</h2>";
echo "<hr>";

$tests = [
    'Тест из frontend/utils/tests/' => [
        'location' => __DIR__,
        'paths' => [
            'backend/config/db.php' => '../../../backend/config/db.php',
            'backend/controllers/ApiController.php' => '../../../backend/controllers/ApiController.php',
            'backend/xml/' => '../../../backend/xml/',
            'frontend/models/UserModel.php' => '../../models/UserModel.php',
            'frontend/assets/' => '../../assets/'
        ]
    ]
];

foreach ($tests as $testName => $test) {
    echo "<h3>$testName</h3>";
    echo "<div style='margin-left: 20px;'>";
    
    foreach ($test['paths'] as $description => $path) {
        $fullPath = $test['location'] . '/' . $path;
        $exists = file_exists($fullPath) || is_dir($fullPath);
        
        $status = $exists ? '✅' : '❌';
        $color = $exists ? 'green' : 'red';
        
        echo "<div style='color: $color; margin: 5px 0;'>";
        echo "$status <strong>$description:</strong> $path";
        if (!$exists) {
            echo " <em>(Не найден: $fullPath)</em>";
        }
        echo "</div>";
    }
    
    echo "</div><br>";
}

echo "<h3>🗂️ Проверка ключевых файлов</h3>";

$keyFiles = [
    'backend/config/db.php' => __DIR__ . '/../../../backend/config/db.php',
    'backend/models/User.php' => __DIR__ . '/../../../backend/models/User.php',
    'frontend/models/UserModel.php' => __DIR__ . '/../../models/UserModel.php',
    'frontend/utils/admin/manage_users.php' => __DIR__ . '/../admin/manage_users.php'
];

foreach ($keyFiles as $name => $path) {
    $exists = file_exists($path);
    $status = $exists ? '✅' : '❌';
    $color = $exists ? 'green' : 'red';
    
    echo "<div style='color: $color; margin: 5px 0;'>";
    echo "$status <strong>$name:</strong> ";
    if ($exists) {
        echo "Найден (" . realpath($path) . ")";
    } else {
        echo "НЕ НАЙДЕН ($path)";
    }
    echo "</div>";
}

echo "<hr>";
echo "<p><em>Этот файл можно удалить после проверки всех путей.</em></p>";
?>
