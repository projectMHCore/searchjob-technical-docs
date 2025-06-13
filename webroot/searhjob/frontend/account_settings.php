<?php
// Страница настроек аккаунта пользователя
session_start();

require_once __DIR__ . '/../backend/config/db.php';
require_once __DIR__ . '/../backend/models/User.php';

// Проверяем авторизацию
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$userModel = new User();
$userData = $userModel->getUserByToken($_SESSION['token']);

if (!$userData) {
    header('Location: login.php');
    exit;
}

$message = '';
$success = false;
$error = '';

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'change_email') {
        $newEmail = trim($_POST['new_email'] ?? '');
        $password = $_POST['current_password'] ?? '';
        
        if (!$newEmail || !$password) {
            $error = 'Заповніть всі поля!';
        } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $error = 'Некоректний формат email!';
        } else {
            // Проверяем текущий пароль
            $result = $userModel->login($userData['login'], $password);
            if ($result['success']) {
                // Проверяем, что email не занят
                $config = require __DIR__ . '/../backend/config/db.php';
                $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
                
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->bind_param("si", $newEmail, $userData['id']);
                $stmt->execute();
                $existingUser = $stmt->get_result();
                
                if ($existingUser->num_rows > 0) {
                    $error = 'Цей email вже використовується!';
                } else {
                    // Обновляем email
                    $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $stmt->bind_param("si", $newEmail, $userData['id']);
                    
                    if ($stmt->execute()) {
                        $success = true;
                        $message = 'Email успішно змінено!';
                        $userData['email'] = $newEmail; // Обновляем в текущих данных
                    } else {
                        $error = 'Помилка оновлення email!';
                    }
                }
                $db->close();
            } else {
                $error = 'Невірний поточний пароль!';
            }
        }
    } elseif ($action === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $error = 'Заповніть всі поля!';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Нові паролі не збігаються!';
        } elseif (strlen($newPassword) < 6) {
            $error = 'Пароль повинен містити мінімум 6 символів!';
        } else {
            // Проверяем текущий пароль
            $result = $userModel->login($userData['login'], $currentPassword);
            if ($result['success']) {
                // Обновляем пароль
                $config = require __DIR__ . '/../backend/config/db.php';
                $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
                
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashedPassword, $userData['id']);
                
                if ($stmt->execute()) {
                    $success = true;
                    $message = 'Пароль успішно змінено!';
                } else {
                    $error = 'Помилка оновлення пароля!';
                }
                $db->close();
            } else {
                $error = 'Невірний поточний пароль!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Налаштування акаунту - SearchJob</title>
    <meta name="description" content="Налаштування безпеки акаунту SearchJob">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Base styles and theme variables */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #e2e8f0;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --text-light: #94a3b8;
            --border-color: #e1e8ed;
            --shadow: rgba(0,0,0,0.1);
            --primary-color: #eaa850;
            --primary-dark: #d4922a;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --bg-tertiary: #4a5568;
            --text-primary: #ffffff;
            --text-secondary: #cbd5e0;
            --text-light: #a0aec0;
            --border-color: #4a5568;
            --shadow: rgba(0,0,0,0.3);
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: scale(1.1);
        }

        /* Main content */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .page-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }

        /* Settings Cards */
        .settings-section {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .settings-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .settings-section h3 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .settings-section h3 i {
            color: var(--primary-color);
            font-size: 1.75rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }

        .btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(234, 168, 80, 0.4);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            box-shadow: none;
        }

        .btn-secondary:hover {
            background: var(--border-color);
            transform: translateY(-1px);
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .current-info {
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .current-info-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .current-info-value {
            font-size: 1.1rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(-5px);
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .container {
                padding: 0 1rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .settings-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/frontend/index.php" class="nav-brand">
                <i class="fas fa-briefcase"></i>
                SearchJob
            </a>
            
            <div class="nav-menu">
                <a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Головна
                </a>
                <a href="/frontend/vacancy_list.php" class="nav-link">
                    <i class="fas fa-search"></i>
                    Вакансії
                </a>
                <a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i>
                    Компанії
                </a>
                <a href="/frontend/profile.php" class="nav-link">
                    <i class="fas fa-user"></i>
                    Профіль
                </a>
                <a href="/frontend/logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Вийти
                </a>
            </div>
            
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <a href="/frontend/profile.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Назад до профілю
            </a>

            <div class="page-header">
                <h1>
                    <i class="fas fa-shield-alt"></i>
                    Налаштування акаунту
                </h1>
                <p class="subtitle">Керуйте безпекою вашого акаунту</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Email Settings -->
            <div class="settings-section">
                <h3>
                    <i class="fas fa-envelope"></i>
                    Змінити Email
                </h3>
                
                <div class="current-info">
                    <div class="current-info-label">Поточний email:</div>
                    <div class="current-info-value"><?= htmlspecialchars($userData['email']) ?></div>
                </div>

                <form method="POST">
                    <input type="hidden" name="action" value="change_email">
                    
                    <div class="form-group">
                        <label for="new_email" class="form-label">Новий email:</label>
                        <input type="email" id="new_email" name="new_email" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_password_email" class="form-label">Поточний пароль для підтвердження:</label>
                        <input type="password" id="current_password_email" name="current_password" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i>
                        Змінити email
                    </button>
                </form>
            </div>

            <!-- Password Settings -->
            <div class="settings-section">
                <h3>
                    <i class="fas fa-lock"></i>
                    Змінити пароль
                </h3>
                
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Поточний пароль:</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label">Новий пароль:</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" minlength="6" required>
                        <small style="color: var(--text-secondary); font-size: 0.875rem;">Мінімум 6 символів</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Підтвердіть новий пароль:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" minlength="6" required>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-key"></i>
                        Змінити пароль
                    </button>
                </form>
            </div>

            <!-- Account Info -->
            <div class="settings-section">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    Інформація про акаунт
                </h3>
                
                <div class="current-info">
                    <div class="current-info-label">Логін:</div>
                    <div class="current-info-value"><?= htmlspecialchars($userData['login']) ?></div>
                </div>
                
                <div class="current-info">
                    <div class="current-info-label">Роль:</div>
                    <div class="current-info-value">
                        <?= $userData['role'] === 'employer' ? 'Роботодавець' : 'Шукач роботи' ?>
                    </div>
                </div>
                
                <div class="current-info">
                    <div class="current-info-label">Дата реєстрації:</div>
                    <div class="current-info-value">
                        <?= date('d.m.Y H:i', strtotime($userData['created_at'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Theme Toggle
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const themeIcon = document.querySelector('.theme-toggle i');
            themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
            
            const themeIcon = document.querySelector('.theme-toggle i');
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            // Password confirmation validation
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePassword() {
                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Паролі не збігаються');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
            
            if (newPassword && confirmPassword) {
                newPassword.addEventListener('change', validatePassword);
                confirmPassword.addEventListener('keyup', validatePassword);
            }
        });
    </script>
</body>
</html>
