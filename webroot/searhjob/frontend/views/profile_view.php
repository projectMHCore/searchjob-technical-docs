<?php
// View: отображение профиля пользователя
?><!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профіль користувача - SearchJob</title>
    <meta name="description" content="Керуйте своїм профілем на SearchJob">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        /* Professional Modern Styles */
        :root {
            --primary-color: #eaa850;
            --primary-dark: #d4922a;
            --secondary-color: #667eea;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --border-color: #e1e8ed;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --shadow-light: 0 4px 15px rgba(0,0,0,0.08);
            --shadow-medium: 0 8px 30px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --bg-primary: #1e293b;
            --bg-secondary: #334155;
            --border-color: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }        body {
            background-color: var(--background-primary); /* Заменено на переменную */
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            transition: var(--transition);
        }

        body[data-theme="dark"] {
            background-color: var(--dark-bg-primary); /* Заменено на переменную */
            color: var(--dark-text-primary);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95); /* Приведено к стандарту */
            backdrop-filter: blur(10px); /* Приведено к стандарту */
            border-bottom: none; /* Убрана граница */
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1); /* Приведено к стандарту */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: var(--transition);
        }

        body[data-theme="dark"] .navbar {
            background: var(--dark-nav-bg); /* Приведено к стандарту */
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem; /* Приведено к стандарту */
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
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
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
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-light);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            transform: scale(1.1);
        }

        body[data-theme="dark"] .theme-toggle {
            border-color: var(--dark-theme-toggle-border);
            color: var(--dark-text-primary);
        }

        body[data-theme="dark"] .theme-toggle:hover {
            border-color: var(--primary-light);
            color: var(--primary-light);
        }

        .main-content {
            padding-top: 100px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.6s ease-out;
            background: var(--bg-primary);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-light);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }        .profile-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-color);
            animation: fadeInUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .profile-header {
            text-align: center;
            padding: 2rem 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin: 0 auto 1rem auto;
            box-shadow: 0 8px 25px rgba(234, 168, 80, 0.3);
            animation: zoomIn 0.6s ease-out 0.3s both;
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }

        .profile-role {
            font-size: 1.1rem;
            color: var(--text-secondary);
            font-weight: 500;
            animation: fadeInUp 0.6s ease-out 0.7s both;
        }.role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
            border: 2px solid transparent;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .info-item {
            background: var(--bg-secondary);
            padding: 1.25rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            color: var(--primary-color);
            width: 16px;
        }

        .info-value {
            font-size: 1.05rem;
            color: var(--text-primary);
            font-weight: 500;
            line-height: 1.5;
        }

        .info-value a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        .salary-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .profile-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .action-card {
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--primary-color);
        }

        .action-card:hover::before {
            transform: scaleY(1);
        }

        .action-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-card h4 i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .action-card p {
            color: var(--text-secondary);
            line-height: 1.5;
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

        .error-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .profile-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .page-header {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .info-item {
            animation: fadeInUp 0.6s ease-out;
        }

        .info-item:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .info-item:nth-child(even) {
            animation-delay: 0.2s;
        }

        .action-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .action-card:nth-child(1) { animation-delay: 0.1s; }
        .action-card:nth-child(2) { animation-delay: 0.2s; }
        .action-card:nth-child(3) { animation-delay: 0.3s; }
        .action-card:nth-child(4) { animation-delay: 0.4s; }

        [data-theme="dark"] .navbar {
            background: rgba(30, 41, 59, 0.95);
        }

        [data-theme="dark"] .profile-card,
        [data-theme="dark"] .action-card {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .info-item {
            background: var(--bg-secondary);
            border-color: var(--border-color);
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .page-title {
                font-size: 2rem;
            }

            .profile-info {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body data-theme="light">
    <!-- Navigation -->
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
                </a></li>                <li><a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i> Компанії
                </a></li>
            </ul>
              <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                
                <a href="/frontend/profile.php" class="nav-link active">
                    <i class="fas fa-user"></i> Профіль
                </a>
                <a href="/frontend/logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Вийти
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-circle" style="color: var(--primary-color);"></i>
                    Профіль користувача
                </h1>
            </div>
              <?php if ($profile): ?>
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?= strtoupper(substr($profile['login'] ?? '', 0, 2)) ?>
                        </div>
                        <h2 class="profile-name"><?= htmlspecialchars($profile['login'] ?? '') ?></h2>
                        <div class="role-badge">
                            <i class="fas fa-<?= ($profile['role'] ?? '') == 'employer' ? 'briefcase' : 'user' ?>"></i>
                            <?= ($profile['role'] ?? '') == 'employer' ? 'Роботодавець' : 'Шукач роботи' ?>
                        </div>
                    </div>
                    
                    <div class="profile-info">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i> Email
                            </div>
                            <div class="info-value"><?= htmlspecialchars($profile['email'] ?? '') ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i> ID користувача
                            </div>
                            <div class="info-value"><?= htmlspecialchars($profile['id'] ?? '') ?></div>
                        </div>
                        
                        <!-- Поля для шукачів роботи -->
                        <?php if (($profile['role'] ?? '') == 'job_seeker'): ?>
                            <?php if (!empty($profile['first_name']) || !empty($profile['last_name'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user"></i> Ім'я
                                </div>
                                <div class="info-value"><?= htmlspecialchars(trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''))) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['phone'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone"></i> Телефон
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['phone']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['birth_date'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-birthday-cake"></i> Дата народження
                                </div>
                                <div class="info-value"><?= date('d.m.Y', strtotime($profile['birth_date'])) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['city'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i> Місто
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['city']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['experience_years'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-briefcase"></i> Досвід роботи
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['experience_years']) ?> років</div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['education'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-graduation-cap"></i> Освіта
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['education']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['skills'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tools"></i> Навички
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['skills']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['about_me'])): ?>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <div class="info-label">
                                    <i class="fas fa-file-alt"></i> Про себе
                                </div>
                                <div class="info-value"><?= nl2br(htmlspecialchars($profile['about_me'])) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['salary_expectation'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-money-bill-wave"></i> Бажана зарплата
                                </div>
                                <div class="info-value salary-value"><?= number_format($profile['salary_expectation'], 0, ',', ' ') ?> грн</div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Поля для роботодавців -->
                        <?php if (($profile['role'] ?? '') == 'employer'): ?>
                            <?php if (!empty($profile['company_name'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-building"></i> Компанія
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['company_name']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['company_description'])): ?>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <div class="info-label">
                                    <i class="fas fa-file-alt"></i> Опис компанії
                                </div>
                                <div class="info-value"><?= nl2br(htmlspecialchars($profile['company_description'])) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['company_address'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i> Адреса
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['company_address']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['company_website'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-globe"></i> Веб-сайт
                                </div>
                                <div class="info-value">
                                    <a href="<?= htmlspecialchars($profile['company_website']) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($profile['company_website']) ?>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['company_size'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-users"></i> Розмір компанії
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['company_size']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['company_industry'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-industry"></i> Галузь
                                </div>
                                <div class="info-value"><?= htmlspecialchars($profile['company_industry']) ?></div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i> Дата реєстрації
                            </div>
                            <div class="info-value"><?= date('d.m.Y', strtotime($profile['created'] ?? '')) ?></div>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <?php if (($profile['role'] ?? '') == 'employer'): ?>
                            <a href="/frontend/vacancy_create.php" class="action-card">
                                <h4><i class="fas fa-plus-circle"></i> Створити вакансію</h4>
                                <p>Розмістіть нову вакансію для пошуку співробітників</p>
                            </a>
                            
                            <a href="/frontend/my_vacancies.php" class="action-card">
                                <h4><i class="fas fa-list-alt"></i> Мої вакансії</h4>
                                <p>Переглядайте та керуйте своїми вакансіями</p>
                            </a>
                            
                            <a href="/frontend/manage_applications.php" class="action-card">
                                <h4><i class="fas fa-users"></i> Заявки кандидатів</h4>
                                <p>Переглядайте та керуйте заявками на ваші вакансії</p>
                            </a>
                            
                            <a href="edit_profile.php?type=personal" class="action-card">
                                <h4><i class="fas fa-user-edit"></i> Редагувати профіль</h4>
                                <p>Змінити особисті дані профілю</p>
                            </a>
                            
                            <a href="edit_profile.php?type=company" class="action-card">
                                <h4><i class="fas fa-building"></i> Редагувати компанію</h4>
                                <p>Змінити інформацію про компанію</p>
                            </a>
                        <?php else: ?>
                            <a href="/frontend/vacancy_list.php" class="action-card">
                                <h4><i class="fas fa-search"></i> Пошук роботи</h4>
                                <p>Знайдіть підходящу вакансію</p>
                            </a>
                            
                            <a href="/frontend/my_applications.php" class="action-card">
                                <h4><i class="fas fa-paper-plane"></i> Мої відгуки</h4>
                                <p>Переглядайте свої відгуки на вакансії</p>
                            </a>
                            
                            <a href="/frontend/edit_profile.php" class="action-card">
                                <h4><i class="fas fa-cog"></i> Редагувати профіль</h4>
                                <p>Змінити дані профілю</p>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-card">
                    <h3><i class="fas fa-exclamation-triangle"></i> Профіль не знайдено</h3>
                    <p>Не вдалося завантажити дані профілю.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

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
        });        // Smooth animations on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', function() {
            // Observe cards for scroll animations
            const cards = document.querySelectorAll('.action-card, .info-item');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.animationDelay = `${index * 0.1}s`;
                observer.observe(card);
            });

            // Enhanced hover effects for action cards
            document.querySelectorAll('.action-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
