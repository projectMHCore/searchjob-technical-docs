<?php
/**
 * Единый скрипт инициализации базы данных для проекта Job Search
 * Создает все необходимые таблицы и добавляет тестовые данные
 */

$config = require __DIR__ . '/../config/db.php';

try {
    // Подключение к базе данных
    $db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
    
    if ($db->connect_error) {
        die('❌ Ошибка подключения к БД: ' . $db->connect_error);
    }
    
    echo "<h1>🚀 Инициализация базы данных Job Search</h1>";
    echo "<p>Создание таблиц и добавление тестовых данных...</p>";
    
    // 1. Создание таблицы пользователей
    echo "<h2>1. Создание таблицы users</h2>";
    $createUsersTable = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        login VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        role ENUM('job_seeker', 'employer', 'admin') DEFAULT 'job_seeker',
        company_name VARCHAR(255) NULL,
        first_name VARCHAR(100) NULL,
        last_name VARCHAR(100) NULL,
        phone VARCHAR(20) NULL,
        birth_date DATE NULL,
        city VARCHAR(100) NULL,
        experience_years INT NULL,
        education TEXT NULL,
        skills TEXT NULL,
        about_me TEXT NULL,
        salary_expectation VARCHAR(100) NULL,
        company_description TEXT NULL,
        company_address VARCHAR(255) NULL,
        company_website VARCHAR(255) NULL,
        company_size VARCHAR(50) NULL,
        company_industry VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($db->query($createUsersTable)) {
        echo "✅ Таблица users создана успешно<br>";
    } else {
        echo "❌ Ошибка создания таблицы users: " . $db->error . "<br>";
    }
    
    // 2. Создание таблицы токенов
    echo "<h2>2. Создание таблицы user_tokens</h2>";
    $createTokensTable = "
    CREATE TABLE IF NOT EXISTS user_tokens (
        user_id INT NOT NULL,
        token VARCHAR(64) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    if ($db->query($createTokensTable)) {
        echo "✅ Таблица user_tokens создана успешно<br>";
    } else {
        echo "❌ Ошибка создания таблицы user_tokens: " . $db->error . "<br>";
    }
    
    // 3. Создание таблицы вакансий
    echo "<h2>3. Создание таблицы vacancies</h2>";
    $createVacanciesTable = "
    CREATE TABLE IF NOT EXISTS vacancies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employer_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        salary VARCHAR(64) NULL,
        location VARCHAR(100) NULL,
        company VARCHAR(100) NULL,
        requirements TEXT NULL,
        employment_type VARCHAR(50) NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    if ($db->query($createVacanciesTable)) {
        echo "✅ Таблица vacancies создана успешно<br>";
    } else {
        echo "❌ Ошибка создания таблицы vacancies: " . $db->error . "<br>";
    }
    
    // 4. Создание таблицы откликов
    echo "<h2>4. Создание таблицы applications</h2>";
    $createApplicationsTable = "
    CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vacancy_id INT NOT NULL,
        user_id INT NOT NULL,
        cover_letter TEXT NULL,
        status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (vacancy_id) REFERENCES vacancies(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_application (vacancy_id, user_id)
    )";
    
    if ($db->query($createApplicationsTable)) {
        echo "✅ Таблица applications создана успешно<br>";
    } else {
        echo "❌ Ошибка создания таблицы applications: " . $db->error . "<br>";
    }
    
    // 5. Добавление тестовых пользователей
    echo "<h2>5. Добавление тестовых пользователей</h2>";
    
    // Проверяем, есть ли уже пользователи
    $checkUsers = $db->query("SELECT COUNT(*) as count FROM users");
    $userCount = $checkUsers->fetch_assoc()['count'];
    
    if ($userCount == 0) {
        $testUsers = [
            [
                'login' => 'admin',
                'email' => 'admin@jobsearch.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'first_name' => 'Администратор',
                'last_name' => 'Системы'
            ],
            [
                'login' => 'employer1',
                'email' => 'employer@techcorp.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'employer',
                'company_name' => 'TechCorp',
                'first_name' => 'Иван',
                'last_name' => 'Иванов',
                'company_description' => 'Ведущая IT-компания, специализирующаяся на разработке веб-приложений',
                'company_address' => 'Москва, ул. Тверская, 1',
                'company_website' => 'https://techcorp.com',
                'company_size' => '50-100',
                'company_industry' => 'Информационные технологии'
            ],
            [
                'login' => 'jobseeker1',
                'email' => 'jobseeker@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'job_seeker',
                'first_name' => 'Петр',
                'last_name' => 'Петров',
                'city' => 'Москва',
                'experience_years' => 3,
                'education' => 'Высшее техническое образование',
                'skills' => 'PHP, JavaScript, MySQL, HTML, CSS',
                'about_me' => 'Опытный веб-разработчик с 3-летним стажем работы',
                'salary_expectation' => '80000-120000'
            ]
        ];
        
        foreach ($testUsers as $user) {
            $fields = implode(', ', array_keys($user));
            $placeholders = ':' . implode(', :', array_keys($user));
            
            $stmt = $db->prepare("INSERT INTO users ($fields) VALUES ($placeholders)");
            
            foreach ($user as $key => $value) {
                $stmt->bind_param($key === 'password' ? 's' : 's', $value);
            }
            
            if ($stmt->execute()) {
                echo "✅ Пользователь {$user['login']} создан<br>";
            } else {
                echo "❌ Ошибка создания пользователя {$user['login']}: " . $stmt->error . "<br>";
            }
        }
    } else {
        echo "ℹ️ Пользователи уже существуют в базе данных ($userCount пользователей)<br>";
    }
    
    // 6. Добавление тестовых вакансий
    echo "<h2>6. Добавление тестовых вакансий</h2>";
    
    // Получаем ID работодателя
    $employerResult = $db->query("SELECT id FROM users WHERE role = 'employer' LIMIT 1");
    if ($employerResult && $employerResult->num_rows > 0) {
        $employerId = $employerResult->fetch_assoc()['id'];
        
        // Проверяем, есть ли уже вакансии
        $checkVacancies = $db->query("SELECT COUNT(*) as count FROM vacancies");
        $vacancyCount = $checkVacancies->fetch_assoc()['count'];
        
        if ($vacancyCount == 0) {
            $testVacancies = [
                [
                    'title' => 'PHP разработчик',
                    'description' => 'Требуется опытный PHP разработчик для работы с веб-приложениями.',
                    'salary' => '80000-120000 руб',
                    'location' => 'Москва',
                    'company' => 'TechCorp',
                    'requirements' => 'PHP, MySQL, JavaScript, опыт от 3 лет',
                    'employment_type' => 'Полная занятость'
                ],
                [
                    'title' => 'Frontend разработчик',
                    'description' => 'Ищем талантливого frontend разработчика для создания современных веб-интерфейсов.',
                    'salary' => '70000-100000 руб',
                    'location' => 'Санкт-Петербург',
                    'company' => 'WebStudio',
                    'requirements' => 'React, TypeScript, CSS3, HTML5',
                    'employment_type' => 'Удаленная работа'
                ],
                [
                    'title' => 'Backend разработчик',
                    'description' => 'Требуется backend разработчик для работы с высоконагруженными системами.',
                    'salary' => '90000-140000 руб',
                    'location' => 'Новосибирск',
                    'company' => 'DataSystems',
                    'requirements' => 'Python, Django, PostgreSQL, Redis',
                    'employment_type' => 'Гибкий график'
                ]
            ];
            
            foreach ($testVacancies as $vacancy) {
                $stmt = $db->prepare("
                    INSERT INTO vacancies (employer_id, title, description, salary, location, company, requirements, employment_type) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->bind_param('isssssss', 
                    $employerId,
                    $vacancy['title'],
                    $vacancy['description'],
                    $vacancy['salary'],
                    $vacancy['location'],
                    $vacancy['company'],
                    $vacancy['requirements'],
                    $vacancy['employment_type']
                );
                
                if ($stmt->execute()) {
                    echo "✅ Вакансия '{$vacancy['title']}' создана<br>";
                } else {
                    echo "❌ Ошибка создания вакансии '{$vacancy['title']}': " . $stmt->error . "<br>";
                }
            }
        } else {
            echo "ℹ️ Вакансии уже существуют в базе данных ($vacancyCount вакансий)<br>";
        }
    } else {
        echo "⚠️ Работодатель не найден, вакансии не созданы<br>";
    }
    
    // 7. Финальная статистика
    echo "<h2>7. Статистика базы данных</h2>";
    
    $stats = [
        'users' => $db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
        'employers' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'employer'")->fetch_assoc()['count'],
        'job_seekers' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'job_seeker'")->fetch_assoc()['count'],
        'vacancies' => $db->query("SELECT COUNT(*) as count FROM vacancies")->fetch_assoc()['count'],
        'active_vacancies' => $db->query("SELECT COUNT(*) as count FROM vacancies WHERE is_active = 1")->fetch_assoc()['count'],
        'applications' => $db->query("SELECT COUNT(*) as count FROM applications")->fetch_assoc()['count']
    ];
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Категория</th><th>Количество</th></tr>";
    echo "<tr><td>👥 Всего пользователей</td><td>{$stats['users']}</td></tr>";
    echo "<tr><td>🏢 Работодателей</td><td>{$stats['employers']}</td></tr>";
    echo "<tr><td>👤 Соискателей</td><td>{$stats['job_seekers']}</td></tr>";
    echo "<tr><td>💼 Всего вакансий</td><td>{$stats['vacancies']}</td></tr>";
    echo "<tr><td>🟢 Активных вакансий</td><td>{$stats['active_vacancies']}</td></tr>";
    echo "<tr><td>📝 Откликов</td><td>{$stats['applications']}</td></tr>";
    echo "</table>";
    
    echo "<h2>8. Тестовые учетные данные</h2>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Роль</th><th>Логин</th><th>Пароль</th><th>Email</th></tr>";
    echo "<tr><td>👑 Администратор</td><td>admin</td><td>admin123</td><td>admin@jobsearch.com</td></tr>";
    echo "<tr><td>🏢 Работодатель</td><td>employer1</td><td>password123</td><td>employer@techcorp.com</td></tr>";
    echo "<tr><td>👤 Соискатель</td><td>jobseeker1</td><td>password123</td><td>jobseeker@example.com</td></tr>";
    echo "</table>";
    
    echo "<h2>9. Навигация</h2>";
    echo "<p>";
    echo "<a href='index.php'>🏠 Главная страница</a> | ";
    echo "<a href='login.php'>🔐 Войти в систему</a> | ";
    echo "<a href='register.php'>📝 Регистрация</a> | ";
    echo "<a href='vacancy_list.php'>💼 Список вакансий</a>";
    echo "</p>";
    
    echo "<p style='color: green; font-weight: bold;'>🎉 Инициализация базы данных завершена успешно!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Ошибка: " . $e->getMessage() . "</p>";
}
?>
