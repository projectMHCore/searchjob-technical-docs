<?php
// Модель пользователя для работы с MySQL и XML
class User {
    private $db;
    
    public function __construct() {
        $config = require __DIR__ . '/../config/db.php';
        $this->db = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        if ($this->db->connect_error) {
            die('Ошибка подключения к БД: ' . $this->db->connect_error);
        }
    }
    public function register($login, $password, $email, $role = 'job_seeker', $company_name = null) {
        $login = $this->db->real_escape_string($login);
        $email = $this->db->real_escape_string($email);
        $role = $this->db->real_escape_string($role);
        $company_name = $company_name ? $this->db->real_escape_string($company_name) : null;
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $check = $this->db->query("SELECT id FROM users WHERE login='$login' OR email='$email'");
        if ($check && $check->num_rows > 0) {
            return ['success' => false, 'error' => 'Пользователь уже существует'];
        }
        $fields = "login, password, email, role";
        $values = "'$login', '$passwordHash', '$email', '$role'";        if ($role === 'employer') {
            $fields .= ", company_name";
            $values .= ", '$company_name'";
        }
        
        $res = $this->db->query("INSERT INTO users ($fields) VALUES ($values)");
        if ($res) {
            $user_id = $this->db->insert_id;
            
            // Создаем XML файл для пользователя согласно требованиям методички Lab-2
            $this->createUserXMLFile($user_id, $login, $email, $role, $company_name);
            
            return ['success' => true, 'user_id' => $user_id];
        } else {
            return ['success' => false, 'error' => 'Ошибка регистрации'];        }
    }
    
