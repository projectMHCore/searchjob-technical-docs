<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($vacancy) && $vacancy ? 'Редагувати вакансію' : 'Створити вакансію' ?> - SearchJob</title>
    <meta name="description" content="Створіть або відредагуйте вакансію на SearchJob">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        }        /* Modern Navbar */
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
            background: rgba(26, 32, 44, 0.95);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.1);
            transform: translateY(-2px);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 1px;
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

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 3rem 2rem;
            background: var(--gradient-primary);
            border-radius: var(--border-radius);
            color: white;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
        }

        .form-card {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            overflow: hidden;
            transition: var(--transition);
        }

        .form-content {
            padding: 2.5rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .required {
            color: #dc3545;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: var(--surface-color);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }
          .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
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

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        [data-theme="dark"] .alert-error {
            background: rgba(220, 53, 69, 0.2);
            color: #f8d7da;
        }

        [data-theme="dark"] .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #d4edda;
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

        .form-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .page-header {
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

            .page-header {
                padding: 2rem 1rem;
                margin-bottom: 2rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .form-content {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>    <!-- Modern Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/frontend/index.php" class="nav-brand">
                <i class="fas fa-briefcase"></i>
                <span>SearchJob</span>
            </a>
            
            <ul class="nav-menu">
                <li><a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i> Головна
                </a></li>
                <li><a href="/frontend/vacancy_list.php" class="nav-link">
                    <i class="fas fa-briefcase"></i> Вакансії
                </a></li>
                <li><a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i> Компанії
                </a></li>
            </ul>
            
            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i> Профіль
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
                        <a href="/frontend/my_vacancies.php" class="nav-link">
                            <i class="fas fa-list"></i> Мої вакансії
                        </a>
                    <?php endif; ?>
                    <a href="/frontend/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Вийти
                    </a>
                <?php else: ?>
                    <a href="/frontend/login.php" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i> Увійти
                    </a>
                    <a href="/frontend/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Реєстрація
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">            <!-- Page Header -->
            <div class="page-header">
                <h1 class="animate__animated animate__fadeInDown"><?= isset($vacancy) && $vacancy ? '✏️ Редагувати вакансію' : '➕ Створити нову вакансію' ?></h1>
                <p class="animate__animated animate__fadeInUp animate__delay-1s"><?= isset($vacancy) && $vacancy ? 'Внесіть зміни у вашу вакансію для залучення кращих кандидатів' : 'Розмістіть вакансію для пошуку талановитих співробітників' ?></p>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error">
                    <span>⚠️</span>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    <span>✅</span>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Vacancy Form -->
            <div class="form-card">
                <div class="form-content">
                    <form method="POST" class="vacancy-form">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span>📋</span>
                                Основная информация
                            </h2>
                            
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    <span>💼</span>
                                    Название вакансии
                                    <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-input" required 
                                       value="<?= htmlspecialchars($vacancy->title ?? '') ?>" 
                                       placeholder="Например: Senior PHP Developer">
                                <div class="help-text">Укажите должность и ключевые технологии</div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company" class="form-label">
                                        <span>🏢</span>
                                        Название компании
                                    </label>
                                    <input type="text" id="company" name="company" class="form-input"
                                           value="<?= htmlspecialchars($vacancy->company ?? '') ?>" 
                                           placeholder="Ваша компания">
                                </div>
                                
                                <div class="form-group">
                                    <label for="location" class="form-label">
                                        <span>📍</span>
                                        Местоположение
                                    </label>
                                    <input type="text" id="location" name="location" class="form-input"
                                           value="<?= htmlspecialchars($vacancy->location ?? '') ?>" 
                                           placeholder="Например: Харьков, удаленно">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="salary" class="form-label">
                                        <span>💰</span>
                                        Зарплата
                                    </label>
                                    <input type="text" id="salary" name="salary" class="form-input"
                                           value="<?= htmlspecialchars($vacancy->salary ?? '') ?>" 
                                           placeholder="Например: 80,000 - 120,000 грн">
                                    <div class="help-text">Укажите диапазон или конкретную сумму</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="employment_type" class="form-label">
                                        <span>⏰</span>
                                        Тип занятости
                                    </label>
                                    <select id="employment_type" name="employment_type" class="form-select">
                                        <option value="">Выберите тип</option>
                                        <option value="Полная занятость" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Полная занятость' ? 'selected' : '' ?>>Полная занятость</option>
                                        <option value="Частичная занятость" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Частичная занятость' ? 'selected' : '' ?>>Частичная занятость</option>
                                        <option value="Удаленная работа" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Удаленная работа' ? 'selected' : '' ?>>Удаленная работа</option>
                                        <option value="Стажировка" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Стажировка' ? 'selected' : '' ?>>Стажировка</option>
                                        <option value="Фриланс" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Фриланс' ? 'selected' : '' ?>>Фриланс</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span>📝</span>
                                Подробная информация
                            </h2>
                            
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <span>📄</span>
                                    Описание вакансии
                                    <span class="required">*</span>
                                </label>
                                <textarea id="description" name="description" class="form-textarea" required 
                                          placeholder="Подробное описание позиции, обязанности, условия работы, преимущества компании..."><?= htmlspecialchars($vacancy->description ?? '') ?></textarea>
                                <div class="help-text">Опишите основные обязанности, рабочие процессы и что делает вашу компанию особенной</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="requirements" class="form-label">
                                    <span>✅</span>
                                    Требования к кандидату
                                </label>
                                <textarea id="requirements" name="requirements" class="form-textarea"
                                          placeholder="Необходимые навыки, технологии, опыт работы, образование, личные качества..."><?= htmlspecialchars($vacancy->requirements ?? '') ?></textarea>
                                <div class="help-text">Укажите обязательные и желательные требования к кандидату</div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <span><?= isset($vacancy) && $vacancy ? '💾' : '✨' ?></span>
                                <?= isset($vacancy) && $vacancy ? 'Сохранить изменения' : 'Создать вакансию' ?>
                            </button>
                            <a href="<?= isset($vacancy) && $vacancy ? 'my_vacancies.php' : 'vacancy_list.php' ?>" class="btn btn-secondary">
                                <span>❌</span>
                                Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>SearchJob</h3>
                <ul>
                    <li><a href="index.php">О платформе</a></li>
                    <li><a href="vacancy_list.php">Поиск работы</a></li>
                    <li><a href="companies_list.php">Компании</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Для соискателей</h3>
                <ul>
                    <li><a href="vacancy_list.php">Вакансии</a></li>
                    <li><a href="register.php">Создать резюме</a></li>
                    <li><a href="my_applications.php">Мои отклики</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Для работодателей</h3>
                <ul>
                    <li><a href="vacancy_create.php">Разместить вакансию</a></li>
                    <li><a href="my_vacancies.php">Управление вакансиями</a></li>
                    <li><a href="register.php">Регистрация работодателя</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Поддержка</h3>
                <ul>
                    <li><a href="#help">Помощь</a></li>
                    <li><a href="#contact">Контакты</a></li>
                    <li><a href="#privacy">Конфиденциальность</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SearchJob. Платформа для поиска работы и талантов.</p>
        </div>
    </footer>    <script>
        // Theme Management
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update theme icon
            const themeIcon = document.querySelector('.theme-toggle i');
            if (themeIcon) {
                themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Set initial icon
            const themeIcon = document.querySelector('.theme-toggle i');
            if (themeIcon) {
                themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        });

        // Form enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-resize textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });

            // Form validation feedback
            const form = document.querySelector('.vacancy-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = '#dc3545';
                            isValid = false;
                        } else {
                            field.style.borderColor = '';
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Будь ласка, заповніть усі обов\'язкові поля');
                    }
                });
            }
        });
    </script>
</body>
</html>
