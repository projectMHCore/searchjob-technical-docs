<?php
require_once __DIR__ . '/../models/ApplicationModel.php';
require_once __DIR__ . '/../models/VacancyModel.php';
session_start();

class ApplicationController {
    private $applicationModel;
    private $vacancyModel;
    
    public function __construct() {
        $this->applicationModel = new ApplicationModel();
        $this->vacancyModel = new VacancyModel();
    }  
    public function apply() {
        if (!isset($_SESSION['token']) || $_SESSION['user_role'] !== 'job_seeker') {
            header('Location: login.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vacancyId = intval($_POST['vacancy_id'] ?? 0);
            $coverLetter = trim($_POST['cover_letter'] ?? '');
            
            if ($vacancyId > 0) {
                $result = $this->applicationModel->createApplication([
                    'vacancy_id' => $vacancyId,
                    'cover_letter' => $coverLetter
                ], $_SESSION['token']);
                
                $data = [
                    'success' => $result['success'],
                    'message' => $result['success'] 
                        ? 'Заявка успешно отправлена! Ожидайте ответа от работодателя.' 
                        : ($result['error'] ?? 'Ошибка отправки заявки')
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Неверный ID вакансии'
                ];
            }
            
            $this->render('application_result', $data);
        } else {
            $vacancyId = intval($_GET['id'] ?? 0);
            
            if ($vacancyId <= 0) {
                header('Location: vacancy_list.php');
                exit;
            }
            
            $vacancyResult = $this->vacancyModel->getVacancy($vacancyId);
            
            if (!$vacancyResult['success'] || !isset($vacancyResult['vacancy'])) {
                header('Location: vacancy_list.php?error=vacancy_not_found');
                exit;
            }
            
            $data = [
                'vacancy' => $vacancyResult['vacancy'],
                'vacancy_id' => $vacancyId
            ];
            
            $this->render('apply_form', $data);
        }
    }   
    public function myApplications() {
        if (!isset($_SESSION['token']) || $_SESSION['user_role'] !== 'job_seeker') {
            header('Location: login.php');
            exit;
        }
        $filters = [
            'status' => $_GET['status'] ?? '',
            'vacancy' => $_GET['vacancy'] ?? ''
        ];
        
        $result = $this->applicationModel->getUserApplications($_SESSION['token'], $filters);
        
        $data = [
            'applications' => $result['success'] ? ($result['applications'] ?? []) : [],
            'filters' => $filters,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Ошибка загрузки заявок')
        ];
        
        $this->render('my_applications', $data);
    }
    public function manageApplications() {
        if (!isset($_SESSION['token']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        $filters = [
            'status' => $_GET['status'] ?? '',
            'vacancy' => $_GET['vacancy'] ?? ''
        ];
        
        $result = $this->applicationModel->getEmployerApplications($_SESSION['token'], $filters);
        
        $data = [
            'applications' => $result['success'] ? ($result['applications'] ?? []) : [],
            'filters' => $filters,
            'error' => $result['success'] ? null : ($result['error'] ?? 'Ошибка загрузки заявок')
        ];
        
        $this->render('manage_applications', $data);
    }

    public function updateStatus() {
        if (!isset($_SESSION['token']) || $_SESSION['user_role'] !== 'employer') {
            header('Location: login.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $applicationId = intval($_POST['application_id'] ?? 0);
            $status = trim($_POST['status'] ?? '');
            
            if ($applicationId > 0 && in_array($status, ['pending', 'viewed', 'accepted', 'rejected'])) {
                $result = $this->applicationModel->updateStatus($applicationId, $status, $_SESSION['token']);
                
                if ($result['success']) {
                    header('Location: manage_applications.php?success=status_updated');
                } else {
                    header('Location: manage_applications.php?error=' . urlencode($result['error'] ?? 'Ошибка обновления статуса'));
                }
            } else {
                header('Location: manage_applications.php?error=invalid_data');
            }
        } else {
            header('Location: manage_applications.php');
        }
        exit;
    }
    
   private function render($view, $data = []) {

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