    public function login($login, $password) {
        $login = $this->db->real_escape_string($login);
        $res = $this->db->query("SELECT id, password, role FROM users WHERE login='$login'");
        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Генерируем токен для пользователя
                $token = bin2hex(random_bytes(32));
                $this->saveToken($row['id'], $token);
                
                return [
                    'success' => true, 
                    'user_id' => $row['id'],
                    'token' => $token,
                    'role' => $row['role']
                ];
            }        }
        return ['success' => false, 'error' => 'Неверный логин или пароль'];
    }
    
    /**
     * Создание XML файла для пользователя согласно требованиям Lab-2
     */
    private function createUserXMLFile($user_id, $login, $email, $role, $company_name = null) {
        try {
            // Создаем директорию xml если её нет
            $xmlDir = __DIR__ . '/../xml';
            if (!is_dir($xmlDir)) {
                if (!mkdir($xmlDir, 0755, true)) {
                    error_log("Не удалось создать директорию XML: " . $xmlDir);
                    return false;
                }
            }
            
            // Создаем XML документ
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            
            // Корневой элемент
            $root = $xml->createElement('user');
            $xml->appendChild($root);
            
            // Добавляем данные пользователя
            $root->appendChild($xml->createElement('id', $user_id));
            $root->appendChild($xml->createElement('login', htmlspecialchars($login)));
            $root->appendChild($xml->createElement('email', htmlspecialchars($email)));
            $root->appendChild($xml->createElement('role', htmlspecialchars($role)));
            $root->appendChild($xml->createElement('created_at', date('Y-m-d H:i:s')));
            
            if ($company_name && $role === 'employer') {
                $root->appendChild($xml->createElement('company_name', htmlspecialchars($company_name)));
            }
            
            // Сохраняем XML файл
            $filename = $xmlDir . '/user_' . $user_id . '.xml';
            return $xml->save($filename);
            
        } catch (Exception $e) {
            error_log("Ошибка создания XML файла: " . $e->getMessage());
            return false;
        }
    }
      /**
     * Получение данных профиля из XML файла или базы данных
     */
    public function getProfile($user_id) {
        $user_id = intval($user_id);
        
        // Сначала пытаемся получить данные из XML файла
        $xmlFile = __DIR__ . '/../xml/user_' . $user_id . '.xml';
        if (file_exists($xmlFile)) {
            try {
                $xml = simplexml_load_file($xmlFile);
                if ($xml !== false) {
                    // Для XML файлов возвращаем базовые данные и дополняем из БД
                    $basicProfile = [
                        'id' => (string)$xml->id,
                        'login' => (string)$xml->login,
                        'email' => (string)$xml->email,
                        'role' => (string)$xml->role,
                        'company_name' => isset($xml->company_name) ? (string)$xml->company_name : null,
                        'created' => (string)$xml->created_at
                    ];
                    
                    // Дополняем расширенными данными из БД
                    $extendedData = $this->getExtendedProfileData($user_id);
                    return (object) array_merge($basicProfile, $extendedData);
                }
            } catch (Exception $e) {
                error_log("Ошибка чтения XML файла: " . $e->getMessage());
            }
        }
          // Получаем полные данные из базы данных
        $fullProfile = $this->getUserProfile($user_id);
        if ($fullProfile) {
            $fullProfile['created'] = $fullProfile['created_at']; // Alias для совместимости
            return (object) $fullProfile;
        }
        
        return null;
    }
    
    /**
     * Получение расширенных данных профиля из базы данных
     */    private function getExtendedProfileData($user_id) {
        $user_id = intval($user_id);
        $res = $this->db->query("SELECT 
            first_name, last_name, phone, birth_date, city, experience_years, 
            education, skills, about_me, salary_expectation, company_description, 
            company_address, company_website, company_size, company_industry, avatar
            FROM users WHERE id=$user_id");
        
        if ($res && $res->num_rows === 1) {
            return $res->fetch_assoc();
        }
          return [];
    }
    
    /**
     * Обновление профиля пользователя
     */
    public function updateProfile($user_id, $data) {
        $user_id = intval($user_id);
        
        // Базовые поля
        $updates = [];
        $allowedFields = [
            'login', 'email', 'company_name',
            'first_name', 'last_name', 'phone', 'birth_date', 'city', 
            'experience_years', 'education', 'skills', 'about_me', 'salary_expectation',
            'company_description', 'company_address', 'company_website', 
            'company_size', 'company_industry'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $value = $this->db->real_escape_string($data[$field]);
                $updates[] = "$field = '$value'";
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updateSQL = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $user_id";
        return $this->db->query($updateSQL);
    }
    public function saveToken($user_id, $token) {
        $user_id = intval($user_id);
        $token = $this->db->real_escape_string($token);
        $this->db->query("CREATE TABLE IF NOT EXISTS user_tokens (user_id INT, token VARCHAR(64), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $this->db->query("DELETE FROM user_tokens WHERE user_id=$user_id");
        $this->db->query("INSERT INTO user_tokens (user_id, token) VALUES ($user_id, '$token')");
    }
    public function getUserIdByToken($token) {
        $token = $this->db->real_escape_string($token);
        $res = $this->db->query("SELECT user_id FROM user_tokens WHERE token='$token' LIMIT 1");
        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            return $row['user_id'];
        }
        return null;
    }    public function getUserByToken($token) {
        $token = $this->db->real_escape_string($token);
        $res = $this->db->query("SELECT u.id, u.login, u.email, u.role, u.company_name, u.created_at, u.company_logo, u.avatar, u.first_name, u.last_name, u.phone, u.birth_date, u.city, u.experience_years, u.education, u.skills, u.about_me, u.salary_expectation, u.company_description, u.company_address, u.company_website, u.company_size, u.company_industry
                                FROM users u 
                                JOIN user_tokens t ON u.id = t.user_id 
                                WHERE t.token='$token' LIMIT 1");
        if ($res && $res->num_rows === 1) {
            return $res->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Обновление аватара пользователя
     */
    public function updateAvatar($userId, $avatarPath) {
        $userId = intval($userId);
        $avatarPath = $avatarPath ? $this->db->real_escape_string($avatarPath) : null;
        
        if ($avatarPath) {
            $sql = "UPDATE users SET avatar = '$avatarPath', updated_at = NOW() WHERE id = $userId";
        } else {
            $sql = "UPDATE users SET avatar = NULL, updated_at = NOW() WHERE id = $userId";
        }
        
        return $this->db->query($sql);
    }
    
    /**
     * Получение пути к аватару пользователя
     */
    public function getAvatarPath($userId) {
        $userId = intval($userId);
        $res = $this->db->query("SELECT avatar FROM users WHERE id = $userId LIMIT 1");
        
        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            return $row['avatar'];
        }
          return null;
    }
    
    /**
     * Получение информации о пользователе с аватаром
     */
    public function getUserProfile($userId) {
        $userId = intval($userId);
        $res = $this->db->query("SELECT id, login, email, role, company_name, created_at, 
                                first_name, last_name, phone, birth_date, city, experience_years, 
                                education, skills, about_me, salary_expectation, company_description, 
                                company_address, company_website, company_size, company_industry, 
                                avatar, updated_at 
                                FROM users WHERE id = $userId LIMIT 1");
        
        if ($res && $res->num_rows === 1) {
            return $res->fetch_assoc();
        }
        
        return null;
    }
}
