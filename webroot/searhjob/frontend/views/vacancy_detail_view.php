<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($vacancy['title'] ?? 'Деталі вакансії') ?> - SearchJob</title>
    <meta name="description" content="<?= htmlspecialchars(mb_substr($vacancy['description'] ?? '', 0, 160)) ?>">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
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
        }        [data-theme="dark"] {
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --background-primary: #1e293b;
            --background-secondary: #334155;
            --border-color: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            transition: var(--transition-normal);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            width: 100%;            z-index: 1000;
            padding: 1rem 0;
            transition: var(--transition-normal);
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
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
        }

        .nav-brand i {
            color: var(--primary-color);
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
            font-weight: 500;            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition-normal);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;            font-weight: 600;
            transition: var(--transition-normal);
            cursor: pointer;
            display: inline-flex;
            align-items: center;            gap: 0.5rem;
            box-shadow: var(--shadow-md);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }        .btn-secondary {
            background: var(--background-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 44px;            height: 44px;
            cursor: pointer;
            transition: var(--transition-normal);
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

        .main-content {
            padding-top: 100px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .vacancy-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-medium);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .vacancy-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .vacancy-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .vacancy-company {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .info-item i {
            color: #ffd700;
            font-size: 1.25rem;
            width: 20px;
        }

        .info-value {
            font-weight: 600;
        }

        .salary {
            font-size: 1.25rem;
            font-weight: 800;
            color: #ffd700;
        }        .content-section {
            background: var(--background-primary);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            animation: fadeInUp 0.6s ease-out;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .content {
            line-height: 1.8;
            color: var(--text-secondary);
            font-size: 1.05rem;
        }        .application-section {
            background: linear-gradient(135deg, var(--background-primary), var(--background-secondary));
            border-left: 5px solid var(--primary-color);
            position: relative;
            overflow: hidden;
        }

        .application-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(234, 168, 80, 0.1));
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            resize: vertical;            font-family: inherit;
            font-size: 1rem;
            background: var(--background-primary);
            color: var(--text-primary);
            transition: var(--transition-normal);
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 3rem 0;
            flex-wrap: wrap;
        }

        .success-message {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .info-message {
            background: linear-gradient(135deg, var(--secondary-color), #5b67d8);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .login-prompt {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .error-card {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 2px solid #f87171;
            border-radius: 16px;
            color: #991b1b;
            animation: fadeInUp 0.6s ease-out;
        }

        .error-card h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

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

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        [data-theme="dark"] .navbar {
            background: rgba(30, 41, 59, 0.95);
        }

        [data-theme="dark"] .content-section {            background: var(--background-primary);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .form-group textarea {
            background: var(--background-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        /* Footer */
        .footer {
            background: var(--background-secondary);
            color: var(--text-primary);
            padding: 3rem 0;
            margin-top: 4rem;
            border-top: 1px solid var(--border-color);
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
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition-normal);
        }

        .footer-section ul li a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .vacancy-title {
                font-size: 2rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
                align-items: center;
            }            .container {
                padding: 0 1rem;
            }
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
    </style>
</head>
<body data-theme="light">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">            <a href="/frontend/index.php" class="nav-brand">
                <i class="fas fa-briefcase"></i>
                <span>SearchJob</span>
            </a>
              <ul class="nav-menu">                <li><a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i> Головна
                </a></li>
                <li><a href="/frontend/vacancy_list.php" class="nav-link active">
                    <i class="fas fa-briefcase"></i> Вакансії
                </a></li>
                <li><a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i> Компанії
                </a></li>
            </ul>
            
            <div class="nav-actions">                <?php if (isset($_SESSION['token'])): ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i> Профіль
                    </a>
                    <a href="/frontend/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Вийти
                    </a>
                <?php else: ?>                    <a href="/frontend/login.php" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i> Увійти
                    </a>
                    <a href="/frontend/register.php" class="btn">
                        <i class="fas fa-user-plus"></i> Реєстрація
                    </a>
                <?php endif; ?>
                
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?php if ($vacancy): ?>
                <!-- Vacancy Header -->
                <div class="vacancy-header">
                    <h1 class="vacancy-title"><?= htmlspecialchars($vacancy['title']) ?></h1>
                    <?php if (!empty($vacancy['company'])): ?>
                        <div class="vacancy-company">
                            <i class="fas fa-building"></i> 
                            <?= htmlspecialchars($vacancy['company']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-grid">
                        <?php if (!empty($vacancy['location'])): ?>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="info-value"><?= htmlspecialchars($vacancy['location']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($vacancy['employment_type'])): ?>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span class="info-value"><?= htmlspecialchars($vacancy['employment_type']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($vacancy['salary'])): ?>
                            <div class="info-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <span class="info-value salary"><?= htmlspecialchars($vacancy['salary']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="info-value"><?= date('d.m.Y', strtotime($vacancy['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Description Section -->
                <div class="content-section">
                    <h3 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Опис вакансії
                    </h3>
                    <div class="content">
                        <?= nl2br(htmlspecialchars($vacancy['clean_description'] ?? $vacancy['description'])) ?>
                    </div>
                </div>
                
                <!-- Requirements Section -->
                <?php if (!empty($vacancy['requirements'])): ?>
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-check-circle"></i>
                            Вимоги до кандидата
                        </h3>
                        <div class="content">
                            <?= nl2br(htmlspecialchars($vacancy['requirements'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
                  <!-- Application Section -->
                <?php if (isset($canApply) && $canApply && !$hasApplied): ?>
                    <div class="content-section application-section">
                        <h3 class="section-title">
                            <i class="fas fa-paper-plane"></i>
                            Подати заявку на вакансію
                        </h3>
                        <form method="post" action="/frontend/apply_vacancy.php">
                            <input type="hidden" name="vacancy_id" value="<?= htmlspecialchars($vacancy['id']) ?>">
                            <div class="form-group">
                                <label for="cover_letter">
                                    <i class="fas fa-envelope"></i> Супровідний лист:
                                </label>
                                <textarea id="cover_letter" name="cover_letter" rows="6" 
                                          placeholder="Розкажіть, чому ви підходите для цієї позиції..."></textarea>
                            </div>
                            <button type="submit" class="btn">
                                <i class="fas fa-paper-plane"></i> Надіслати заявку
                            </button>
                        </form>
                    </div>
                <?php elseif (isset($hasApplied) && $hasApplied): ?>
                    <div class="content-section application-section">
                        <div class="success-message">
                            <h3><i class="fas fa-check-circle"></i> Заявку надіслано</h3>
                            <p>Ви вже подавали заявку на цю вакансію. Очікуйте відповіді від роботодавця.</p>
                        </div>
                        <div style="text-align: center;">
                            <a href="/frontend/my_applications.php" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Мої заявки
                            </a>
                        </div>
                    </div>
                <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'employer'): ?>
                    <div class="content-section application-section">
                        <div class="info-message">
                            <h3><i class="fas fa-info-circle"></i> Інформація</h3>
                            <p>Ви переглядаєте вакансію як роботодавець.</p>
                        </div>
                    </div>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <div class="content-section application-section">
                        <div class="login-prompt">
                            <h3><i class="fas fa-lock"></i> Для подання заявки</h3>
                            <p>Увійдіть до системи як шукач роботи, щоб подати заявку на цю вакансію.</p>
                        </div>
                        <div style="text-align: center; margin-top: 1.5rem;">
                            <a href="/frontend/login.php" class="btn" style="margin-right: 1rem;">
                                <i class="fas fa-sign-in-alt"></i> Увійти
                            </a>
                            <a href="/frontend/register.php" class="btn btn-secondary">
                                <i class="fas fa-user-plus"></i> Реєстрація
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="error-card">
                    <h2><i class="fas fa-exclamation-triangle"></i> Вакансію не знайдено</h2>
                    <p><?= htmlspecialchars($error ?? 'Запитувана вакансія не існує або була видалена.') ?></p>
                </div>
            <?php endif; ?>            <!-- Navigation Actions -->
            <div class="actions">
                <a href="/frontend/vacancy_list.php" class="btn">
                    <i class="fas fa-list"></i> До списку вакансій
                </a>
                <a href="/frontend/index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Головна
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Про нас</h3>
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
                    <h3>Для кандидатів</h3>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_list.php">Пошук вакансій</a></li>
                        <li><a href="/frontend/companies_list.php">Компанії</a></li>
                        <li><a href="/frontend/register.php">Створити резюме</a></li>
                        <li><a href="#">Кар'єрні поради</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Для роботодавців</h3>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_create.php">Додати вакансію</a></li>
                        <li><a href="/frontend/register.php">Реєстрація компанії</a></li>
                        <li><a href="#">Пошук кандидатів</a></li>
                        <li><a href="#">Тарифи</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Підтримка</h3>
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
    </footer>

    <script>
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const themeIcon = document.querySelector('.theme-toggle i');
            themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
            
            const themeIcon = document.querySelector('.theme-toggle i');
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out';
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => observer.observe(section));
        });
    </script>
</body>
</html>
