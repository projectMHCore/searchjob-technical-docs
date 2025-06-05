<!DOCTYPE html>
<html lang="uk" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Керування заявками - SearchJob</title>
    <meta name="description" content="Керуйте заявками на вакансії на SearchJob">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E💼%3C/text%3E%3C/svg%3E">
    
    <!-- Fonts -->
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
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, #f39c12 100%);
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--background-color);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
        }

        [data-theme="dark"] .navbar {
            background: rgba(45, 45, 45, 0.95);
            box-shadow: var(--shadow-medium);
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
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.1);
        }

        .nav-link.active {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.15);
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 3rem 2rem;
            background: var(--gradient-primary);
            border-radius: var(--border-radius-lg);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="2" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="2" fill="white" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .page-header .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
        }

        /* Statistics Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 1rem;
        }

        /* Filters Section */
        .filters-section {
            background: var(--surface-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
        }

        .filters-section h3 {
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--background-color);
            color: var(--text-primary);
            transition: var(--transition);
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(234, 168, 80, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1rem;
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
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 2px 8px rgba(234, 168, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(234, 168, 80, 0.4);
        }

        .btn-secondary {
            background: var(--border-color);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--text-secondary);
            color: white;
            transform: translateY(-1px);
        }

        /* Applications Grid */
        .applications-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .application-card {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .application-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-primary);
        }

        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .application-info {
            flex: 1;
        }

        .vacancy-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .applicant-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .application-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #f57c00;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .status-viewed {
            background: rgba(52, 144, 220, 0.1);
            color: #1976d2;
            border: 1px solid rgba(52, 144, 220, 0.2);
        }

        .status-accepted {
            background: rgba(76, 175, 80, 0.1);
            color: #388e3c;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .status-rejected {
            background: rgba(244, 67, 54, 0.1);
            color: #d32f2f;
            border: 1px solid rgba(244, 67, 54, 0.2);
        }

        .application-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(234, 168, 80, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(234, 168, 80, 0.1);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .meta-item strong {
            color: var(--text-primary);
        }

        .cover-letter {
            background: var(--background-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            border-left: 4px solid var(--primary-color);
            line-height: 1.6;
        }

        .cover-letter h4 {
            margin-bottom: 1rem;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        /* Application Actions */
        .application-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .status-update-form {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .status-select {
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--background-color);
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface-color);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
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
                padding: 1rem 0;
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

            .stats-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .filters-form {
                grid-template-columns: 1fr;
            }

            .application-card {
                padding: 1.5rem;
            }

            .application-header {
                flex-direction: column;
                align-items: stretch;
            }

            .application-meta {
                grid-template-columns: 1fr;
            }

            .application-actions {
                flex-direction: column;
            }

            .status-update-form {
                flex-direction: column;
                align-items: stretch;
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

        .application-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .page-header {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .stats-section {
            animation: fadeInUp 0.6s ease-out 0.4s both;
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
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'employer'): ?>
                        <a href="/frontend/my_vacancies.php" class="nav-link">
                            <i class="fas fa-list"></i> Мої вакансії
                        </a>
                        <a href="/frontend/manage_applications.php" class="nav-link active">
                            <i class="fas fa-clipboard-list"></i> Заявки
                        </a>
                    <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'job_seeker'): ?>
                        <a href="/frontend/my_applications.php" class="nav-link">
                            <i class="fas fa-paper-plane"></i> Мої відгуки
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
                <h1 class="animate__animated animate__fadeInDown">📋 Керування заявками</h1>
                <p class="subtitle animate__animated animate__fadeInUp animate__delay-1s">Переглядайте та керуйте заявками кандидатів на ваші вакансії</p>
            </div>

            <?php if (isset($_GET['success']) && $_GET['success'] === 'status_updated'): ?>
                <div class="alert alert-success">
                    <span>✅</span>
                    Статус заявки успешно обновлен!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <span>❌</span>
                    <?php 
                    $errorMessages = [
                        'invalid_data' => 'Неверные данные для обновления статуса',
                        'default' => 'Произошла ошибка при обработке запроса'
                    ];
                    echo htmlspecialchars($errorMessages[$_GET['error']] ?? $errorMessages['default']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error">
                    <span>❌</span>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filters-section">
                <h3>🔍 Фильтры</h3>
                <form method="get" class="filters-form">
                    <div class="filter-group">
                        <label for="status">Статус заявки:</label>
                        <select name="status" id="status">
                            <option value="">Все статусы</option>
                            <option value="pending" <?= isset($filters['status']) && $filters['status'] === 'pending' ? 'selected' : '' ?>>На рассмотрении</option>
                            <option value="viewed" <?= isset($filters['status']) && $filters['status'] === 'viewed' ? 'selected' : '' ?>>Просмотрено</option>
                            <option value="accepted" <?= isset($filters['status']) && $filters['status'] === 'accepted' ? 'selected' : '' ?>>Принятые</option>
                            <option value="rejected" <?= isset($filters['status']) && $filters['status'] === 'rejected' ? 'selected' : '' ?>>Отклоненные</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="vacancy">Название вакансии:</label>
                        <input type="text" name="vacancy" id="vacancy" 
                               value="<?= htmlspecialchars($filters['vacancy'] ?? '') ?>" 
                               placeholder="Поиск по названию вакансии">
                    </div>
                    
                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-primary">
                            <span>🔍</span>
                            Фильтровать
                        </button>
                        <a href="manage_applications.php" class="btn btn-secondary">
                            <span>🗑️</span>
                            Очистить
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
                        <div class="stat-label">Всего заявок</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $pendingApps ?></div>
                        <div class="stat-label">На рассмотрении</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $viewedApps ?></div>
                        <div class="stat-label">Просмотрено</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $acceptedApps ?></div>
                        <div class="stat-label">Принято</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $rejectedApps ?></div>
                        <div class="stat-label">Отклонено</div>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="applications-grid">
                    <?php 
                    // Status mapping functions
                    function getStatusText($status) {
                        switch ($status) {
                            case 'pending': return 'На рассмотрении';
                            case 'viewed': return 'Просмотрено';
                            case 'accepted': return 'Принято';
                            case 'rejected': return 'Отклонено';
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
                            case 'pending': return '⏳';
                            case 'viewed': return '👁️';
                            case 'accepted': return '✅';
                            case 'rejected': return '❌';
                            default: return '⏳';
                        }
                    }
                    ?>
                    
                    <?php foreach ($applications as $application): ?>
                        <div class="application-card">
                            <div class="application-header">
                                <div class="application-info">
                                    <div class="vacancy-title">💼 <?= htmlspecialchars($application['title'] ?? 'Вакансия') ?></div>
                                    <div class="applicant-name">👤 <?= htmlspecialchars($application['first_name'] ?? '') ?> <?= htmlspecialchars($application['last_name'] ?? '') ?></div>
                                </div>
                                <div class="application-status <?= getStatusClass($application['status']) ?>">
                                    <span><?= getStatusIcon($application['status']) ?></span>
                                    <?= getStatusText($application['status']) ?>
                                </div>
                            </div>
                            
                            <div class="application-meta">
                                <div class="meta-item">
                                    <strong>📧</strong>
                                    <span><?= htmlspecialchars($application['email'] ?? 'Не указан') ?></span>
                                </div>
                                
                                <?php if (!empty($application['phone'])): ?>
                                    <div class="meta-item">
                                        <strong>📱</strong>
                                        <span><?= htmlspecialchars($application['phone']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($application['experience_years'])): ?>
                                    <div class="meta-item">
                                        <strong>💼</strong>
                                        <span><?= htmlspecialchars($application['experience_years']) ?> лет опыта</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($application['education'])): ?>
                                    <div class="meta-item">
                                        <strong>🎓</strong>
                                        <span><?= htmlspecialchars($application['education']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($application['salary_expectation'])): ?>
                                    <div class="meta-item">
                                        <strong>💰</strong>
                                        <span><?= htmlspecialchars($application['salary_expectation']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="meta-item">
                                    <strong>📅</strong>
                                    <span>Подано: <?= date('d.m.Y H:i', strtotime($application['created_at'])) ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($application['skills'])): ?>
                                <div class="meta-item" style="margin-bottom: 1rem;">
                                    <strong>🛠️ Навыки:</strong>
                                    <span><?= htmlspecialchars($application['skills']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($application['about_me'])): ?>
                                <div class="meta-item" style="margin-bottom: 1rem;">
                                    <strong>ℹ️ О себе:</strong>
                                    <div style="margin-top: 0.5rem; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars(mb_substr($application['about_me'], 0, 300))) ?>
                                        <?= mb_strlen($application['about_me']) > 300 ? '...' : '' ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($application['cover_letter'])): ?>
                                <div class="cover-letter">
                                    <h4>📝 Сопроводительное письмо:</h4>
                                    <div><?= nl2br(htmlspecialchars($application['cover_letter'])) ?></div>
                                </div>
                            <?php endif; ?>

                            <div class="application-actions">
                                <form method="post" action="manage_applications.php" class="status-update-form">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="application_id" value="<?= htmlspecialchars($application['id']) ?>">
                                    
                                    <select name="status" class="status-select" required>
                                        <option value="">Изменить статус</option>
                                        <option value="pending" <?= $application['status'] === 'pending' ? 'disabled' : '' ?>>На рассмотрении</option>
                                        <option value="viewed" <?= $application['status'] === 'viewed' ? 'disabled' : '' ?>>Просмотрено</option>
                                        <option value="accepted" <?= $application['status'] === 'accepted' ? 'disabled' : '' ?>>Принять</option>
                                        <option value="rejected" <?= $application['status'] === 'rejected' ? 'disabled' : '' ?>>Отклонить</option>
                                    </select>
                                    
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <span>💾</span>
                                        Обновить
                                    </button>
                                </form>
                                
                                <?php if ($application['status'] === 'pending'): ?>
                                    <form method="post" action="manage_applications.php" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="application_id" value="<?= htmlspecialchars($application['id']) ?>">
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span>✅</span>
                                            Принять
                                        </button>
                                    </form>
                                    
                                    <form method="post" action="manage_applications.php" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="application_id" value="<?= htmlspecialchars($application['id']) ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите отклонить эту заявку?')">
                                            <span>❌</span>
                                            Отклонить
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Заявок пока нет</h3>
                    <p>Пока никто не подавал заявки на ваши вакансии. Создайте привлекательные объявления о работе, чтобы привлечь больше кандидатов!</p>
                    <div style="margin-top: 2rem;">
                        <a href="vacancy_create.php" class="btn btn-primary">
                            <span>➕</span>
                            Создать вакансию
                        </a>
                        <a href="my_vacancies.php" class="btn btn-secondary">
                            <span>📋</span>
                            Мои вакансии
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
                    <li><a href="manage_applications.php">Заявки кандидатов</a></li>
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
    </footer>

    <script>        // Theme Management
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

        // Initialize theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Set initial icon
            const themeIcon = document.querySelector('.theme-toggle i');
            if (themeIcon) {
                themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        });

        // Mobile Menu
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }

        // Auto-mark as viewed when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматически отмечаем заявки как просмотренные при загрузке страницы
            const pendingApplications = document.querySelectorAll('.application-card .status-pending');
            if (pendingApplications.length > 0) {
                // Можно добавить AJAX запрос для автоматического обновления статуса на "viewed"
                console.log('Найдено заявок на рассмотрении:', pendingApplications.length);
            }
        });

        // Confirm actions
        document.addEventListener('DOMContentLoaded', function() {
            const rejectButtons = document.querySelectorAll('.btn-danger');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Вы уверены, что хотите отклонить эту заявку? Это действие нельзя отменить.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
