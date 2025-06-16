<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація - SearchJob</title>
    <meta name="description" content="Зареєструйтесь у SearchJob та почніть пошук роботи мрії">
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
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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
        }
        
        .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: translateY(-2px);
        }
        
        /* Theme Toggle */
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
        
        /* Main Content */
        .main-content {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 2rem 2rem;
            position: relative;
        }
        
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/frontend/assets/images/head_background.jpg') center center/cover no-repeat;
            opacity: 0.1;
            z-index: -1;
        }
        
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: #2c3e50;
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #eaa850;
            box-shadow: 0 0 0 4px rgba(234, 168, 80, 0.1);
            transform: scale(1.02);
        }
        
        .form-select {
            cursor: pointer;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }
        
        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #16a34a;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.5s ease-out;
        }
        
        .form-links {
            text-align: center;
            margin-top: 2rem;
        }
        
        .form-links p {
            margin-bottom: 0.5rem;
            color: #64748b;
        }
        
        .form-links a {
            color: #eaa850;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .form-links a:hover {
            color: #d4922a;
            text-decoration: underline;
        }
        
        /* Hidden field animation */
        .company-field {
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s ease;
            opacity: 0;
        }
        
        .company-field.show {
            max-height: 100px;
            opacity: 1;
            margin-bottom: 1.5rem;
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
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        /* Theme variables */
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
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }
        
        [data-theme="dark"] .auth-container {
            background: rgba(45, 55, 72, 0.95);
        }
        
        [data-theme="dark"] .form-input,
        [data-theme="dark"] .form-select {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .auth-header h1 {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-group label {
            color: var(--text-primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .auth-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .auth-header h1 {
                font-size: 2rem;
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
            </div>
            
            <div class="nav-auth">
                <button id="theme-toggle" class="theme-toggle" title="Переключити тему">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="/frontend/login.php" class="btn-secondary">Увійти</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="auth-container">
            <div class="auth-header">
                <h1>Реєстрація</h1>
                <p>Створіть акаунт SearchJob та знайдіть роботу мрії</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    Реєстрація пройшла успішно! <a href="/frontend/login.php">Увійти в систему</a>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register_action.php">
                <div class="form-group">
                    <label for="login">
                        <i class="fas fa-user"></i>
                        Логін
                    </label>
                    <input type="text" id="login" name="login" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Пароль
                    </label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i>
                        Підтвердіть пароль
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="role">
                        <i class="fas fa-user-tag"></i>
                        Роль
                    </label>
                    <select id="role" name="role" class="form-select" required onchange="toggleCompanyField()">
                        <option value="">Оберіть роль</option>
                        <option value="job_seeker">Здобувач</option>
                        <option value="employer">Роботодавець</option>
                    </select>
                </div>
                
                <div class="form-group company-field" id="company-field">
                    <label for="company_name">
                        <i class="fas fa-building"></i>
                        Назва компанії
                    </label>
                    <input type="text" id="company_name" name="company_name" class="form-input">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-plus"></i>
                    Зареєструватися
                </button>
            </form>
            
            <div class="form-links">
                <p>Вже є акаунт? <a href="/frontend/login.php">Увійти</a></p>
                <p><a href="/frontend/index.php">На головну</a></p>
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
            const formInputs = document.querySelectorAll('.form-input, .form-select');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
            document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
        
        function toggleCompanyField() {
            const role = document.getElementById('role').value;
            const companyField = document.getElementById('company-field');
            const companyInput = document.getElementById('company_name');
            
            if (role === 'employer') {
                companyField.classList.add('show');
                companyInput.required = true;
            } else {
                companyField.classList.remove('show');
                companyInput.required = false;
                companyInput.value = '';
            }
        }
    </script>
</body>
</html>
