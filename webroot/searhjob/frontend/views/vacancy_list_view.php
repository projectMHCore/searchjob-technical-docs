<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вакансії - SearchJob</title>
    <meta name="description" content="Знайдіть роботу мрії серед тисяч актуальних вакансій в Україні">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            overflow-x: hidden;
            position: relative;
            background: #f8fafc;
            min-height: 100vh;
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
        
        .nav-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
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
        .main-content {
            padding-top: 100px;
            min-height: 100vh;
        }
        
        .section-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .page-header {
            text-align: center;
            padding: 3rem 0;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            color: white;
            margin-bottom: 3rem;
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .page-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .filter-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 1px solid #3b82f6;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .filter-info p {
            margin: 0 0 1rem 0;
            color: #1e40af;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .search-filters {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .form-input, .form-select {
            padding: 1rem 1.5rem;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #eaa850;
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }
        .vacancies-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 3rem;
        }
          .vacancy-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
        }
        
        .vacancy-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .vacancy-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: #eaa850;
        }
        
        .vacancy-card:hover::before {
            transform: scaleY(1);
        }
        
        .vacancy-header {
            margin-bottom: 1.5rem;
        }
        
        .vacancy-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .vacancy-title a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .vacancy-title a:hover {
            color: #eaa850;
        }
        
        .vacancy-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .meta-item i {
            color: #eaa850;
        }
        
        .vacancy-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .vacancy-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: #eaa850;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #64748b;
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .footer {
            background: #1a202c;
            color: white;
            padding: 60px 0 30px;
            margin-top: 4rem;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 2rem;
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
            max-width: 1400px;
            margin: 0 auto;
            padding-left: 2rem;
            padding-right: 2rem;
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
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --border-color: #e1e8ed;
        }
        
        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --text-primary: #ffffff;
            --text-secondary: #cbd5e0;
            --border-color: #4a5568;
        }
        
        [data-theme="dark"] body {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }
        
        [data-theme="dark"] .vacancy-card,
        [data-theme="dark"] .search-filters,
        [data-theme="dark"] .empty-state {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .form-input,
        [data-theme="dark"] .form-select {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .vacancy-title a {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .filter-group label {
            color: var(--text-primary);
        }
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .search-filters {
                padding: 1.5rem;
                grid-template-columns: 1fr;
            }
            
            .vacancy-actions {
                flex-direction: column;
            }
            
            .vacancy-meta {
                flex-direction: column;
                gap: 0.5rem;
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
              <div class="nav-menu">                <a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Головна
                </a>
                <a href="/frontend/vacancy_list.php" class="nav-link" style="color: #eaa850;">
                    <i class="fas fa-search"></i>
                    Вакансії
                </a>
                <a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i>
                    Компанії
                </a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'employer'): ?>
                    <a href="/frontend/my_vacancies.php" class="nav-link">
                        <i class="fas fa-list"></i>
                        Мої вакансії
                    </a>
                <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'job_seeker'): ?>
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
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/frontend/login.php" class="btn-secondary">Увійти</a>
                    <a href="/frontend/register.php" class="btn-primary">Реєстрація</a>
                <?php else: ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        Профіль
                    </a>
                    <a href="/frontend/logout.php" class="btn-secondary">Вихід</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <div class="section-container">
                <h1 class="page-title">Вакансії</h1>
                <p class="page-subtitle">Знайдіть роботу мрії серед тисяч актуальних пропозицій</p>
            </div>
        </section>

        <div class="section-container">
            <?php if (!empty($filters['employer'])): ?>
                <div class="filter-info">
                    <p><i class="fas fa-info-circle"></i> Показані вакансії обраної компанії</p>
                    <a href="/frontend/vacancy_list.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Показати всі вакансії
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Search and Filters -->
            <form class="search-filters" method="GET" action="vacancy_list.php">
                <div class="filter-group">
                    <label for="search">
                        <i class="fas fa-search"></i>
                        Пошук по назві
                    </label>
                    <input type="text" id="search" name="search" class="form-input" placeholder="Введіть назву посади..." value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="location">
                        <i class="fas fa-map-marker-alt"></i>
                        Місто
                    </label>
                    <input type="text" id="location" name="location" class="form-input" placeholder="Введіть місто..." value="<?= htmlspecialchars($filters['location']) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="employment_type">
                        <i class="fas fa-clock"></i>
                        Тип зайнятості
                    </label>
                    <select id="employment_type" name="employment_type" class="form-select">
                        <option value="">Усі типи</option>
                        <option value="full_time" <?= $filters['employment_type'] === 'full_time' ? 'selected' : '' ?>>Повна зайнятість</option>
                        <option value="part_time" <?= $filters['employment_type'] === 'part_time' ? 'selected' : '' ?>>Часткова зайнятість</option>
                        <option value="remote" <?= $filters['employment_type'] === 'remote' ? 'selected' : '' ?>>Віддалена робота</option>
                        <option value="internship" <?= $filters['employment_type'] === 'internship' ? 'selected' : '' ?>>Стажування</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn-primary" style="width: 100%;">
                        <i class="fas fa-search"></i>
                        Знайти
                    </button>
                </div>
            </form>
            
            <?php if ($error): ?>
                <div class="error-message" style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; border-left: 4px solid #dc2626; margin-bottom: 2rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <!-- Vacancies List -->
            <div class="vacancies-grid">
                <?php if (empty($vacancies)): ?>
                    <div class="empty-state">
                        <?php if (!empty($filters['employer'])): ?>
                            <div class="empty-state-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>У даної компанії немає активних вакансій</h3>
                            <p>Дана компанія поки не розмістила активних вакансій на платформі.</p>
                            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                                <a href="/frontend/vacancy_list.php" class="btn-primary">
                                    <i class="fas fa-list"></i>
                                    Переглянути всі вакансії
                                </a>
                                <a href="/frontend/companies_list.php" class="btn-secondary">
                                    <i class="fas fa-building"></i>
                                    Інші компанії
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Вакансії не знайдені</h3>
                            <p>Спробуйте змінити параметри пошуку або перегляньте всі доступні вакансії.</p>
                            <a href="/frontend/vacancy_list.php" class="btn-primary">
                                <i class="fas fa-refresh"></i>
                                Переглянути всі вакансії
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($vacancies as $index => $vacancy): ?>
                        <div class="vacancy-card animate-on-scroll" style="animation-delay: <?= $index * 0.1 ?>s;">
                            <div class="vacancy-header">
                                <h3 class="vacancy-title">
                                    <a href="vacancy_detail.php?id=<?= $vacancy['id'] ?>">
                                        <?= htmlspecialchars($vacancy['title']) ?>
                                    </a>
                                </h3>
                                
                                <div class="vacancy-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-building"></i>
                                        <span><?= htmlspecialchars($vacancy['company']) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($vacancy['location']) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span><?= htmlspecialchars($vacancy['salary']) ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?= htmlspecialchars($vacancy['employment_type']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="vacancy-description">
                                <?= htmlspecialchars(mb_substr($vacancy['description'], 0, 200)) ?>...
                            </div>
                            
                            <div class="vacancy-actions">
                                <a href="vacancy_detail.php?id=<?= $vacancy['id'] ?>" class="btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Детальніше
                                </a>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'job_seeker'): ?>
                                    <a href="apply_vacancy.php?id=<?= $vacancy['id'] ?>" class="btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                        Відгукнутися
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Additional Actions -->
            <div style="text-align: center; margin: 3rem 0;">
                <a href="/frontend/index.php" class="btn-secondary">
                    <i class="fas fa-home"></i>
                    На головну
                </a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'employer'): ?>
                    <a href="/frontend/vacancy_create.php" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Створити вакансію
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
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
            <p>© <?php echo date("Y"); ?> SearchJob. Всі права захищені.</p>
        </div>
    </footer>

    <script>
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
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, observerOptions);
            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                observer.observe(element);
            });
            document.querySelectorAll('.vacancy-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            document.querySelectorAll('.btn-primary, .btn-secondary, .btn-success').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
