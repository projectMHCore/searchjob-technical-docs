# SearchJob - Технічна документація проекту

## 📋 Опис проекту
SearchJob - це веб-платформа для пошуку роботи, що дозволяє кандидатам шукати вакансії, а роботодавцям - розміщувати оголошення про роботу.

## 🏗️ Архітектура системи
Проект реалізований з використанням:
- **Backend**: PHP з REST API
- **Frontend**: PHP MVC архітектура
- **База даних**: MySQL
- **Серіалізація**: JSON (основна) + XML (альтернативна)
- **Логування**: JSON структуровані логи

## 📚 Лабораторні роботи

### 🔧 [Лабораторна робота 1: Технічні вимоги та інфраструктура](Lab1_TechnicalRequirements.md)
- Аналіз вимог до проекту
- Дослідження конкурентів
- Технічні специфікації
- Інфраструктура розгортання

### 🌐 [Лабораторна робота 2: Клієнт-серверна архітектура](Lab2_ClientServer_Architecture.md)

**UML діаграма взаємодії:**

```mermaid
sequenceDiagram
    participant C as Клієнт
    participant F as Frontend
    participant B as Backend API
    participant D as База даних
    participant L as Логування

    C->>F: HTTP запит
    F->>B: API виклик
    B->>L: Логування запиту
    B->>D: SQL запит
    D-->>B: Результат
    B->>L: Логування відповіді
    B-->>F: JSON відповідь
    F-->>C: HTML сторінка
```

### 🎨 [Лабораторна робота 3: Архітектура клієнтської частини](Lab3_ClientSide_Architecture.md)

**MVC Архітектура Frontend:**

```mermaid
graph TD
    A[User Browser] --> B[Controllers]
    B --> C[Models]
    B --> D[Views]
    C --> E[InternalApiClient]
    E --> F[Backend API]
    
    subgraph "Controllers"
        B1[LoginController]
        B2[VacancyController]
        B3[ProfileController]
    end
    
    subgraph "Models"
        C1[UserModel]
        C2[VacancyModel]
        C3[ApplicationModel]
    end
    
    subgraph "Views"
        D1[login_view.php]
        D2[vacancy_list_view.php]
        D3[profile_view.php]
    end
    
    B --> B1
    B --> B2
    B --> B3
    
    C --> C1
    C --> C2
    C --> C3
    
    D --> D1
    D --> D2
    D --> D3
```

## 📁 Структура проекту

```
project/
├── Lab1_TechnicalRequirements.md    # Технічні вимоги
├── Lab2_ClientServer_Architecture.md # Клієнт-серверна архітектура  
├── Lab3_ClientSide_Architecture.md   # Клієнтська архітектура
├── webroot/searhjob/                 # Основний код проекту
│   ├── backend/                      # Серверна частина
│   │   ├── controllers/              # API контролери
│   │   ├── models/                   # Моделі даних
│   │   ├── utils/                    # Утиліти та логування
│   │   └── logs/                     # Файли логів
│   └── frontend/                     # Клієнтська частина
│       ├── controllers/              # MVC контролери
│       ├── models/                   # MVC моделі
│       ├── views/                    # MVC представлення
│       └── assets/                   # Статичні ресурси
└── nginx/                            # Конфігурація веб-сервера
```
