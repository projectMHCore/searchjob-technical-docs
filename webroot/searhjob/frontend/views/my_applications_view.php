<!DOCTYPE html>
<html lang="uk" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мої заявки - SearchJob</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
      
    <style>
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
        
        .nav-link.active {
            color: #eaa850;
            background: rgba(234, 168, 80, 0.1);
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(234, 168, 80, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            color: #2c3e50;
            padding: 0.75rem 1.5rem;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: translateY(-2px);
        }
        
        /* Main content spacing */
        .main-content {
            padding-top: 80px;
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
        
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .applications-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .application-card {
            background: var(--background-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-normal);
        }
        
        .application-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--background-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        
        .filter-section {
            background: var(--background-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .applications-list {
            min-height: 400px;
        }
          .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }
        
        /* Footer */
        .footer {
            background: #1a202c;
            color: white;
            padding: 60px 0 30px;
            margin-top: 4rem;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #eaa850;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            border-top: 1px solid #2d3748;
            padding-top: 2rem;
            text-align: center;
            color: #a0aec0;
        }
        
        /* Dark theme styles */
        [data-theme="dark"] {
            background-color: #1a202c;
            color: #e2e8f0;
        }
        
        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }
        
        [data-theme="dark"] .nav-link {
            color: #e2e8f0;
        }
        
        [data-theme="dark"] .nav-link:hover {
            color: #eaa850;
            background: rgba(234, 168, 80, 0.1);
        }
        
        [data-theme="dark"] .nav-link.active {
            color: #eaa850;
            background: rgba(234, 168, 80, 0.1);
        }
        
        [data-theme="dark"] .applications-container,
        [data-theme="dark"] .page-header,
        [data-theme="dark"] .application-card,
        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .filter-section {
            background: #2d3748;
            color: #e2e8f0;
            border-color: #4a5568;
        }
        
        [data-theme="dark"] .theme-toggle {
            border-color: #4a5568;
            color: #e2e8f0;
        }
        
        [data-theme="dark"] .theme-toggle:hover {
            border-color: #eaa850;
            color: #eaa850;
        }
        
        [data-theme="dark"] .btn-secondary {
            border-color: #4a5568;
            color: #e2e8f0;
        }
          [data-theme="dark"] .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
        }
        
        /* Dark theme footer styles */
        [data-theme="dark"] .footer {
            background: #1a202c;
        }
        
        [data-theme="dark"] .footer-section h3 {
            color: #eaa850;
        }
        
        [data-theme="dark"] .footer-links a {
            color: #a0aec0;
        }
        
        [data-theme="dark"] .footer-links a:hover {
            color: #eaa850;
        }
        
        [data-theme="dark"] .footer-bottom {
            border-top-color: #2d3748;
            color: #a0aec0;
        }
          /* Mobile responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                flex-direction: column;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                z-index: 999;
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #2c3e50;
            }
            
            .applications-container {
                padding: 1rem;
            }
            
            .page-header {
                padding: 2rem 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .nav-auth {
                gap: 0.5rem;
            }
            
            .btn-primary, .btn-secondary {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        Профіль
                    </a>
                    <a href="/frontend/my_applications.php" class="nav-link active">
                        <i class="fas fa-file-alt"></i>
                        Мої заявки
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
                        <a href="/frontend/my_vacancies.php" class="nav-link">
                            <i class="fas fa-plus-circle"></i>
                            Мої вакансії
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="nav-auth">
                <button id="theme-toggle" class="theme-toggle" title="Переключити тему">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        Вихід
                    </a>
                <?php else: ?>
                    <a href="/frontend/login.php" class="btn-secondary">
                        <i class="fas fa-sign-in-alt"></i>
                        Увійти
                    </a>
                    <a href="/frontend/register.php" class="btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Реєстрація
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="applications-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1><i class="fas fa-file-alt"></i> Мої заявки</h1>
                <p class="subtitle">Відстежуйте статус ваших заявок на вакансії та керуйте своїми відгуками</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success']) && $_GET['success'] === 'application_sent'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Заявку успішно надіслано!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php 
                    $errorMessages = [
                        'access_denied' => 'Доступ заборонено',
                        'application_failed' => 'Помилка при надсиланні заявки',
                        'default' => 'Сталася помилка при обробці запиту'
                    ];
                    echo htmlspecialchars($errorMessages[$_GET['error']] ?? $errorMessages['default']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filter-section">
                <h3><i class="fas fa-filter"></i> Фільтри</h3>
                <form method="get" class="filters-form">
                    <div class="filter-group">
                        <label for="status">Статус заявки:</label>
                        <select name="status" id="status">
                            <option value="">Усі статуси</option>
                            <option value="pending" <?= isset($filters['status']) && $filters['status'] === 'pending' ? 'selected' : '' ?>>На розгляді</option>
                            <option value="viewed" <?= isset($filters['status']) && $filters['status'] === 'viewed' ? 'selected' : '' ?>>Переглянуто</option>
                            <option value="accepted" <?= isset($filters['status']) && $filters['status'] === 'accepted' ? 'selected' : '' ?>>Прийняті</option>
                            <option value="rejected" <?= isset($filters['status']) && $filters['status'] === 'rejected' ? 'selected' : '' ?>>Відхилені</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="vacancy">Назва вакансії:</label>
                        <input type="text" name="vacancy" id="vacancy" 
                               value="<?= htmlspecialchars($filters['vacancy'] ?? '') ?>" 
                               placeholder="Пошук за назвою вакансії">
                    </div>
                    
                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Фільтрувати
                        </button>
                        <a href="/frontend/my_applications.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Очистити
                        </a>
                    </div>
                </form>
            </div>

            <?php if (!empty($applications)): ?>
                <!-- Statistics Section -->
                <div class="stats-section">
                    <?php
                    $totalApps = count($applications);
                    $pendingApps = count(array_filter($applications, fn($app) => $app['status'] === 'pending'));
                    $viewedApps = count(array_filter($applications, fn($app) => $app['status'] === 'viewed'));
                    $acceptedApps = count(array_filter($applications, fn($app) => $app['status'] === 'accepted'));
                    $rejectedApps = count(array_filter($applications, fn($app) => $app['status'] === 'rejected'));
                    ?>
                    
                    <div class="stat-card">
                        <div class="stat-number"><?= $totalApps ?></div>
                        <div class="stat-label">Усього відгуків</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $pendingApps ?></div>
                        <div class="stat-label">На розгляді</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $viewedApps ?></div>
                        <div class="stat-label">Переглянуто</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $acceptedApps ?></div>
                        <div class="stat-label">Прийнято</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $rejectedApps ?></div>
                        <div class="stat-label">Відхилено</div>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="applications-list">
                    <?php 
                    // Status mapping functions
                    function getStatusText($status) {
                        switch ($status) {
                            case 'pending': return 'На розгляді';
                            case 'viewed': return 'Переглянуто';
                            case 'accepted': return 'Прийнято';
                            case 'rejected': return 'Відхилено';
                            default: return $status;
                        }
                    }
                    
                    function getStatusClass($status) {
                        switch ($status) {
                            case 'pending': return 'status-pending';
                            case 'viewed': return 'status-viewed';
                            case 'accepted': return 'status-accepted';
                            case 'rejected': return 'status-rejected';
                            default: return 'status-pending';
                        }
                    }
                    
                    function getStatusIcon($status) {
                        switch ($status) {
                            case 'pending': return 'fas fa-clock';
                            case 'viewed': return 'fas fa-eye';
                            case 'accepted': return 'fas fa-check-circle';
                            case 'rejected': return 'fas fa-times-circle';
                            default: return 'fas fa-clock';
                        }
                    }
                    ?>
                    
                    <?php foreach ($applications as $application): ?>
                        <div class="application-card">
                            <div class="application-header">
                                <div class="application-info">
                                    <div class="vacancy-title">
                                        <i class="fas fa-briefcase"></i> 
                                        <?= htmlspecialchars($application['title'] ?? 'Вакансія') ?>
                                    </div>
                                    <?php if (!empty($application['company_name'])): ?>
                                        <div class="company-name">
                                            <i class="fas fa-building"></i> 
                                            <?= htmlspecialchars($application['company_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="application-status <?= getStatusClass($application['status']) ?>">
                                    <i class="<?= getStatusIcon($application['status']) ?>"></i>
                                    <?= getStatusText($application['status']) ?>
                                </div>
                            </div>
                            
                            <div class="application-meta">
                                <?php if (!empty($application['salary'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <strong>Зарплата:</strong>
                                        <span><?= htmlspecialchars($application['salary']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <strong>Подано:</strong>
                                    <span><?= date('d.m.Y H:i', strtotime($application['created_at'])) ?></span>
                                </div>
                                
                                <?php if (!empty($application['vacancy_created'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-plus"></i>
                                        <strong>Створена:</strong>
                                        <span><?= date('d.m.Y', strtotime($application['vacancy_created'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($application['employer_login'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-user-tie"></i>
                                        <strong>Роботодавець:</strong>
                                        <span><?= htmlspecialchars($application['employer_login']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($application['description'])): ?>
                                <div class="meta-item" style="margin-bottom: 1rem;">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Опис вакансії:</strong>
                                    <div style="margin-top: 0.5rem; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars(mb_substr($application['description'], 0, 300))) ?>
                                        <?= mb_strlen($application['description']) > 300 ? '...' : '' ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($application['cover_letter'])): ?>
                                <div class="cover-letter">
                                    <h4><i class="fas fa-envelope"></i> Мій супровідний лист:</h4>
                                    <div><?= nl2br(htmlspecialchars($application['cover_letter'])) ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($application['status'] === 'accepted'): ?>
                                <div class="alert alert-success" style="margin-top: 1rem;">
                                    <i class="fas fa-check-circle"></i>
                                    Вітаємо! Вашу заявку прийнято. Очікуйте на контакт від роботодавця.
                                </div>
                            <?php elseif ($application['status'] === 'rejected'): ?>
                                <div class="alert alert-error" style="margin-top: 1rem;">
                                    <i class="fas fa-times-circle"></i>
                                    На жаль, вашу заявку було відхилено. Не засмучуйтеся, продовжуйте пошук!
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox" style="font-size: 4rem; color: var(--text-light);"></i>
                    </div>
                    <h3>У вас поки немає заявок</h3>
                    <p>Ви ще не подавали заявки на вакансії. Почніть пошук роботи та надішліть свій перший відгук!</p>
                    <div style="margin-top: 2rem;">
                        <a href="/frontend/vacancy_list.php" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Знайти вакансії
                        </a>
                        <a href="/frontend/profile.php" class="btn btn-secondary">
                            <i class="fas fa-user"></i>
                            Доповнити профіль
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>SearchJob</h3>
                <ul class="footer-links">
                    <li><a href="/frontend/index.php">Про платформу</a></li>
                    <li><a href="/frontend/vacancy_list.php">Пошук роботи</a></li>
                    <li><a href="/frontend/companies_list.php">Компанії</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Для шукачів</h3>
                <ul class="footer-links">
                    <li><a href="/frontend/vacancy_list.php">Вакансії</a></li>
                    <li><a href="/frontend/register.php">Створити резюме</a></li>
                    <li><a href="/frontend/my_applications.php">Мої заявки</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Для роботодавців</h3>
                <ul class="footer-links">
                    <li><a href="/frontend/vacancy_create.php">Розмістити вакансію</a></li>
                    <li><a href="/frontend/my_vacancies.php">Керування вакансіями</a></li>
                    <li><a href="/frontend/register.php">Реєстрація роботодавця</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Підтримка</h3>
                <ul class="footer-links">
                    <li><a href="mailto:support@searchjob.com">support@searchjob.com</a></li>
                    <li><a href="tel:+380441234567">+380 44 123 45 67</a></li>
                    <li><a href="#">Допомога</a></li>
                    <li><a href="#">Конфіденційність</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 SearchJob. Всі права захищені.</p>
        </div>
    </footer>    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const icon = themeToggle.querySelector('i');
            
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
                const navbar = document.querySelector('.navbar');
                if (newTheme === 'dark') {
                    navbar.style.background = 'rgba(26, 32, 44, 0.95)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                }
                
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
        });
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const pendingApplications = document.querySelectorAll('.application-card .status-pending');
            if (pendingApplications.length > 0) {
                console.log('Знайдено заявок на розгляді:', pendingApplications.length);
            }
        });
    </script>
</body>
</html>
