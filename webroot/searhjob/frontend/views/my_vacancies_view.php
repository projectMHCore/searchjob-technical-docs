<!DOCTYPE html>
<html lang="uk" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мої вакансії - SearchJob</title>
    <meta name="description" content="Керуйте вашими вакансіями на SearchJob">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E💼%3C/text%3E%3C/svg%3E">
    
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
            --transition: 0.3s ease-in-out;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --surface-color: #ffffff;
            --shadow-medium: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }[data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --bg-tertiary: #4a5568;
            --text-primary: #ffffff;
            --text-secondary: #cbd5e0;
            --text-light: #a0aec0;
            --border-color: #4a5568;
            --shadow: rgba(0,0,0,0.3);
            --background-primary: #1e293b;
            --background-secondary: #334155;
            --surface-color: #2d3748;
        }

        [data-theme="dark"] body {
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
            border-bottom-color: var(--border-color);
        }

        [data-theme="dark"] .nav-brand {
            color: #eaa850;
        }

        [data-theme="dark"] .nav-link {
            color: var(--text-primary);
        }

        [data-theme="dark"] .nav-link:hover {
            color: #eaa850;
        }

        [data-theme="dark"] .theme-toggle {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-theme="dark"] .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--background-primary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
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
        }        .nav-brand {
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
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.1);
        }

        .nav-link.active {
            color: var(--primary-color);
            background: rgba(234, 168, 80, 0.15);
        }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-secondary {
            background: var(--background-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--border-color);
            transform: translateY(-1px);
        }

        /* Footer Styles */
        .footer {
            background: var(--background-secondary);
            border-top: 1px solid var(--border-color);
            margin-top: 4rem;
            padding: 3rem 0 2rem;
        }

        [data-theme="dark"] .footer {
            background: var(--bg-secondary);
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            text-align: center;
            color: var(--text-secondary);
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
            max-width: 1200px;
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
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Actions Header */
        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Vacancy Cards */
        .vacancy-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
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
            background: var(--primary-color);
            transform: scaleY(0);
            transition: var(--transition);
        }

        .vacancy-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .vacancy-card:hover::before {
            transform: scaleY(1);
        }        .vacancy-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .vacancy-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
            flex: 1;
            min-width: 250px;
            transition: var(--transition);
        }

        .vacancy-title:hover {
            color: var(--primary-color);
        }

        .vacancy-actions {
            display: flex;
            gap: 0.75rem;
            flex-shrink: 0;
            flex-wrap: wrap;
            align-items: center;
        }

        .vacancy-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .salary {
            font-weight: 600;
            color: #10b981;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .vacancy-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .vacancy-stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
            align-items: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .status {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
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
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--border-color);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface-color);
            border-radius: var(--border-radius-lg);
            border: 2px dashed var(--border-color);
            margin: 2rem 0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
            font-size: 1.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
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

            .vacancy-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .vacancy-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .actions-header {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-section {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .vacancy-actions {
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

        .vacancy-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .vacancy-card:nth-child(even) {
            animation-delay: 0.1s;
        }

        .vacancy-card:nth-child(odd) {
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
                <a href="/frontend/my_vacancies.php" class="nav-link active">
                    <i class="fas fa-list"></i>
                    Мої вакансії
                </a>
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
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">            <!-- Page Header -->
            <div class="page-header">
                <h1 class="animate__animated animate__fadeInDown">📋 Мої вакансії</h1>
                <p class="subtitle animate__animated animate__fadeInUp animate__delay-1s">Керуйте своїми оголошеннями про роботу та знаходьте кращих кандидатів</p>
            </div>            <!-- Statistics Section -->
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number"><?= count($vacancies ?? []) ?></div>
                    <div class="stat-label">Усього вакансій</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($vacancies ?? [], fn($v) => $v['is_active'])) ?></div>
                    <div class="stat-label">Активних</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= array_sum(array_column($vacancies ?? [], 'views')) ?></div>
                    <div class="stat-label">Усього переглядів</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($vacancies ?? [], fn($v) => strtotime($v['created_at']) > strtotime('-30 days'))) ?></div>
                    <div class="stat-label">За місяць</div>
                </div>
            </div>
            
            <!-- Alerts -->
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    ✅ <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <!-- Actions Header -->
            <div class="actions-header">                <a href="/frontend/vacancy_create.php" class="btn btn-primary">
                    ➕ Створити нову вакансію
                </a>
            </div>            <!-- Vacancy Cards -->
            <?php if (!empty($vacancies)): ?>
                <?php foreach ($vacancies as $vacancy): ?>
                    <div class="vacancy-card">
                        <div class="vacancy-header">
                            <a href="vacancy_detail.php?id=<?= htmlspecialchars($vacancy['id']) ?>" class="vacancy-title">
                                <?= htmlspecialchars($vacancy['title']) ?>
                            </a>
                            
                            <div class="vacancy-actions">                                <span class="status <?= $vacancy['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $vacancy['is_active'] ? '✅ Активна' : '⏸️ Неактивна' ?>
                                </span>
                                
                                <button onclick="toggleVacancyStatus(<?= $vacancy['id'] ?>, <?= $vacancy['is_active'] ? 'false' : 'true' ?>)" 
                                        class="btn btn-sm <?= $vacancy['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                    <?= $vacancy['is_active'] ? '⏸️ Деактивувати' : '▶️ Активувати' ?>
                                </button>
                                
                                <a href="vacancy_edit.php?id=<?= htmlspecialchars($vacancy['id']) ?>" class="btn btn-sm btn-secondary">
                                    ✏️ Редагувати
                                </a>
                                
                                <form method="post" style="display: inline;" onsubmit="return confirm('Ви впевнені, що хочете видалити цю вакансію?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="vacancy_id" value="<?= htmlspecialchars($vacancy['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Видалити</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="vacancy-meta">
                            <?php if (!empty($vacancy['company'])): ?>
                                <div class="meta-item">
                                    <span>🏢</span>
                                    <span><?= htmlspecialchars($vacancy['company']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($vacancy['location'])): ?>
                                <div class="meta-item">
                                    <span>📍</span>
                                    <span><?= htmlspecialchars($vacancy['location']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($vacancy['employment_type'])): ?>
                                <div class="meta-item">
                                    <span>⏰</span>
                                    <span><?= htmlspecialchars($vacancy['employment_type']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($vacancy['salary'])): ?>
                            <div class="salary">
                                <span>💰</span>
                                <span><?= htmlspecialchars($vacancy['salary']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="vacancy-description">
                            <?php 
                            $description = $vacancy['clean_description'] ?? $vacancy['description'];
                            echo htmlspecialchars(mb_strlen($description) > 200 ? mb_substr($description, 0, 200) . '...' : $description);
                            ?>
                        </div>                          <div class="vacancy-stats">
                            <div class="stat-item">
                                <span>📅</span>
                                <span>Створена: <strong><?= date('d.m.Y', strtotime($vacancy['created_at'])) ?></strong></span>
                            </div>
                            <?php if (!empty($vacancy['updated_at']) && $vacancy['updated_at'] !== $vacancy['created_at']): ?>
                                <div class="stat-item">
                                    <span>🔄</span>
                                    <span>Оновлена: <strong><?= date('d.m.Y', strtotime($vacancy['updated_at'])) ?></strong></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <h3>У вас поки немає вакансій</h3>
                    <p>Створіть свою першу вакансію, щоб знайти підходящих кандидатів для вашої компанії</p>
                    <a href="/frontend/vacancy_create.php" class="btn btn-primary">➕ Створити першу вакансію</a>
                </div>
            <?php endif; ?>
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
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.querySelector('#theme-toggle i');
            if (icon) {
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }
        }
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('active');
        }
        function toggleVacancyStatus(vacancyId, newStatus) {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '⏳ Обробка...';
            button.disabled = true;
            
            fetch('../backend/controllers/VacancyStatusController.php?action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `vacancy_id=${vacancyId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Помилка: ' + (data.error || 'Невідома помилка'));
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Помилка з\'єднання');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }
            document.addEventListener('click', function(event) {
                const navMenu = document.querySelector('.nav-menu');
                const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
                
                if (navMenu && mobileMenuBtn && !navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    navMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
