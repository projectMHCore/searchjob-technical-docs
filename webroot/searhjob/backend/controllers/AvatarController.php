<?php
/**
 * Контроллер для работы с аватарами пользователей
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Logger.php';

class AvatarController {
    
    public function __construct() {
        Logger::init();
    }
    
    /**
     * Загрузка нового аватара
     */
    public function uploadAvatar($userId, $file) {
        try {
            // Проверяем, что файл был загружен
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Ошибка загрузки файла'];
            }
            
            // Проверяем размер файла (максимум 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                return ['success' => false, 'message' => 'Размер файла не должен превышать 5MB'];
            }
            
            // Проверяем тип файла
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                return ['success' => false, 'message' => 'Допустимы только файлы JPEG, PNG, GIF'];
            }
            
            // Проверяем, что это действительно изображение
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return ['success' => false, 'message' => 'Файл не является изображением'];
            }
            
            // Создаем уникальное имя файла
            $extension = $this->getExtensionFromMimeType($fileType);
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . $extension;
            
            // Путь для сохранения
            $uploadDir = __DIR__ . '/../../frontend/assets/uploads/avatars/';
            $uploadPath = $uploadDir . $fileName;
            
            // Убеждаемся, что папка существует
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Удаляем старый аватар, если есть
            $this->deleteOldAvatar($userId);
            
            // Перемещаем загруженный файл
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Создаем миниатюру
                $this->createThumbnail($uploadPath, $uploadDir . 'thumb_' . $fileName);
                  // Обновляем базу данных
                $user = new User();
                $relativePath = 'assets/uploads/avatars/' . $fileName;
                
                if ($user->updateAvatar($userId, $relativePath)) {
                    Logger::info("Avatar uploaded for user $userId: $fileName");
                    
                    return [
                        'success' => true, 
                        'message' => 'Аватар успешно загружен',
                        'avatar_path' => $relativePath,
                        'avatar_url' => './' . $relativePath
                    ];
                } else {
                    // Удаляем файл, если не удалось обновить БД
                    unlink($uploadPath);
                    if (file_exists($uploadDir . 'thumb_' . $fileName)) {
                        unlink($uploadDir . 'thumb_' . $fileName);
                    }
                    return ['success' => false, 'message' => 'Ошибка обновления базы данных'];
                }
            } else {
                return ['success' => false, 'message' => 'Ошибка сохранения файла'];
            }
            
        } catch (Exception $e) {
            Logger::error("Avatar upload error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Внутренняя ошибка сервера'];
        }
    }
    
    /**
     * Удаление аватара пользователя
     */
    public function deleteAvatar($userId) {
        try {
            $user = new User();
            $currentAvatar = $user->getAvatarPath($userId);
            
            if ($currentAvatar) {
                $this->deleteAvatarFiles($currentAvatar);
            }
            
            if ($user->updateAvatar($userId, null)) {
                Logger::info("Avatar deleted for user $userId");
                return ['success' => true, 'message' => 'Аватар успешно удален'];
            } else {
                return ['success' => false, 'message' => 'Ошибка удаления аватара'];
            }
            
        } catch (Exception $e) {
            Logger::error("Avatar delete error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Внутренняя ошибка сервера'];
        }
    }
    
    /**
     * Удаление старого аватара пользователя
     */
    private function deleteOldAvatar($userId) {
        $user = new User();
        $currentAvatar = $user->getAvatarPath($userId);
        
        if ($currentAvatar) {
            $this->deleteAvatarFiles($currentAvatar);
        }
    }
    
    /**
     * Удаление файлов аватара (основной и миниатюра)
     */
    private function deleteAvatarFiles($avatarPath) {
        $fullPath = __DIR__ . '/../../' . $avatarPath;
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        
        // Удаляем миниатюру
        $dir = dirname($fullPath);
        $filename = basename($fullPath);
        $thumbPath = $dir . '/thumb_' . $filename;
        
        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }
    }
    
    /**
     * Создание миниатюры изображения
     */
    private function createThumbnail($sourcePath, $thumbPath, $maxWidth = 150, $maxHeight = 150) {
        $imageInfo = getimagesize($sourcePath);
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Вычисляем новые размеры с сохранением пропорций
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = intval($originalWidth * $ratio);
        $newHeight = intval($originalHeight * $ratio);
        
        // Создаем изображение из источника
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }
        
        // Создаем новое изображение
        $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Для PNG и GIF сохраняем прозрачность
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbImage, false);
            imagesavealpha($thumbImage, true);
            $transparent = imagecolorallocatealpha($thumbImage, 255, 255, 255, 127);
            imagefilledrectangle($thumbImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Изменяем размер
        imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
        // Сохраняем миниатюру
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($thumbImage, $thumbPath, 90);
                break;
            case 'image/png':
                imagepng($thumbImage, $thumbPath);
                break;
            case 'image/gif':
                imagegif($thumbImage, $thumbPath);
                break;
        }
        
        // Освобождаем память
        imagedestroy($sourceImage);
        imagedestroy($thumbImage);
        
        return true;
    }
    
    /**
     * Получение расширения файла по MIME типу
     */
    private function getExtensionFromMimeType($mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
                return 'jpg';
            case 'image/png':
                return 'png';
            case 'image/gif':
                return 'gif';
            default:
                return 'jpg';
        }
    }
}
