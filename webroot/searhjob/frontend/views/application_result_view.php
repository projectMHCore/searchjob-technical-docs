<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $success ? 'Заявка отправлена' : 'Ошибка отправки' ?> - SearchJob</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #eaa850;
            --primary-dark: #d19339;
            --primary-light: #f3c577;
            --secondary-color: #2c3e50;
            --background-color: #f8f9fa;
            --surface-color: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --border-color: #e9ecef;
            --success-color: #28a745;
            --error-color: #dc3545;
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --background-color: #1a1a1a;
            --surface-color: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
            --secondary-color: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background-color);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        [data-theme="dark"] .navbar {
            background: rgba(45, 45, 45, 0.95);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.1);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .theme-toggle:hover {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Result Card */
        .result-card {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            padding: 3rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-color);
            text-align: center;
        }

        .result-icon {
            font-size: 4rem;
            margin-bottom: 2rem;
        }

        .result-icon.success {
            color: var(--success-color);
        }

        .result-icon.error {
            color: var(--error-color);
        }

        .result-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .result-title.success {
            color: var(--success-color);
        }

        .result-title.error {
            color: var(--error-color);
        }

        .result-message {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .result-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }

        .btn-secondary {
            background: var(--border-color);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background: var(--text-secondary);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }

            .container {
                padding: 0 1rem;
            }

            .result-card {
                padding: 2rem;
            }

            .result-title {
                font-size: 1.5rem;
            }

            .result-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="/frontend/index.php" class="logo">
                <div class="logo-icon">SJ</div>
                <span>SearchJob</span>
            </a>            <ul class="nav-menu">                <li><a href="/frontend/index.php" class="nav-link"><i class="fas fa-home"></i> Головна</a></li>
                <li><a href="/frontend/vacancy_list.php" class="nav-link"><i class="fas fa-briefcase"></i> Вакансії</a></li>
                <li><a href="/frontend/companies_list.php" class="nav-link"><i class="fas fa-building"></i> Компанії</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/frontend/my_applications.php" class="nav-link"><i class="fas fa-file-alt"></i> Мои отклики</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
                        <li><a href="/frontend/my_vacancies.php" class="nav-link"><i class="fas fa-plus-circle"></i> Мои вакансии</a></li>
                    <?php endif; ?>
                    <li><a href="/frontend/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                <?php else: ?>
                    <li><a href="/frontend/login.php" class="nav-link"><i class="fas fa-sign-in-alt"></i> Вход</a></li>
                    <li><a href="/frontend/register.php" class="nav-link"><i class="fas fa-user-plus"></i> Регистрация</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Переключить тему">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="result-card">
                <div class="result-icon <?= $success ? 'success' : 'error' ?>">
                    <?php if ($success): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php endif; ?>
                </div>
                
                <h1 class="result-title <?= $success ? 'success' : 'error' ?>">
                    <?= $success ? 'Заявка отправлена!' : 'Ошибка отправки' ?>
                </h1>
                
                <p class="result-message">
                    <?= htmlspecialchars($message) ?>
                </p>
                  <div class="result-actions">
                    <?php if ($success): ?>
                        <a href="/frontend/my_applications.php" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Мои отклики
                        </a>
                        <a href="/frontend/vacancy_list.php" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Другие вакансии
                        </a>
                    <?php else: ?>
                        <a href="javascript:history.back()" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Попробовать снова
                        </a>
                        <a href="/frontend/vacancy_list.php" class="btn btn-secondary">
                            <i class="fas fa-list"></i> К списку вакансий
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update theme icon
            const themeIcon = document.querySelector('.theme-icon');
            themeIcon.textContent = newTheme === 'dark' ? '☀️' : '🌙';
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const themeIcon = document.querySelector('.theme-icon');
            themeIcon.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
        });

        // Auto-redirect on success after 5 seconds
        <?php if ($success): ?>
        setTimeout(function() {
            if (confirm('Перейти к списку ваших откликов?')) {
                window.location.href = 'my_applications_view.php';
            }
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>
