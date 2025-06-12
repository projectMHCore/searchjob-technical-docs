<?php
session_start();

// Простая диагностика API аватаров
echo "<!DOCTYPE html>";
echo "<html><head><title>Avatar API Diagnostics</title></head><body>";
echo "<h1>Avatar API Diagnostics</h1>";

echo "<h2>Session Info:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>User Data Check:</h2>";
if (isset($_SESSION['token'])) {
    require_once __DIR__ . '/../backend/models/User.php';
    $user = new User();
    $userData = $user->getUserByToken($_SESSION['token']);
    
    if ($userData) {
        echo "User found: " . htmlspecialchars($userData['login']) . " (ID: " . $userData['id'] . ")<br>";
        echo "<pre>";
        print_r($userData);
        echo "</pre>";
    } else {
        echo "No user found for token<br>";
    }
} else {
    echo "No token in session<br>";
}

echo "<h2>File Paths Check:</h2>";
$avatarApiPath = __DIR__ . '/../backend/api/avatar.php';
echo "Avatar API file exists: " . (file_exists($avatarApiPath) ? "YES" : "NO") . "<br>";
echo "Avatar API path: " . $avatarApiPath . "<br>";

$uploadsDir = __DIR__ . '/assets/uploads/avatars/';
echo "Uploads directory exists: " . (is_dir($uploadsDir) ? "YES" : "NO") . "<br>";
echo "Uploads directory writable: " . (is_writable($uploadsDir) ? "YES" : "NO") . "<br>";
echo "Uploads path: " . $uploadsDir . "<br>";

echo "<h2>Manual Test Links:</h2>";
if (isset($_SESSION['token'])) {
    echo '<a href="../backend/api/avatar.php" target="_blank">Test Avatar API (GET)</a><br>';
    echo '<a href="avatar_test.php">Full Avatar Test Page</a><br>';
} else {
    echo '<a href="login.php">Please Login First</a><br>';
}

echo "</body></html>";
?>
