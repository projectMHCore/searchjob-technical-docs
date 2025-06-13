<?php
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>User ID Debug</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; } .error { color: red; } .success { color: green; }</style>";
echo "</head><body>";
echo "<h1>🔍 User ID Debug Analysis</h1>";

// Проверяем сессию
echo "<div class='debug-section'>";
echo "<h2>1. Session Data</h2>";
if (empty($_SESSION)) {
    echo "<div class='error'>❌ Session is empty - user not logged in</div>";
    echo "<a href='login.php'>Go to Login</a>";
    echo "</div></body></html>";
    exit;
}

echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "</div>";

// Проверяем, есть ли токен
if (!isset($_SESSION['token'])) {
    echo "<div class='debug-section'>";
    echo "<div class='error'>❌ No token in session</div>";
    echo "</div></body></html>";
    exit;
}

// Подключаем модель User
require_once __DIR__ . '/../backend/models/User.php';
$user = new User();

echo "<div class='debug-section'>";
echo "<h2>2. User Data from Token</h2>";

$userData = $user->getUserByToken($_SESSION['token']);
if ($userData) {
    echo "<div class='success'>✅ User found by token</div>";
    echo "<pre>";
    print_r($userData);
    echo "</pre>";
    
    $tokenUserId = $userData['id'];
    $sessionUserId = $_SESSION['user_id'] ?? 'NOT SET';
    
    echo "<h3>ID Comparison:</h3>";
    echo "<strong>Token User ID:</strong> " . $tokenUserId . "<br>";
    echo "<strong>Session User ID:</strong> " . $sessionUserId . "<br>";
    
    if ($tokenUserId != $sessionUserId) {
        echo "<div class='error'>⚠️ ID MISMATCH DETECTED!</div>";
        echo "<p>The user ID from token ($tokenUserId) does not match session user ID ($sessionUserId)</p>";
    } else {
        echo "<div class='success'>✅ IDs match</div>";
    }
} else {
    echo "<div class='error'>❌ No user found for token</div>";
}
echo "</div>";

// Проверяем аватар
echo "<div class='debug-section'>";
echo "<h2>3. Avatar File Check</h2>";

if (isset($tokenUserId)) {
    $avatarPath = $user->getAvatarPath($tokenUserId);
    echo "<strong>Avatar path from DB:</strong> " . ($avatarPath ? $avatarPath : 'NULL') . "<br>";
    
    // Проверяем файлы аватаров в директории
    $uploadsDir = __DIR__ . '/../uploads/avatars/';
    if (is_dir($uploadsDir)) {
        $files = scandir($uploadsDir);
        $avatarFiles = array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });
        
        echo "<strong>Avatar files in uploads directory:</strong><br>";
        if (empty($avatarFiles)) {
            echo "No avatar files found<br>";
        } else {
            foreach ($avatarFiles as $file) {
                echo "- $file<br>";
                
                // Проверяем, содержит ли имя файла ID пользователя
                if (strpos($file, (string)$tokenUserId) !== false) {
                    echo "  ✅ Contains correct user ID ($tokenUserId)<br>";
                } elseif (strpos($file, (string)$sessionUserId) !== false) {
                    echo "  ⚠️ Contains session user ID ($sessionUserId)<br>";
                } else {
                    echo "  ❓ Unknown ID pattern<br>";
                }
            }
        }
    } else {
        echo "<div class='error'>Avatar uploads directory not found</div>";
    }
}
echo "</div>";

// Проверяем таблицу user_tokens
echo "<div class='debug-section'>";
echo "<h2>4. Token Table Check</h2>";

$token = $_SESSION['token'];

// Используем отдельное соединение с БД, так как $user->db приватное
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

if ($db->connect_error) {
    echo "<div class='error'>❌ Database connection error: " . $db->connect_error . "</div>";
    echo "</div>";
} else {
    $token_escaped = $db->real_escape_string($token);
    $result = $db->query("SELECT user_id, token, created_at FROM user_tokens WHERE token = '$token_escaped' LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $tokenData = $result->fetch_assoc();
        echo "<div class='success'>✅ Token found in database</div>";
        echo "<pre>";
        print_r($tokenData);
        echo "</pre>";
        
        $dbTokenUserId = $tokenData['user_id'];
        echo "<strong>User ID from token table:</strong> $dbTokenUserId<br>";
        
        if ($dbTokenUserId != $sessionUserId) {
            echo "<div class='error'>⚠️ Token table user ID ($dbTokenUserId) doesn't match session ($sessionUserId)</div>";
        }
    } else {
        echo "<div class='error'>❌ Token not found in database</div>";
    }
}
echo "</div>";

// Проверяем все токены пользователя
if (isset($sessionUserId) && is_numeric($sessionUserId) && isset($db)) {
    echo "<div class='debug-section'>";
    echo "<h2>5. All Tokens for Session User ID ($sessionUserId)</h2>";
    
    $sessionUserId_escaped = intval($sessionUserId);
    $result = $db->query("SELECT token, created_at FROM user_tokens WHERE user_id = $sessionUserId_escaped ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Token (first 16 chars)</th><th>Created At</th><th>Current?</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $isCurrentToken = ($row['token'] === $token) ? '✅ YES' : 'No';
            echo "<tr>";
            echo "<td>" . substr($row['token'], 0, 16) . "...</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $isCurrentToken . "</td>";
            echo "</tr>";
        }
        echo "</table>";    } else {
        echo "<div class='error'>No tokens found for user ID $sessionUserId</div>";
    }
    echo "</div>";
}

// Закрываем соединение с базой данных
if (isset($db)) {
    $db->close();
}

echo "<div class='debug-section'>";
echo "<h2>6. Summary & Recommendations</h2>";

if (isset($tokenUserId, $sessionUserId) && $tokenUserId != $sessionUserId) {
    echo "<div class='error'>";
    echo "<h3>🚨 PROBLEM IDENTIFIED:</h3>";
    echo "<p>There's a mismatch between:</p>";
    echo "<ul>";
    echo "<li>Session user_id: $sessionUserId</li>";
    echo "<li>Token-based user_id: $tokenUserId</li>";
    echo "</ul>";
    echo "<p><strong>Root cause:</strong> During login, the wrong user_id was stored in the session, but the correct user_id is associated with the token.</p>";
    echo "<p><strong>Impact:</strong> This can cause issues with avatar file naming and user identification.</p>";
    echo "<p><strong>Solution:</strong> Update session to use token-based user identification consistently.</p>";
    echo "</div>";
    
    echo "<h3>🔧 Proposed Fix:</h3>";
    echo "<p>1. Update LoginController to use token-based user ID</p>";
    echo "<p>2. Ensure all profile operations use getUserByToken() method</p>";
    echo "<p>3. Fix avatar file naming to use consistent user ID</p>";
} else {
    echo "<div class='success'>✅ No obvious ID mismatch detected</div>";
}
echo "</div>";

echo "</body></html>";
?>
