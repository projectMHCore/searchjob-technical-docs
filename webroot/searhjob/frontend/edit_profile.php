<?php
// Страница редактирования профиля пользователя
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

// Определяем тип редактирования
$editType = $_GET['type'] ?? 'full'; // full, personal, company
$message = '';
$success = false;

// Получаем текущие данные профиля из базы данных
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}

// Получаем текущие данные пользователя
$userId = intval($userData['id']);
$result = $db->query("SELECT * FROM users WHERE id = $userId");
$currentData = $result->fetch_assoc();

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updates = [];
        $params = [];
        $types = '';
        
        // Общие поля
        if (!empty($_POST['first_name'])) {
            $updates[] = "first_name = ?";
            $params[] = trim($_POST['first_name']);
            $types .= 's';
        }
        
        if (!empty($_POST['last_name'])) {
            $updates[] = "last_name = ?";
            $params[] = trim($_POST['last_name']);
            $types .= 's';
        }
        
        if (!empty($_POST['phone'])) {
            $updates[] = "phone = ?";
            $params[] = trim($_POST['phone']);
            $types .= 's';
        }
        
        if (!empty($_POST['birth_date'])) {
            $updates[] = "birth_date = ?";
            $params[] = $_POST['birth_date'];
            $types .= 's';
        }
        
        if (!empty($_POST['city'])) {
            $updates[] = "city = ?";
            $params[] = trim($_POST['city']);
            $types .= 's';
        }
        
        // Поля для соискателей
        if ($userData['role'] === 'job_seeker') {
            if (isset($_POST['experience_years']) && $_POST['experience_years'] !== '') {
                $updates[] = "experience_years = ?";
                $params[] = intval($_POST['experience_years']);
                $types .= 'i';
            }
            
            if (!empty($_POST['education'])) {
                $updates[] = "education = ?";
                $params[] = trim($_POST['education']);
                $types .= 's';
            }
            
            if (!empty($_POST['skills'])) {
                $updates[] = "skills = ?";
                $params[] = trim($_POST['skills']);
                $types .= 's';
            }
            
            if (!empty($_POST['about_me'])) {
                $updates[] = "about_me = ?";
                $params[] = trim($_POST['about_me']);
                $types .= 's';
            }
            
            if (isset($_POST['salary_expectation']) && $_POST['salary_expectation'] !== '') {
                $updates[] = "salary_expectation = ?";
                $params[] = floatval($_POST['salary_expectation']);
                $types .= 'd';
            }
        }
        
        // Поля для работодателей
        if ($userData['role'] === 'employer') {
            if (!empty($_POST['company_name'])) {
                $updates[] = "company_name = ?";
                $params[] = trim($_POST['company_name']);
                $types .= 's';
            }
            
            if (!empty($_POST['company_description'])) {
                $updates[] = "company_description = ?";
                $params[] = trim($_POST['company_description']);
                $types .= 's';
            }
            
            if (!empty($_POST['company_address'])) {
                $updates[] = "company_address = ?";
                $params[] = trim($_POST['company_address']);
                $types .= 's';
            }
            
            if (!empty($_POST['company_website'])) {
                $updates[] = "company_website = ?";
                $params[] = trim($_POST['company_website']);
                $types .= 's';
            }
            
            if (!empty($_POST['company_size'])) {
                $updates[] = "company_size = ?";
                $params[] = trim($_POST['company_size']);
                $types .= 's';
            }
            
            if (!empty($_POST['company_industry'])) {
                $updates[] = "company_industry = ?";
                $params[] = trim($_POST['company_industry']);
                $types .= 's';
            }
        }
        
        if (!empty($updates)) {
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            $params[] = $userId;
            $types .= 'i';
            
            $stmt = $db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                if ($stmt->execute()) {
                    $success = true;
                    $message = 'Профіль успішно оновлено!';
                    
                    // Оновлюємо дані для відображення
                    $result = $db->query("SELECT * FROM users WHERE id = $userId");
                    $currentData = $result->fetch_assoc();
                } else {
                    $message = 'Помилка при оновленні профілю: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = 'Помилка підготовки запиту: ' . $db->error;
            }
        } else {
            $message = 'Немає даних для оновлення';
        }
    } catch (Exception $e) {
        $message = 'Помилка: ' . $e->getMessage();
    }
}

