<?php
class Vacancy {
    private $conn;
      public function __construct() {
        $config = require __DIR__ . '/../config/db.php';
        $this->conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        if ($this->conn->connect_error) {
            die('Ошибка подключения: ' . $this->conn->connect_error);
        }
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS vacancies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employer_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            salary VARCHAR(100),
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP        )";
        $this->conn->query($sql);
    }
    
    public function create($data) {
        
        $employer_id = $data['employer_id'];
        $title = $data['title'];
        $description = $data['description'];
        $salary = $data['salary'] ?? '';
        $company = $data['company'] ?? '';
        $location = $data['location'] ?? '';
        $requirements = $data['requirements'] ?? '';
        $employment_type = $data['employment_type'] ?? '';
        
        $stmt = $this->conn->prepare("INSERT INTO vacancies (employer_id, title, description, salary, company, location, requirements, employment_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssss', 
            $employer_id, 
            $title, 
            $description, 
            $salary,
            $company,
            $location,
            $requirements,
            $employment_type
        );
        if ($stmt->execute()) {
            $vacancy_id = $stmt->insert_id;
            $this->createVacancyXMLFile($vacancy_id, $data);
            
            return $vacancy_id;
        }
        return false;
    }
    
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM vacancies WHERE is_active = 1 ORDER BY created_at DESC");
        $vacancies = [];
        while ($row = $result->fetch_assoc()) {
            $vacancies[] = $row;
        }
        return $vacancies;
    }
    
    public function search($searchTerm) {
        $search = $this->conn->real_escape_string($searchTerm);
        $query = "SELECT * FROM vacancies WHERE is_active = 1 AND (title LIKE '%$search%' OR description LIKE '%$search%' OR salary LIKE '%$search%') ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        $vacancies = [];
        while ($row = $result->fetch_assoc()) {
            $vacancies[] = $row;
        }
        return $vacancies;
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM vacancies WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }    public function getVacanciesByEmployer($employerId) {
        $stmt = $this->conn->prepare("SELECT * FROM vacancies WHERE employer_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $employerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $vacancies = [];
        while ($row = $result->fetch_assoc()) {
            $vacancies[] = $row;
        }        return $vacancies;
    }
    
    public function update($id, $data, $employerId = null) {
        if ($employerId !== null) {
            $checkStmt = $this->conn->prepare("SELECT id FROM vacancies WHERE id = ? AND employer_id = ?");
            $checkStmt->bind_param('ii', $id, $employerId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            if ($result->num_rows === 0) {
                return false;
            }
        }
        
        $stmt = $this->conn->prepare("UPDATE vacancies SET title = ?, description = ?, salary = ?, company = ?, location = ?, requirements = ?, employment_type = ? WHERE id = ?");        // Подготавливаем переменные для bind_param (требуется передача по ссылке)
        $title = $data['title'];
        $description = $data['description'];
        $salary = $data['salary'];
        $company = $data['company'] ?? '';
        $location = $data['location'] ?? '';
        $requirements = $data['requirements'] ?? '';
        $employment_type = $data['employment_type'] ?? '';
        
        $stmt->bind_param('sssssssi', 
            $title, 
            $description, 
            $salary,
            $company,
            $location,
            $requirements,
            $employment_type,
            $id
        );
        
        if ($stmt->execute()) {
            $this->updateVacancyXMLFile($id, $data);
            return true;
        }
        
        return false;
    }
      public function delete($id, $employerId) {
        $stmt = $this->conn->prepare("SELECT id FROM vacancies WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('ii', $id, $employerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $stmt = $this->conn->prepare("DELETE FROM vacancies WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('ii', $id, $employerId);
        
        if ($stmt->execute()) {
            $xmlFile = __DIR__ . '/../xml/vacancies/vacancy_' . $id . '.xml';
            if (file_exists($xmlFile)) {
                unlink($xmlFile);
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Переключает статус активности вакансии (активна/неактивна)
     */
    public function toggleStatus($id, $employerId) {
        $stmt = $this->conn->prepare("SELECT is_active FROM vacancies WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('ii', $id, $employerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        $newStatus = $row['is_active'] ? 0 : 1;
        $stmt = $this->conn->prepare("UPDATE vacancies SET is_active = ? WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('iii', $newStatus, $id, $employerId);
        return $stmt->execute();
    }
    
    /**
     * Устанавливает статус активности вакансии
     */
    public function setStatus($id, $isActive, $employerId) {
        $stmt = $this->conn->prepare("SELECT id FROM vacancies WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('ii', $id, $employerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $status = $isActive ? 1 : 0;
        
        $stmt = $this->conn->prepare("UPDATE vacancies SET is_active = ? WHERE id = ? AND employer_id = ?");
        $stmt->bind_param('iii', $status, $id, $employerId);
        return $stmt->execute();
    }
    private function createVacancyXMLFile($vacancy_id, $data) {
        try {
            $xmlDir = __DIR__ . '/../xml/vacancies';
            if (!is_dir($xmlDir)) {
                if (!mkdir($xmlDir, 0755, true)) {
                    error_log("Не удалось создать директорию XML для вакансий: " . $xmlDir);
                    return false;
                }
            }
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $root = $xml->createElement('vacancy');
            $xml->appendChild($root);
            $root->appendChild($xml->createElement('id', $vacancy_id));
            $root->appendChild($xml->createElement('employer_id', htmlspecialchars($data['employer_id'])));
            $root->appendChild($xml->createElement('title', htmlspecialchars($data['title'])));
            $root->appendChild($xml->createElement('description', htmlspecialchars($data['description'])));
            $root->appendChild($xml->createElement('salary', htmlspecialchars($data['salary'] ?? '')));
            $root->appendChild($xml->createElement('company', htmlspecialchars($data['company'] ?? '')));
            $root->appendChild($xml->createElement('location', htmlspecialchars($data['location'] ?? '')));
            $root->appendChild($xml->createElement('requirements', htmlspecialchars($data['requirements'] ?? '')));
            $root->appendChild($xml->createElement('employment_type', htmlspecialchars($data['employment_type'] ?? '')));
            $root->appendChild($xml->createElement('is_active', '1'));
            $root->appendChild($xml->createElement('created_at', date('Y-m-d H:i:s')));
            $filename = $xmlDir . '/vacancy_' . $vacancy_id . '.xml';
            return $xml->save($filename);
            
        } catch (Exception $e) {
            error_log("Ошибка создания XML файла вакансии: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Обновление XML файла вакансии
     */
    private function updateVacancyXMLFile($vacancy_id, $data) {
        try {
            $xmlFile = __DIR__ . '/../xml/vacancies/vacancy_' . $vacancy_id . '.xml';
            
            if (file_exists($xmlFile)) {
                $xml = new DOMDocument();
                $xml->load($xmlFile);
                $fields = ['title', 'description', 'salary', 'company', 'location', 'requirements', 'employment_type'];
                foreach ($fields as $field) {
                    if (isset($data[$field])) {
                        $element = $xml->getElementsByTagName($field)->item(0);
                        if ($element) {
                            $element->nodeValue = htmlspecialchars($data[$field]);
                        }
                    }
                }
                $updatedAtElement = $xml->getElementsByTagName('updated_at')->item(0);
                if (!$updatedAtElement) {
                    $updatedAtElement = $xml->createElement('updated_at');
                    $xml->documentElement->appendChild($updatedAtElement);
                }
                $updatedAtElement->nodeValue = date('Y-m-d H:i:s');
                
                return $xml->save($xmlFile);
            } else {
                return $this->createVacancyXMLFile($vacancy_id, $data);
            }
            
        } catch (Exception $e) {
            error_log("Ошибка обновления XML файла вакансии: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Загрузка данных вакансии из XML файла
     */
    public function getVacancyFromXML($vacancy_id) {
        $xmlFile = __DIR__ . '/../xml/vacancies/vacancy_' . $vacancy_id . '.xml';
        
        if (file_exists($xmlFile)) {
            try {
                $xml = simplexml_load_file($xmlFile);
                if ($xml !== false) {
                    return [
                        'id' => (string)$xml->id,
                        'employer_id' => (string)$xml->employer_id,
                        'title' => (string)$xml->title,
                        'description' => (string)$xml->description,
                        'salary' => (string)$xml->salary,
                        'company' => (string)$xml->company,
                        'location' => (string)$xml->location,
                        'requirements' => (string)$xml->requirements,
                        'employment_type' => (string)$xml->employment_type,
                        'is_active' => (string)$xml->is_active,
                        'created_at' => (string)$xml->created_at,
                        'updated_at' => isset($xml->updated_at) ? (string)$xml->updated_at : null
                    ];
                }
            } catch (Exception $e) {
                error_log("Ошибка чтения XML файла вакансии: " . $e->getMessage());
            }
        }
        
        return null;
    }
    
    /**
     * Десериализация всех вакансий из XML файлов
     */
    public function getAllVacanciesFromXML() {
        $xmlDir = __DIR__ . '/../xml/vacancies';
        $vacancies = [];
        
        if (!is_dir($xmlDir)) {
            return $vacancies;
        }
        
        $files = glob($xmlDir . '/vacancy_*.xml');
        foreach ($files as $file) {
            try {
                $xml = simplexml_load_file($file);
                if ($xml !== false) {
                    $vacancies[] = [
                        'id' => (string)$xml->id,
                        'employer_id' => (string)$xml->employer_id,
                        'title' => (string)$xml->title,
                        'description' => (string)$xml->description,
                        'salary' => (string)$xml->salary,
                        'company' => (string)$xml->company,
                        'location' => (string)$xml->location,
                        'requirements' => (string)$xml->requirements,
                        'employment_type' => (string)$xml->employment_type,
                        'is_active' => (string)$xml->is_active,
                        'created_at' => (string)$xml->created_at,
                        'updated_at' => isset($xml->updated_at) ? (string)$xml->updated_at : null
                    ];
                }
            } catch (Exception $e) {
                error_log("Ошибка чтения XML файла: " . $e->getMessage());
            }
        }
        
        return $vacancies;
    }
}
