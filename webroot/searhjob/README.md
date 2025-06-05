# SearchJob - Система поиска работы

Веб-приложение для поиска работы, разработанное с использованием архитектуры MVC.

## Структура проекта

```
searhjob/
├── backend/                    # Серверная часть (API)
│   ├── config/                 # Конфигурационные файлы
│   │   ├── db.php             # Настройки базы данных
│   │   └── logging.php        # Настройки логирования
│   ├── controllers/           # API контроллеры
│   │   ├── ApiController.php
│   │   ├── ProfileController.php
│   │   ├── VacancyApiController.php
│   │   └── XmlApiController.php
│   ├── models/                # Модели данных
│   │   ├── User.php
│   │   ├── Vacancy.php
│   │   └── JobApplication.php
│   ├── utils/                 # Утилиты и вспомогательные классы
│   │   ├── Logger.php         # Система логирования
│   │   ├── init_database.php  # Инициализация БД
│   │   └── create_test_user.php
│   ├── views/                 # Backend представления
│   ├── logs/                  # Файлы логов
│   └── xml/                   # XML файлы пользователей
├── frontend/                  # Клиентская часть
│   ├── controllers/           # Frontend контроллеры
│   ├── models/                # Клиентские модели
│   ├── views/                 # Представления (шаблоны)
│   ├── assets/                # Статические ресурсы
│   │   ├── style.css
│   │   └── images/
│   ├── utils/                 # Утилиты frontend
│   │   ├── admin/             # Административные инструменты
│   │   │   └── manage_users.php
│   │   ├── tests/             # Тестовые и отладочные файлы
│   │   │   ├── global_system_test.php
│   │   │   ├── test_api.php
│   │   │   ├── debug_session.php
│   │   │   └── test_profile_api.html
│   │   ├── edit_profile_backup.php
│   │   └── InternalApiClient.php
│   └── *.php                  # Основные страницы приложения
└── index.php                 # Главная страница
```

## Основные функции

- Регистрация и авторизация пользователей
- Создание и управление вакансиями (для работодателей)
- Поиск и просмотр вакансий (для соискателей)
- Подача заявок на вакансии
- Управление профилем пользователя
- API для работы с данными
- XML хранение данных пользователей

## Роли пользователей

1. Соискатель (job_seeker) - ищет работу, подает заявки
2. Работодатель (employer) - создает вакансии, просматривает заявки
3. Администратор (admin) - управляет системой

## Технологии

- Backend: PHP, MySQL
- Frontend: HTML, CSS, JavaScript, PHP
- Архитектура: MVC
- API: REST API с JSON/XML форматами
- Логирование: Централизованная система логов

## Установка и настройка

1. Настройте базу данных в `backend/config/db.php`
2. Запустите инициализацию БД: `backend/utils/init_database.php`
3. Настройте веб-сервер для работы с папкой проекта
4. Доступ к тестам: `frontend/utils/tests/global_system_test.php`

## Тестовые данные

После инициализации БД доступны тестовые аккаунты:
- Администратор: admin / admin123
- Работодатель: employer1 / password123
- Соискатель: jobseeker1 / password123

## Логирование

Система использует централизованное логирование:
- Логи сохраняются в `backend/logs/`
- Разные уровни: debug, info, error, api, database
- Автоматическая очистка старых логов

## Доступ к инструментам после реорганизации

### Тестирование системы
- Глобальный системный тест: `/frontend/utils/tests/global_system_test.php`
- API тестирование: `/frontend/utils/tests/test_api.php`
- Отладка сессий: `/frontend/utils/tests/debug_session.php`
- Проверка путей: `/frontend/utils/tests/check_paths.php`

### Административные инструменты
- Управление пользователями: `/frontend/utils/admin/manage_users.php`

### Резервные копии
- Backup редактора профиля: `/frontend/utils/edit_profile_backup.php`

⚠️ Важно: После перемещения файлов в новые папки все относительные пути были обновлены для корректной работы.
