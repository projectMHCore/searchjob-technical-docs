<?php
// Страница просмотра зарегистрированных компаний
session_start();

// Подключение к базе данных
$config = require __DIR__ . '/../backend/config/db.php';
$db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}

// Получаем список всех компаний (работодателей)
$query = "SELECT 
    id, login, email, company_name, company_description, 
    company_address, company_website, company_size, 
    company_industry, created_at,
    (SELECT COUNT(*) FROM vacancies WHERE employer_id = users.id AND is_active = 1) as active_vacancies,
    (SELECT COUNT(*) FROM vacancies WHERE employer_id = users.id) as total_vacancies
    FROM users 
    WHERE role = 'employer' 
    ORDER BY created_at DESC";

$result = $db->query($query);

// Получаем статистику
$totalCompanies = $result->num_rows;
$totalVacanciesQuery = $db->query("SELECT COUNT(*) as count FROM vacancies WHERE is_active = 1");
$totalVacancies = $totalVacanciesQuery->fetch_assoc()['count'];
$totalApplicationsQuery = $db->query("SELECT COUNT(*) as count FROM job_applications");
$totalApplications = $totalApplicationsQuery->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компанії - SearchJob</title>
    <meta name="description" content="Перегляньте компанії-роботодавців на платформі SearchJob">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">    <style>
        /* Professional Modern Styles */
        :root {
            --primary-color: #eaa850;
            --primary-hover: #d4941e;
            --secondary-color: #2c3e50;
            --background-color: #ffffff;
            --surface-color: #f8f9fa;
            --text-primary: #1a1a1a;
            --text-secondary: #666666;
            --border-color: #e9ecef;
            --shadow-light: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-medium: 0 4px 20px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --primary-color: #eaa850;
            --primary-hover: #d4941e;
            --secondary-color: #34495e;
            --background-color: #1a1a1a;
            --surface-color: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --border-color: #404040;
            --shadow-light: 0 2px 10px rgba(0,0,0,0.3);
            --shadow-medium: 0 4px 20px rgba(0,0,0,0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
            position: relative;
            background: var(--background-color);
            min-height: 100vh;
            transition: var(--transition);
        }        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
            border-bottom: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] .navbar {
            background: rgba(26, 26, 26, 0.95);
            box-shadow: var(--shadow-medium);
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
            background: linear-gradient(135deg, var(--primary-color), #f39c12);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
          .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: var(--transition);
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
          .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #d4922a);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
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
            color: var(--text-primary);
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }
          /* Theme Toggle */
        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-toggle:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: scale(1.1);
        }

        /* Main Content */
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
            background: linear-gradient(135deg, var(--primary-color), #d4922a);
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

        /* Statistics Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            border-radius: 16px;
            margin-left: 2rem;
            margin-right: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
          .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }        /* Search Filters */
        .search-filters {
            background: var(--surface-color);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow-light);
            margin-bottom: 3rem;
            animation: fadeInUp 0.6s ease-out;
            border: 1px solid var(--border-color);
        }
        
        .filters-grid {
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        /* Companies Grid */
        .companies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
          .company-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        [data-theme="dark"] .company-card {
            background: var(--bg-primary);
            border-color: var(--border-color);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }
        
        .company-card::before {
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
        
        .company-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        [data-theme="dark"] .company-card:hover {
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }
        
        .company-card:hover::before {
            transform: scaleY(1);
        }
        
        .company-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }
        
        .company-info h3 {
            margin: 0 0 0.25rem 0;
            color: #2c3e50;
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .company-info .industry {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .company-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .company-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
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
            width: 16px;
        }
        
        .company-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .stat {
            text-align: center;
            flex: 1;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #eaa850;
            margin-bottom: 0.25rem;
        }
        
        .stat-title {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-small.btn-primary {
            background: linear-gradient(135deg, #eaa850, #d4922a);
            color: white;
            box-shadow: 0 2px 10px rgba(234, 168, 80, 0.3);
        }
        
        .btn-small.btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e1e8ed;
        }
        
        .btn-small:hover {
            transform: translateY(-2px);
        }

        /* Empty State */
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

        /* Footer */
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

        /* Dark Theme Adjustments */
        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }
        
        [data-theme="dark"] .company-card,
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
        
        [data-theme="dark"] .company-info h3 {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .filter-group label {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .company-stats {
            background: var(--bg-secondary);
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .companies-grid {
                grid-template-columns: 1fr;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .company-meta {
                grid-template-columns: 1fr;
            }
            
            .company-actions {
                flex-direction: column;
            }
        }    </style>
</head>
<body data-theme="light">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-briefcase"></i>
                <span>SearchJob</span>
            </div>
              <ul class="nav-menu">                <li><a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i> Головна
                </a></li>
                <li><a href="/frontend/vacancy_list.php" class="nav-link">
                    <i class="fas fa-briefcase"></i> Вакансії
                </a></li>
                <li><a href="/frontend/companies_list.php" class="nav-link active">
                    <i class="fas fa-building"></i> Компанії
                </a></li>
            </ul>
              <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                
                <?php if (isset($_SESSION['token'])): ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i> Профіль
                    </a>
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <div class="section-container">
                <h1 class="page-title animate__animated animate__fadeInUp">
                    <i class="fas fa-building"></i> Компанії-роботодавці
                </h1>
                <p class="page-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                    Знайдіть найкращих роботодавців та дізнайтеся більше про компанії вашої мрії
                </p>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="section-container">
                <div class="stats-grid">
                    <div class="stat-card animate__animated animate__fadeInUp">
                        <div class="stat-number"><?= $totalCompanies ?></div>
                        <div class="stat-label">
                            <i class="fas fa-building"></i> Зареєстровані компанії
                        </div>
                    </div>
                    <div class="stat-card animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="stat-number"><?= $totalVacancies ?></div>
                        <div class="stat-label">
                            <i class="fas fa-briefcase"></i> Активні вакансії
                        </div>
                    </div>
                    <div class="stat-card animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="stat-number"><?= $totalApplications ?></div>
                        <div class="stat-label">
                            <i class="fas fa-paper-plane"></i> Надіслано відгуків
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="section-container">
            <!-- Search Filters -->
            <div class="search-filters">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700; color: #2c3e50;">
                    <i class="fas fa-filter" style="color: #eaa850; margin-right: 0.5rem;"></i>
                    Фільтри пошуку
                </h3>
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="industry">
                            <i class="fas fa-industry"></i> Галузь
                        </label>
                        <select id="industry" class="form-select" onchange="filterCompanies()">
                            <option value="">Усі галузі</option>
                            <option value="Інформаційні технології">IT</option>
                            <option value="Фінанси">Фінанси</option>
                            <option value="Освіта">Освіта</option>
                            <option value="Медицина">Медицина</option>
                            <option value="Торгівля">Торгівля</option>
                            <option value="Виробництво">Виробництво</option>
                            <option value="Маркетинг">Маркетинг</option>
                            <option value="Дизайн">Дизайн</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="company_size">
                            <i class="fas fa-users"></i> Розмір компанії
                        </label>
                        <select id="company_size" class="form-select" onchange="filterCompanies()">
                            <option value="">Будь-який розмір</option>
                            <option value="1-10 співробітників">Стартап (1-10)</option>
                            <option value="11-50 співробітників">Мала (11-50)</option>
                            <option value="51-100 співробітників">Середня (51-100)</option>
                            <option value="100+ співробітників">Велика (100+)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="search">
                            <i class="fas fa-search"></i> Пошук
                        </label>
                        <input type="text" id="search" class="form-input" placeholder="Назва компанії..." onkeyup="filterCompanies()">
                    </div>
                </div>
            </div>            </div>

            <!-- Companies Grid -->
            <div class="companies-grid" id="companiesGrid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($company = $result->fetch_assoc()): ?>
                        <div class="company-card" 
                             data-industry="<?= htmlspecialchars($company['company_industry'] ?? '') ?>"
                             data-size="<?= htmlspecialchars($company['company_size'] ?? '') ?>"
                             data-name="<?= htmlspecialchars($company['company_name'] ?? $company['login']) ?>">
                            
                            <div class="company-header">
                                <div class="company-logo">
                                    <?= strtoupper(substr($company['company_name'] ?: $company['login'], 0, 2)) ?>
                                </div>
                                <div class="company-info">
                                    <h3><?= htmlspecialchars($company['company_name'] ?: $company['login']) ?></h3>
                                    <div class="industry"><?= htmlspecialchars($company['company_industry'] ?: 'Галузь не вказана') ?></div>
                                </div>
                            </div>

                            <?php if (!empty($company['company_description'])): ?>
                                <div class="company-description">
                                    <?= nl2br(htmlspecialchars(mb_substr($company['company_description'], 0, 200) . (mb_strlen($company['company_description']) > 200 ? '...' : ''))) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="company-meta">
                                <?php if (!empty($company['company_size'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span><?= htmlspecialchars($company['company_size']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($company['company_address'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($company['company_address']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?= htmlspecialchars($company['email']) ?></span>
                                </div>
                                
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>З <?= date('M Y', strtotime($company['created_at'])) ?></span>
                                </div>
                                
                                <?php if (!empty($company['company_website'])): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-globe"></i>
                                        <a href="<?= htmlspecialchars($company['company_website']) ?>" target="_blank" rel="noopener" style="color: #eaa850; text-decoration: none;">
                                            Веб-сайт
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="company-stats">
                                <div class="stat">
                                    <div class="stat-value"><?= $company['active_vacancies'] ?></div>
                                    <div class="stat-title">Активні вакансії</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value"><?= $company['total_vacancies'] ?></div>
                                    <div class="stat-title">Всього вакансій</div>
                                </div>
                            </div>

                            <div class="company-actions">
                                <a href="vacancy_list.php?employer=<?= $company['id'] ?>" class="btn-small btn-primary">
                                    <i class="fas fa-briefcase"></i> Вакансії
                                </a>
                                <?php if (!empty($company['company_website'])): ?>
                                    <a href="<?= htmlspecialchars($company['company_website']) ?>" target="_blank" class="btn-small btn-secondary">
                                        <i class="fas fa-external-link-alt"></i> Сайт
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Компанії не знайдено</h3>
                        <p>Поки що жодна компанія не зареєструвалася на платформі або не відповідає критеріям пошуку.</p>
                        <a href="/frontend/register.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Зареєструвати компанію
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-section">
                <h4><i class="fas fa-briefcase"></i> SearchJob</h4>
                <p>Найкраща платформа для пошуку роботи в Україні. Знайдіть роботу своєї мрії або найкращих кандидатів для вашої компанії.</p>
            </div>
            
            <div class="footer-section">
                <h4>Для кандидатів</h4>
                <ul class="footer-links">
                    <li><a href="/frontend/vacancy_list.php">Пошук вакансій</a></li>
                    <li><a href="/frontend/companies_list.php">Компанії</a></li>
                    <li><a href="/frontend/register.php">Створити резюме</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Для роботодавців</h4>
                <ul class="footer-links">
                    <li><a href="/frontend/register.php">Реєстрація компанії</a></li>
                    <li><a href="/frontend/post_vacancy.php">Розмістити вакансію</a></li>
                    <li><a href="/frontend/candidates_list.php">База резюме</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Контакти</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-envelope"></i> info@searchjob.ua</li>
                    <li><i class="fas fa-phone"></i> +38 (050) 123-45-67</li>
                    <li><i class="fas fa-map-marker-alt"></i> Харків, Україна</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 SearchJob. Усі права захищені. | 
               <a href="#" style="color: #eaa850;">Політика конфіденційності</a> | 
               <a href="#" style="color: #eaa850;">Умови використання</a>
            </p>
        </div>
    </footer>

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
        });

        // Company Filtering
        function filterCompanies() {
            const industry = document.getElementById('industry').value.toLowerCase();
            const size = document.getElementById('company_size').value.toLowerCase();
            const search = document.getElementById('search').value.toLowerCase();
            const cards = document.querySelectorAll('.company-card');

            let visibleCount = 0;

            cards.forEach(card => {
                const cardIndustry = card.dataset.industry.toLowerCase();
                const cardSize = card.dataset.size.toLowerCase();
                const cardName = card.dataset.name.toLowerCase();

                const industryMatch = !industry || cardIndustry.includes(industry);
                const sizeMatch = !size || cardSize.includes(size);
                const searchMatch = !search || cardName.includes(search);

                if (industryMatch && sizeMatch && searchMatch) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.6s ease-out';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            const emptyState = document.querySelector('.empty-state');
            const companiesGrid = document.getElementById('companiesGrid');
            
            if (visibleCount === 0 && cards.length > 0) {
                if (!emptyState) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = `
                        <div class="empty-state-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Нічого не знайдено</h3>
                        <p>Спробуйте змінити критерії пошуку або очистити фільтри.</p>
                    `;
                    companiesGrid.appendChild(emptyDiv);
                }
            } else if (emptyState && visibleCount > 0) {
                emptyState.remove();
            }
        }

        // Smooth animations on scroll
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
            const cards = document.querySelectorAll('.company-card');
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>
</html>
