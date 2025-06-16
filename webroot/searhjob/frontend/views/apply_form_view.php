<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подати заявку - <?= htmlspecialchars($vacancy['title']) ?> - SearchJob</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>        :root {
            --primary-orange: #eaa850;
            --primary-dark: #d4922a;
            --primary-color: #eaa850;
            --primary-hover: #d4922a;
            --secondary-color: #f97316;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --background-primary: #ffffff;
            --background-secondary: #f8fafc;
            --border-color: #e2e8f0;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --transition-normal: 0.3s ease-in-out;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --background-color: var(--background-primary);
            --transition: var(--transition-normal);
        }        [data-theme="dark"] {
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --background-primary: #1e293b;
            --background-secondary: #334155;
            --border-color: #475569;
            --background-color: var(--background-primary);
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

        /* Modern Navbar */
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
            background: rgba(30, 41, 59, 0.95);
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
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--gradient-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--primary-color);
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
            background: var(--border-color);
        }

        /* Main Content */
        .main-content {
            margin-top: 100px;
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Vacancy Info Card */
        .vacancy-info {
            background: var(--gradient-primary);
            color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .vacancy-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .vacancy-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .vacancy-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            font-size: 1rem;
            opacity: 0.95;
            position: relative;
        }

        .vacancy-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Application Form */
        .apply-form {
            background: var(--surface-color);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .warning-banner {
            background: rgba(234, 168, 80, 0.1);
            color: var(--primary-dark);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid rgba(234, 168, 80, 0.2);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        [data-theme="dark"] .warning-banner {
            background: rgba(234, 168, 80, 0.2);
            color: var(--primary-light);
        }

        .warning-banner strong {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: var(--surface-color);
            color: var(--text-primary);
            resize: vertical;
            min-height: 150px;
            transition: var(--transition);
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2.5rem;
            flex-wrap: wrap;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(0);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(234, 168, 80, 0.3);
        }

        .btn-secondary {
            background: var(--border-color);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background: var(--text-secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        /* Footer */
        .footer {
            background: var(--secondary-color);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }

        [data-theme="dark"] .footer {
            background: #0d1117;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #b0b0b0;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-section ul li a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #404040;
            color: #888;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .vacancy-info {
            animation: fadeInUp 0.6s ease-out;
        }

        .apply-form {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }

            .nav-links {
                display: none;
            }

            .container {
                padding: 0 1rem;
            }

            .vacancy-info, .apply-form {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .vacancy-title {
                font-size: 1.5rem;
            }

            .vacancy-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .form-actions {
                flex-direction: column;
            }            .btn {
                justify-content: center;
            }
        }

        /* Additional Styles */
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .help-text {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <!-- Modern Navigation -->
    <nav class="navbar">
        <div class="nav-container">            <a href="/frontend/index.php" class="logo">
                <div class="logo-icon">S</div>
                SearchJob
            </a>
            <ul class="nav-links">                <li><a href="/frontend/index.php" class="nav-link">Головна</a></li>
                <li><a href="/frontend/vacancy_list.php" class="nav-link">Вакансії</a></li>
                <li><a href="/frontend/companies_list.php" class="nav-link">Компанії</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/frontend/profile.php" class="nav-link">Профіль</a></li>                    <li><a href="/frontend/my_applications.php" class="nav-link">Мої відгуки</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
                        <li><a href="/frontend/my_vacancies.php" class="nav-link">Мої вакансії</a></li>
                    <?php endif; ?>
                    <li><a href="/frontend/logout.php" class="nav-link">Вийти</a></li>
                <?php else: ?>
                    <li><a href="/frontend/login.php" class="nav-link">Увійти</a></li>
                    <li><a href="/frontend/register.php" class="nav-link">Реєстрація</a></li>
                <?php endif; ?>
            </ul>
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Переключить тему">
                <span class="theme-icon">🌙</span>
            </button>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <!-- Vacancy Information -->
            <div class="vacancy-info">
                <h1 class="vacancy-title">💼 <?= htmlspecialchars($vacancy['title']) ?></h1>
                
                <div class="vacancy-meta">
                    <?php if (!empty($vacancy['company'])): ?>
                        <span><strong>🏢</strong> <?= htmlspecialchars($vacancy['company']) ?></span>
                    <?php endif; ?>
                    
                    <?php if (!empty($vacancy['location'])): ?>
                        <span><strong>📍</strong> <?= htmlspecialchars($vacancy['location']) ?></span>
                    <?php endif; ?>
                    
                    <?php if (!empty($vacancy['salary'])): ?>
                        <span><strong>💰</strong> <?= htmlspecialchars($vacancy['salary']) ?></span>
                    <?php endif; ?>
                    
                    <?php if (!empty($vacancy['employment_type'])): ?>
                        <span><strong>⏰</strong> <?= htmlspecialchars($vacancy['employment_type']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Application Form -->
            <div class="apply-form">
                <h2 class="form-title">
                    <span>📬</span>
                    Подати заявку на вакансію
                </h2>
                
                <div class="warning-banner">
                    <div>
                        <strong>
                            <span>💡</span>
                            Важные рекомендации:
                        </strong>
                        <p>Убедитесь, что ваш профиль заполнен полностью. Работодатели обращают внимание на опыт работы, навыки и образование при рассмотрении заявок. Качественное сопроводительное письмо значительно повышает ваши шансы на успех.</p>
                    </div>
                </div>
                
                <form method="POST" action="apply_vacancy.php" class="application-form">
                    <input type="hidden" name="vacancy_id" value="<?= htmlspecialchars($vacancy_id) ?>">
                    
                    <div class="form-group">
                        <label for="cover_letter" class="form-label">
                            <span>📝</span>
                            Супровідний лист
                        </label>
                        <textarea id="cover_letter" name="cover_letter" class="form-textarea"
                                  placeholder="Розкажіть, чому ви підходите для цієї позиції:&#10;&#10;• Ваш релевантний досвід та досягнення&#10;• Ключові навички, що відповідають вимогам&#10;• Мотивація працювати в цій компанії&#10;• Що унікального ви можете привнести в команду&#10;&#10;Покажіть свою зацікавленість та професіоналізм!"></textarea>
                        <div class="help-text">                            💡 <strong>Порада:</strong> Хороший супровідний лист збільшує ваші шанси на отримання запрошення на співбесіду.
                            <br>📏 Рекомендований розмір: 150-400 слів. Будьте конкретними та уникайте загальних фраз.
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span>📤</span>
                            Надіслати заявку
                        </button>                        <a href="/frontend/vacancy_detail.php?id=<?= htmlspecialchars($vacancy_id) ?>" class="btn btn-secondary">
                            <span>↩️</span>
                            Назад до вакансії
                        </a>
                        <a href="/frontend/profile.php" class="btn btn-secondary">
                            <span>👤</span>
                            Мій профіль
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">            <div class="footer-section">
                <h3>SearchJob</h3>
                <ul>                    <li><a href="/frontend/index.php">Про платформу</a></li>
                    <li><a href="/frontend/vacancy_list.php">Пошук роботи</a></li>
                    <li><a href="/frontend/companies_list.php">Компанії</a></li>
                </ul>
            </div>
            <div class="footer-section">                <h3>Для кандидатів</h3>
                <ul>
                    <li><a href="/frontend/vacancy_list.php">Вакансії</a></li>
                    <li><a href="/frontend/register.php">Створити резюме</a></li>
                    <li><a href="/frontend/my_applications.php">Мої відгуки</a></li>
                </ul>
            </div>
            <div class="footer-section">                <h3>Для роботодавців</h3>
                <ul>
                    <li><a href="/frontend/vacancy_create.php">Розмістити вакансію</a></li>
                    <li><a href="/frontend/my_vacancies.php">Управління вакансіями</a></li>
                    <li><a href="/frontend/register.php">Реєстрація роботодавця</a></li>
                </ul>
            </div>
            <div class="footer-section">                <h3>Підтримка</h3>
                <ul>
                    <li><a href="#help">Допомога</a></li>
                    <li><a href="#contact">Контакти</a></li>
                    <li><a href="#privacy">Конфіденційність</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 SearchJob. Платформа для пошуку роботи та талантів.</p>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const themeIcon = document.querySelector('.theme-icon');
            themeIcon.textContent = newTheme === 'dark' ? '☀️' : '🌙';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const themeIcon = document.querySelector('.theme-icon');
            themeIcon.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
            const textarea = document.getElementById('cover_letter');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
            const form = document.querySelector('.application-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const coverLetter = document.getElementById('cover_letter').value.trim();
                    
                    if (coverLetter.length < 50) {
                        e.preventDefault();
                        alert('Будь ласка, напишіть детальніший супровідний лист (мінімум 50 символів)');
                        return;
                    }
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<span>⏳</span> Надсилання...';
                    submitBtn.disabled = true;                });
            }
        });
    </script>
</body>
</html>
