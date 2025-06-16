# SearchJob - Система пошуку роботи

Веб-додаток для пошуку роботи, розроблений з використанням архітектури MVC.

## Структура проекту

```
searhjob/
├── backend/                    # Серверна частина (API)
│   ├── config/                 # Конфігураційні файли
│   │   ├── db.php             # Налаштування бази даних
│   │   └── logging.php        # Налаштування логування
│   ├── controllers/           # API контролери
│   │   ├── ApiController.php
│   │   ├── ProfileController.php
│   │   ├── VacancyApiController.php
│   │   └── XmlApiController.php
│   ├── models/                # Моделі даних
│   │   ├── User.php
│   │   ├── Vacancy.php
│   │   └── JobApplication.php
│   ├── utils/                 # Утиліти та допоміжні класи
│   │   ├── Logger.php         # Система логування
│   │   ├── init_database.php  # Ініціалізація БД
│   │   └── create_test_user.php
│   ├── views/                 # Backend представлення
│   ├── logs/                  # Файли логів
│   └── xml/                   # XML файли користувачів
├── frontend/                  # Клієнтська частина
│   ├── controllers/           # Frontend контролери
│   ├── models/                # Клієнтські моделі
│   ├── views/                 # Представлення (шаблони)
│   ├── assets/                # Статичні ресурси
│   │   ├── style.css
│   │   └── images/
│   ├── utils/                 # Утиліти frontend
│   │   ├── admin/             # Адміністративні інструменти
│   │   │   └── manage_users.php
│   │   ├── tests/             # Тестові та відлагоджувальні файли
│   │   │   ├── global_system_test.php
│   │   │   ├── test_api.php
│   │   │   ├── debug_session.php
│   │   │   └── test_profile_api.html
│   │   ├── edit_profile_backup.php
│   │   └── InternalApiClient.php
│   └── *.php                  # Основні сторінки додатку
└── index.php                 # Головна сторінка
```

## Основні функції

- Реєстрація та авторизація користувачів
- Створення та управління вакансіями (для роботодавців)
- Пошук і перегляд вакансій (для здобувачів)
- Подача заявок на вакансії
- Управління профілем користувача
- API для роботи з даними
- XML зберігання даних користувачів
