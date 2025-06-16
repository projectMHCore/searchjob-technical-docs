<?php
require_once __DIR__ . '/../models/VacancyModel.php';
session_start();

class VacancyController {
    private $vacancyModel;
    
    public function __construct() {
        $this->vacancyModel = new VacancyModel();
    }
      /**
     * Отображение списка вакансий
     */
    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'location' => $_GET['location'] ?? '',
            'employment_type' => $_GET['employment_type'] ?? '',
            'employer' => $_GET['employer'] ?? ''
        ];
        
        // Получаем данные через модель
        $result = $this->vacancyModel->getAllVacancies($filters);
        
        $data = [
            'vacancies' => $result['success'] ? ($result['vacancies'] ?? []) : [],
            'filters' => $filters,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Ошибка загрузки вакансий')
        ];
        $this->render('vacancy_list', $data);
    }
      /**
     * Отображение конкретной вакансии
     */
    public function show($id) {
        $result = $this->vacancyModel->getVacancy($id);
        
        $canApply = false;
        $hasApplied = false;
        
    
        if ($result['success'] && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'job_seeker') {
            $canApply = true;
        }
        
        $data = [
            'vacancy' => $result['success'] ? ($result['vacancy'] ?? null) : null,
            'canApply' => $canApply,
            'hasApplied' => $hasApplied,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Вакансия не найдена')
        ];
        
        $this->render('vacancy_detail', $data);
    }
      /**
     * Форма создания вакансии
     */
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'salary' => trim($_POST['salary'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'employment_type' => trim($_POST['employment_type'] ?? ''),
                'company' => trim($_POST['company'] ?? '')
            ];
            
            $result = $this->vacancyModel->createVacancy($data, $_SESSION['token']);
            
            if ($result['success']) {
                header('Location: my_vacancies.php?success=created');
                exit;
            } else {
                $viewData = [
                    'vacancy' => (object)$data,
                    'error' => $result['error'] ?? 'Ошибка создания вакансии'
                ];
                $this->render('vacancy_form', $viewData);
            }        } else {
            $data = ['vacancy' => null, 'error' => null];
            $this->render('vacancy_form', $data);
        }
    }
    
    /**
     * Сохранение новой вакансии
     */
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'salary' => trim($_POST['salary'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'employment_type' => trim($_POST['employment_type'] ?? ''),
                'company' => trim($_POST['company'] ?? '')
            ];
            
            $result = $this->vacancyModel->createVacancy($data, $_SESSION['token']);
            
            if ($result['success']) {
                header('Location: my_vacancies.php?success=created');
                exit;
            } else {
                $viewData = [
                    'vacancy' => (object)$data,
                    'error' => $result['error'] ?? 'Ошибка создания вакансии'
                ];
                $this->render('vacancy_form', $viewData);
            }
        } else {
            header('Location: vacancy_create.php');
            exit;
        }
    }
      /**
     * Форма редактирования вакансии
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'salary' => trim($_POST['salary'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'employment_type' => trim($_POST['employment_type'] ?? ''),
                'company' => trim($_POST['company'] ?? '')
            ];
            
            $result = $this->vacancyModel->updateVacancy($id, $data, $_SESSION['token']);
            
            if ($result['success']) {
                header('Location: my_vacancies.php?success=updated');
                exit;
            } else {
                $viewData = [
                    'vacancy' => (object)$data,
                    'error' => $result['error'] ?? 'Ошибка обновления вакансии'
                ];
                $this->render('vacancy_form', $viewData);
                return;
            }
        }
        $result = $this->vacancyModel->getVacancy($id);
        
        $vacancy = null;
        if ($result['success'] && isset($result['vacancy'])) {
            $vacancy = (object)$result['vacancy'];
        }
        
        $data = [
            'vacancy' => $vacancy,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Вакансия не найдена')
        ];
        
        $this->render('vacancy_form', $data);
    }
    
    /**
     * Обновление вакансии
     */
    public function update($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'requirements' => trim($_POST['requirements'] ?? ''),
                'salary' => trim($_POST['salary'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'employment_type' => trim($_POST['employment_type'] ?? ''),
                'company' => trim($_POST['company'] ?? '')
            ];
            
            $result = $this->vacancyModel->updateVacancy($id, $data, $_SESSION['token']);
            
            if ($result['success']) {
                header('Location: my_vacancies.php?success=updated');
                exit;
            } else {
                $viewData = [
                    'vacancy' => (object)$data,
                    'error' => $result['error'] ?? 'Ошибка обновления вакансии'
                ];
                $this->render('vacancy_form', $viewData);
            }
        } else {
            header('Location: vacancy_edit.php?id=' . $id);
            exit;
        }
    }
    
    /**
     * Удаление вакансии
     */
    public function delete($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        
        $result = $this->vacancyModel->deleteVacancy($id, $_SESSION['token']);
        
        if ($result['success']) {
            header('Location: my_vacancies.php?success=deleted');
        } else {
            header('Location: my_vacancies.php?error=' . urlencode($result['error'] ?? 'Ошибка удаления'));
        }
        exit;
    }
    
    /**
     * Мои вакансии (для работодателя)
     */
    public function myVacancies() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        
        $result = $this->vacancyModel->getEmployerVacancies($_SESSION['token']);
        
        $data = [
            'vacancies' => $result['success'] ? ($result['vacancies'] ?? []) : [],
            'error' => $result['success'] ? null : ($result['error'] ?? 'Ошибка загрузки вакансий'),
            'success' => $_GET['success'] ?? null
        ];
        
        $this->render('my_vacancies', $data);
    }
    
    /**
     * Рендеринг представления
     */    private function render($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}_view_new.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            $fallbackFile = __DIR__ . "/../views/{$view}_view.php";
            if (file_exists($fallbackFile)) {
                include $fallbackFile;
            } else {
                echo "Представление {$view} не найдено";
            }
        }
    }
}
