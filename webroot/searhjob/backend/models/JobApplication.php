<?php
class JobApplication {
    private $db;
    
    public function __construct() {
        $config = require __DIR__ . '/../config/db.php';
        $this->db = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_error) {
            die('Ошибка подключения к БД: ' . $this->db->connect_error);
        }
    }
    
    /**
     * Создание отклика на вакансию
     */
    public function create($vacancy_id, $user_id, $cover_letter = '') {
        $vacancy_id = intval($vacancy_id);
        $user_id = intval($user_id);
        $cover_letter = $this->db->real_escape_string($cover_letter);
        $check = $this->db->query("SELECT id FROM job_applications WHERE vacancy_id = $vacancy_id AND user_id = $user_id");
        if ($check && $check->num_rows > 0) {
            return ['success' => false, 'error' => 'Вы уже откликнулись на эту вакансию'];
        }
        
        $sql = "INSERT INTO job_applications (vacancy_id, user_id, cover_letter) VALUES ($vacancy_id, $user_id, '$cover_letter')";
        $result = $this->db->query($sql);
        
        if ($result) {
            return ['success' => true, 'application_id' => $this->db->insert_id];
        } else {
            return ['success' => false, 'error' => 'Ошибка при создании отклика: ' . $this->db->error];
        }
    }
      /**
     * Получение откликов пользователя
     */
    public function getUserApplications($user_id, $filters = []) {
        $user_id = intval($user_id);
        
        $where_conditions = ["ja.user_id = $user_id"];
        if (!empty($filters['status'])) {
            $status = $this->db->real_escape_string($filters['status']);
            $where_conditions[] = "ja.status = '$status'";
        }
        if (!empty($filters['vacancy'])) {
            $vacancy_title = $this->db->real_escape_string($filters['vacancy']);
            $where_conditions[] = "v.title LIKE '%$vacancy_title%'";
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        $sql = "
            SELECT ja.*, v.title, v.salary, v.description, v.created_at as vacancy_created,
                   u.login as employer_login, u.company_name
            FROM job_applications ja
            JOIN vacancies v ON ja.vacancy_id = v.id
            JOIN users u ON v.employer_id = u.id
            WHERE $where_clause
            ORDER BY ja.created_at DESC
        ";
        
        $result = $this->db->query($sql);
        $applications = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $applications[] = $row;
            }
        }
        
        return $applications;
    }
    
    /**
     * Получение откликов на вакансии работодателя
     */
    public function getEmployerApplications($employer_id, $filters = []) {
        $employer_id = intval($employer_id);
        
        $where_conditions = ["v.employer_id = $employer_id"];
        
        if (!empty($filters['status'])) {
            $status = $this->db->real_escape_string($filters['status']);
            $where_conditions[] = "ja.status = '$status'";
        }
        if (!empty($filters['vacancy'])) {
            $vacancy_title = $this->db->real_escape_string($filters['vacancy']);
            $where_conditions[] = "v.title LIKE '%$vacancy_title%'";
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        $sql = "
            SELECT ja.*, v.title, v.salary,
                   u.login, u.email, u.first_name, u.last_name, u.phone, u.experience_years, 
                   u.education, u.skills, u.about_me, u.salary_expectation
            FROM job_applications ja
            JOIN vacancies v ON ja.vacancy_id = v.id
            JOIN users u ON ja.user_id = u.id
            WHERE $where_clause
            ORDER BY ja.created_at DESC
        ";
        
        $result = $this->db->query($sql);
        $applications = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $applications[] = $row;
            }
        }
        
        return $applications;
    }
    
    /**
     * Обновление статуса отклика
     */
    public function updateStatus($application_id, $status, $employer_id) {
        $application_id = intval($application_id);
        $status = $this->db->real_escape_string($status);
        $employer_id = intval($employer_id);
        $check = $this->db->query("
            SELECT ja.id FROM job_applications ja
            JOIN vacancies v ON ja.vacancy_id = v.id
            WHERE ja.id = $application_id AND v.employer_id = $employer_id
        ");
        
        if (!$check || $check->num_rows === 0) {
            return ['success' => false, 'error' => 'Отклик не найден или у вас нет прав на его изменение'];
        }
        
        $sql = "UPDATE job_applications SET status = '$status' WHERE id = $application_id";
        $result = $this->db->query($sql);
        
        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'Ошибка при обновлении статуса: ' . $this->db->error];
        }
    }
    
    /**
     * Проверка, откликался ли пользователь на вакансию
     */
    public function hasUserApplied($vacancy_id, $user_id) {
        $vacancy_id = intval($vacancy_id);
        $user_id = intval($user_id);
        
        $result = $this->db->query("SELECT id FROM job_applications WHERE vacancy_id = $vacancy_id AND user_id = $user_id");
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Получение количества откликов на вакансию
     */
    public function getApplicationsCount($vacancy_id) {
        $vacancy_id = intval($vacancy_id);
        $result = $this->db->query("SELECT COUNT(*) as count FROM job_applications WHERE vacancy_id = $vacancy_id");
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        
        return 0;
    }
    
    /**
     * Проверка доступа работодателя к профилю кандидата
     */
    public function hasEmployerAccessToCandidate($employer_id, $candidate_id) {
        $employer_id = intval($employer_id);
        $candidate_id = intval($candidate_id);
        $sql = "
            SELECT COUNT(*) as count
            FROM job_applications ja
            JOIN vacancies v ON ja.vacancy_id = v.id
            WHERE v.employer_id = $employer_id AND ja.user_id = $candidate_id
        ";
        
        $result = $this->db->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        
        return false;
    }
}
?>
