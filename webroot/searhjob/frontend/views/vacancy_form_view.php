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
        }        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        [data-theme="dark"] .footer {
            background: linear-gradient(135deg, #0d1117 0%, #161b22 100%);
        }

        .section-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h4 {
            color: #eaa850;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: #eaa850;
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #4a5568;
            color: #a0aec0;
            font-size: 0.9rem;
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
                        <!-- Basic Information Section -->                        <div class="form-section">
                            <h2 class="section-title">
                                <span>📋</span>
                                Основна інформація
                            </h2>
                            
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    <span>💼</span>
                                    Назва вакансії
                                    <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-input" required 
                                       value="<?= htmlspecialchars($vacancy->title ?? '') ?>" 
                                       placeholder="Наприклад: Senior PHP Developer">
                                <div class="help-text">Вкажіть посаду та ключові технології</div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company" class="form-label">
                                        <span>🏢</span>
                                        Назва компанії
                                    </label>
                                    <input type="text" id="company" name="company" class="form-input"
                                           value="<?= htmlspecialchars($vacancy->company ?? '') ?>" 
                                           placeholder="Ваша компанія">
                                </div>
                                
                                <div class="form-group">
                                    <label for="location" class="form-label">
                                        <span>📍</span>
                                        Місцерозташування
                                    </label>
                                    <input type="text" id="location" name="location" class="form-input"
                                           value="<?= htmlspecialchars($vacancy->location ?? '') ?>" 
                                           placeholder="Наприклад: Харків, віддалено">
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
                                           placeholder="Наприклад: 80,000 - 120,000 грн">
                                    <div class="help-text">Вкажіть діапазон або конкретну суму</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="employment_type" class="form-label">
                                        <span>⏰</span>
                                        Тип зайнятості
                                    </label>
                                    <select id="employment_type" name="employment_type" class="form-select">
                                        <option value="">Оберіть тип</option>
                                        <option value="Повна зайнятість" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Повна зайнятість' ? 'selected' : '' ?>>Повна зайнятість</option>
                                        <option value="Часткова зайнятість" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Часткова зайнятість' ? 'selected' : '' ?>>Часткова зайнятість</option>
                                        <option value="Віддалена робота" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Віддалена робота' ? 'selected' : '' ?>>Віддалена робота</option>
                                        <option value="Стажування" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Стажування' ? 'selected' : '' ?>>Стажування</option>
                                        <option value="Фріланс" <?= isset($vacancy->employment_type) && $vacancy->employment_type === 'Фріланс' ? 'selected' : '' ?>>Фріланс</option>
                                    </select>
                                </div>
                            </div>
                        </div>                        <!-- Description Section -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <span>📝</span>
                                Детальна інформація
                            </h2>
                            
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <span>📄</span>
                                    Опис вакансії
                                    <span class="required">*</span>
                                </label>
                                <textarea id="description" name="description" class="form-textarea" required 
                                          placeholder="Детальний опис позиції, обов'язки, умови роботи, переваги компанії..."><?= htmlspecialchars($vacancy->description ?? '') ?></textarea>
                                <div class="help-text">Опишіть основні обов'язки, робочі процеси та що робить вашу компанію особливою</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="requirements" class="form-label">
                                    <span>✅</span>
                                    Вимоги до кандидата
                                </label>
                                <textarea id="requirements" name="requirements" class="form-textarea"
                                          placeholder="Необхідні навички, технології, досвід роботи, освіта, особисті якості..."><?= htmlspecialchars($vacancy->requirements ?? '') ?></textarea>
                                <div class="help-text">Вкажіть обов'язкові та бажані вимоги до кандидата</div>
                            </div>
                        </div>
                          <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981, #059669); color: white; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                                <span><?= isset($vacancy) && $vacancy ? '💾' : '✨' ?></span>
                                <?= isset($vacancy) && $vacancy ? 'Зберегти зміни' : 'Створити вакансію' ?>
                            </button>
                            <a href="<?= isset($vacancy) && $vacancy ? 'my_vacancies.php' : 'vacancy_list.php' ?>" class="btn btn-secondary">
                                <span>❌</span>
                                Скасувати
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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
    </footer><script>
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
                    });                    if (!isValid) {
                        e.preventDefault();
                        alert('Будь ласка, заповніть всі обов\'язкові поля');
                    }
                });
            }
        });
    </script>
</body>
</html>