$db->close();
?>
<!DOCTYPE html>
<html lang="uk" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування профілю - SearchJob</title>
    <meta name="description" content="Редагуйте свій профіль на SearchJob">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">      <style>        /* Base styles and theme variables */
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
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            overflow-x: hidden;
            position: relative;
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #eaa850;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: #eaa850;
            background: rgba(234, 168, 80, 0.1);
            transform: translateY(-2px);
        }

        [data-theme="dark"] .nav-link {
            color: var(--text-primary);
        }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: none;
            border: 2px solid #e1e8ed;
            color: #2c3e50;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: scale(1.1);
        }

        [data-theme="dark"] .theme-toggle {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, #eaa850, #d4922a);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(234, 168, 80, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #2c3e50;
            border: 2px solid #e1e8ed;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: translateY(-2px);
        }

        [data-theme="dark"] .btn-secondary {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        /* Main content */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .section-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Footer */
        .footer {
            background: #1a202c;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #eaa850;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #eaa850;
        }

        .footer-bottom {
            border-top: 1px solid #2d3748;
            padding-top: 2rem;
            text-align: center;
            color: #a0aec0;
        }

        /* Page-specific styles for profile editing */
        .edit-profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .edit-type-selector {
            background: var(--background-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-section {
            background: var(--background-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }
        
        .form-section h3 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-orange);
        }
        
        /* Form styles override */
        .form-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .form-grid.two-columns {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        
        .form-group label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .edit-profile-container {
                padding: 1rem;
            }
            
            .page-header {
                padding: 2rem 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .form-grid.two-columns {
                grid-template-columns: 1fr;
            }        }
        
        /* Исправление стилей для корректного отображения */
        .navbar {
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        [data-theme="dark"] .navbar {
            background: rgba(26, 26, 26, 0.95);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), #f39c12);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        /* Main Content */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), #f39c12);
            color: white;
            border-radius: var(--border-radius-lg);
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
            margin-bottom: 0.5rem;
            position: relative;
        }

        .page-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }        /* Form Styles */
        .edit-form {
            max-width: 100%;
            margin: 0 auto;
        }

        .form-section {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: var(--transition);
        }

        .form-section:hover::before {
            transform: scaleY(1);
        }

        .form-section h3 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            font-family: inherit;
            background: var(--background-color);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .help-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-family: inherit;
            white-space: nowrap;
            text-align: center;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #f39c12);
            color: white;
            box-shadow: 0 2px 8px rgba(234, 168, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(234, 168, 80, 0.4);
        }

        .btn-secondary {
            background: var(--surface-color);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--border-color);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        /* Alerts */
        .message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .message.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .form-actions {
            text-align: center;
            padding: 2rem 0;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .navigation-links {
            margin-top: 2rem;
            text-align: center;
            padding: 1.5rem;
            background: var(--surface-color);
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
        }

        .navigation-links h4 {
            margin-bottom: 1rem;
            color: var(--text-primary);
            font-size: 1rem;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-primary);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 1rem;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--surface-color);
                border-top: 1px solid var(--border-color);
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .nav-menu.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .main-content {
                padding: 1rem;
            }

            .page-header {
                padding: 2rem 1rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .form-section {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-section:nth-child(even) {
            animation-delay: 0.1s;
        }

        .form-section:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>    <!-- Navigation -->
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
                <?php if ($userData['role'] === 'employer'): ?>
                    <a href="/frontend/my_vacancies.php" class="nav-link">
                        <i class="fas fa-list"></i>
                        Мої вакансії
                    </a>
                <?php elseif ($userData['role'] === 'jobseeker'): ?>
                    <a href="/frontend/my_applications.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        Мої заявки
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="nav-auth">
                <button id="theme-toggle" class="theme-toggle" title="Переключити тему">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="/frontend/profile.php" class="nav-link">
                    <i class="fas fa-user"></i>
                    Профіль
                </a>
                <a href="/frontend/logout.php" class="btn-secondary">Вихід</a>
            </div>
        </div>
    </nav>    <!-- Main Content -->
    <main class="main-content">
        <div class="section-container">
            <!-- Page Header -->
            <div class="page-header">
                <?php
                $pageTitle = '✏️ Редагування профілю';
                $subtitle = 'Оновіть інформацію';
                if ($editType === 'personal') {
                    $pageTitle = '👤 Особиста інформація';
                    $subtitle = 'Редагування персональних даних';
                } elseif ($editType === 'company') {
                    $pageTitle = '🏢 Інформація про компанію';
                    $subtitle = 'Редагування даних компанії';
                }
                ?>
                <h1><?= $pageTitle ?></h1>
                <p class="subtitle"><?= $subtitle ?></p>
            </div>
            
            <!-- Alert Messages -->
            <?php if ($message): ?>
                <div class="message <?= $success ? 'success' : 'error' ?>">
                    <?= $success ? '✅' : '❌' ?> <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>            <!-- Edit Form -->
            <form method="post" class="edit-form">
                <?php if ($editType === 'full' || $editType === 'personal'): ?>
                <!-- Avatar Upload Section -->
                <div class="form-section">
                    <h3>📷 Фото профілю</h3>
                    <?php include __DIR__ . '/components/avatar_upload.php'; ?>
                </div>
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>👤 Особиста інформація</h3>
                    
                    <div class="form-group">
                        <label for="first_name">Ім'я</label>
                        <input type="text" id="first_name" name="first_name" 
                               value="<?= htmlspecialchars($currentData['first_name'] ?? '') ?>"
                               placeholder="Введіть ваше ім'я">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Прізвище</label>
                        <input type="text" id="last_name" name="last_name" 
                               value="<?= htmlspecialchars($currentData['last_name'] ?? '') ?>"
                               placeholder="Введіть ваше прізвище">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($currentData['phone'] ?? '') ?>"
                               placeholder="+38 (067) 123-45-67">
                        <div class="help-text">Формат: +38 (067) 123-45-67</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_date">Дата народження</label>
                        <input type="date" id="birth_date" name="birth_date" 
                               value="<?= htmlspecialchars($currentData['birth_date'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="city">Місто</label>
                        <input type="text" id="city" name="city" 
                               value="<?= htmlspecialchars($currentData['city'] ?? '') ?>"
                               placeholder="Наприклад: Полтава">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (($editType === 'full' || $editType === 'personal') && $userData['role'] === 'job_seeker'): ?>
                <!-- Professional Information Section -->
                <div class="form-section">
                    <h3>💼 Професійна інформація</h3>
                    
                    <div class="form-group">
                        <label for="experience_years">Досвід роботи (роки)</label>
                        <input type="number" id="experience_years" name="experience_years" 
                               value="<?= htmlspecialchars($currentData['experience_years'] ?? '') ?>"
                               min="0" max="50" placeholder="Наприклад: 3">
                    </div>
                    
                    <div class="form-group">
                        <label for="education">Освіта</label>
                        <textarea id="education" name="education" 
                                  placeholder="Вкажіть вашу освіту, навчальні заклади, спеціальність..."><?= htmlspecialchars($currentData['education'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="skills">Навички та компетенції</label>
                        <textarea id="skills" name="skills" 
                                  placeholder="Перелічте ваші професійні навички, технології, мови програмування..."><?= htmlspecialchars($currentData['skills'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="about_me">Про себе</label>
                        <textarea id="about_me" name="about_me" 
                                  placeholder="Розкажіть про себе, свої досягнення, цілі..."><?= htmlspecialchars($currentData['about_me'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="salary_expectation">Бажана зарплата (грн.)</label>
                        <input type="number" id="salary_expectation" name="salary_expectation" 
                               value="<?= htmlspecialchars($currentData['salary_expectation'] ?? '') ?>"
                               min="0" step="1000" placeholder="Наприклад: 25000">
                        <div class="help-text">Вказуйте суму в гривнях (UAH)</div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (($editType === 'full' || $editType === 'company') && $userData['role'] === 'employer'): ?>
                <!-- Company Information Section -->
                <div class="form-section">
                    <h3>🏢 Інформація про компанію</h3>
                    
                    <div class="form-group">
                        <label for="company_name">Назва компанії</label>
                        <input type="text" id="company_name" name="company_name" 
                               value="<?= htmlspecialchars($currentData['company_name'] ?? '') ?>"
                               placeholder="Введіть назву компанії">
                    </div>
                    
                    <div class="form-group">
                        <label for="company_description">Опис компанії</label>
                        <textarea id="company_description" name="company_description" 
                                  placeholder="Розкажіть про діяльність компанії, місію, цінності..."><?= htmlspecialchars($currentData['company_description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_address">Адреса компанії</label>
                        <input type="text" id="company_address" name="company_address" 
                               value="<?= htmlspecialchars($currentData['company_address'] ?? '') ?>"
                               placeholder="Місто, вулиця, будинок">
                    </div>
                    
                    <div class="form-group">
                        <label for="company_website">Веб-сайт компанії</label>
                        <input type="url" id="company_website" name="company_website" 
                               value="<?= htmlspecialchars($currentData['company_website'] ?? '') ?>"
                               placeholder="https://example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="company_size">Розмір компанії</label>
                        <select id="company_size" name="company_size">
                            <option value="">Оберіть розмір</option>
                            <option value="1-10" <?= ($currentData['company_size'] ?? '') === '1-10' ? 'selected' : '' ?>>1-10 співробітників</option>
                            <option value="11-50" <?= ($currentData['company_size'] ?? '') === '11-50' ? 'selected' : '' ?>>11-50 співробітників</option>
                            <option value="51-200" <?= ($currentData['company_size'] ?? '') === '51-200' ? 'selected' : '' ?>>51-200 співробітників</option>
                            <option value="201-500" <?= ($currentData['company_size'] ?? '') === '201-500' ? 'selected' : '' ?>>201-500 співробітників</option>
                            <option value="500+" <?= ($currentData['company_size'] ?? '') === '500+' ? 'selected' : '' ?>>Більше 500 співробітників</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_industry">Сфера діяльності</label>
                        <input type="text" id="company_industry" name="company_industry" 
                               value="<?= htmlspecialchars($currentData['company_industry'] ?? '') ?>"
                               placeholder="Наприклад: ІТ, Фінанси, Торгівля">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Зберегти зміни</button>
                    <a href="/frontend/profile.php" class="btn btn-secondary">❌ Відмінити</a>
                </div>
                
                <!-- Navigation Links for Employers -->
                <?php if ($userData['role'] === 'employer' && $editType !== 'full'): ?>
                <div class="navigation-links">
                    <h4>Перемкнути режим редагування:</h4>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem;">
                        <?php if ($editType === 'personal'): ?>
                            <a href="edit_profile.php?type=company" class="btn btn-outline">🏢 Редагувати компанію</a>
                            <a href="/frontend/edit_profile.php" class="btn btn-outline">📝 Повне редагування</a>
                        <?php elseif ($editType === 'company'): ?>
                            <a href="edit_profile.php?type=personal" class="btn btn-outline">👤 Редагувати профіль</a>
                            <a href="/frontend/edit_profile.php" class="btn btn-outline">📝 Повне редагування</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </main>    <!-- Footer -->
    <footer class="footer">
        <div class="section-container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>SearchJob</h4>
                    <p style="color: #a0aec0; margin-bottom: 1.5rem;">
                        Провідна платформа для пошуку роботи в Україні
                    </p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Для кандидатів</h4>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_list.php">Пошук вакансій</a></li>
                        <li><a href="/frontend/companies_list.php">Компанії</a></li>
                        <li><a href="/frontend/register.php">Створити резюме</a></li>
                        <li><a href="#">Кар'єрні поради</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Для роботодавців</h4>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_create.php">Додати вакансію</a></li>
                        <li><a href="/frontend/register.php">Реєстрація компанії</a></li>
                        <li><a href="#">Пошук кандидатів</a></li>
                        <li><a href="#">Тарифи</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Підтримка</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:support@searchjob.com">support@searchjob.com</a></li>
                        <li><a href="tel:+380441234567">+380 44 123 45 67</a></li>
                        <li><a href="#">Допомога</a></li>
                        <li><a href="#">Умови використання</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2025 SearchJob. Всі права захищені.</p>
            </div>
        </div>
    </footer>    <script>
        // Enhanced animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const icon = themeToggle.querySelector('i');
            
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
                
                // Update navbar background immediately
                const navbar = document.querySelector('.navbar');
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    if (newTheme === 'dark') {
                        navbar.style.background = 'rgba(26, 32, 44, 0.98)';
                    } else {
                        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                    }
                } else {
                    if (newTheme === 'dark') {
                        navbar.style.background = 'rgba(26, 32, 44, 0.95)';
                    } else {
                        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    }
                }
                
                // Add theme transition effect
                themeToggle.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    themeToggle.style.transform = '';
                }, 500);
            });
              function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }

            // Form Enhancements
            function enhanceForm() {
                // Add character counter for textareas
                const textareas = document.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    const maxLength = textarea.getAttribute('maxlength');
                    if (maxLength) {
                        const counter = document.createElement('div');
                        counter.className = 'character-counter';
                        counter.style.cssText = 'font-size: 0.8rem; color: var(--text-secondary); text-align: right; margin-top: 0.25rem;';
                        textarea.parentNode.appendChild(counter);
                        
                        function updateCounter() {
                            const current = textarea.value.length;
                            counter.textContent = `${current}/${maxLength}`;
                            counter.style.color = current > maxLength * 0.9 ? '#ef4444' : 'var(--text-secondary)';
                        }
                        
                        textarea.addEventListener('input', updateCounter);
                        updateCounter();
                    }
                });

                // Phone number formatting
                const phoneInput = document.getElementById('phone');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.startsWith('38')) {
                            value = value.substring(2);
                        }
                        if (value.length > 0) {
                            if (value.length <= 3) {
                                value = `+38 (${value}`;
                            } else if (value.length <= 6) {
                                value = `+38 (${value.substring(0, 3)}) ${value.substring(3)}`;
                            } else if (value.length <= 8) {
                                value = `+38 (${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
                            } else {
                                value = `+38 (${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6, 8)}-${value.substring(8, 10)}`;
                            }
                        }
                        e.target.value = value;
                    });
                }
            }
            
            enhanceForm();// Enhanced navbar scroll effect
            let lastScrollTop = 0;
            let isScrolling = false;
            
            window.addEventListener('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        const navbar = document.querySelector('.navbar');
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                        
                        if (scrollTop > 100) {
                            if (isDarkTheme) {
                                navbar.style.background = 'rgba(26, 32, 44, 0.98)';
                            } else {
                                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                            }
                            navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.1)';
                            navbar.style.backdropFilter = 'blur(10px)';
                        } else {
                            if (isDarkTheme) {
                                navbar.style.background = 'rgba(26, 32, 44, 0.95)';
                            } else {
                                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                            }
                            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
                            navbar.style.backdropFilter = 'blur(5px)';
                        }
                        
                        // Hide/show navbar on scroll
                        if (scrollTop > lastScrollTop && scrollTop > 200) {
                            navbar.style.transform = 'translateY(-100%)';
                        } else {
                            navbar.style.transform = 'translateY(0)';
                        }
                        
                        lastScrollTop = scrollTop;
                        isScrolling = false;
                    });
                }
                isScrolling = true;
            });        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Form Enhancements already initialized above
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const navMenu = document.querySelector('.nav-menu');
                const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
                
                if (navMenu && mobileMenuBtn && !navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    navMenu.classList.remove('active');
                }
            });

            // Auto-save form data to localStorage
            const form = document.querySelector('.edit-form');
            if (form) {
                const inputs = form.querySelectorAll('input, textarea, select');
                
                inputs.forEach(input => {
                    // Load saved data
                    const savedValue = localStorage.getItem(`form_${input.name}`);
                    if (savedValue && !input.value) {
                        input.value = savedValue;
                    }
                    
                    // Save on change
                    input.addEventListener('change', function() {
                        localStorage.setItem(`form_${input.name}`, input.value);
                    });
                });                // Clear saved data on successful submit
                form.addEventListener('submit', function() {
                    inputs.forEach(input => {
                        localStorage.removeItem(`form_${input.name}`);
                    });
                });
            }
        });
        
        }); // Закрывающая скобка для основного DOMContentLoaded
    </script>
</body>
</html>
