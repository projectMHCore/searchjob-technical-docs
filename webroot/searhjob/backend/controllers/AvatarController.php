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
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Ошибка загрузки файла'];
            }
            
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                return ['success' => false, 'message' => 'Размер файла не должен превышать 5MB'];
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                return ['success' => false, 'message' => 'Допустимы только файлы JPEG, PNG, GIF'];
            }
            
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return ['success' => false, 'message' => 'Файл не является изображением'];
            }
            $this->deleteOldAvatar($userId);
            
            $extension = $this->getExtensionFromMimeType($fileType);
            $fileName = 'avatar_' . $userId . '_' . time() . '_' . uniqid() . '.' . $extension;
            
            $uploadDir = __DIR__ . '/../../frontend/assets/uploads/avatars/';
            $uploadPath = $uploadDir . $fileName;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $this->createThumbnail($uploadPath, $uploadDir . 'thumb_' . $fileName);
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
                $this->deleteAllUserAvatars($userId);
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
        try {
            $user = new User();
            $currentAvatar = $user->getAvatarPath($userId);
            
            if ($currentAvatar) {
                Logger::info("Deleting old avatar for user $userId: $currentAvatar");
                $this->deleteAvatarFiles($currentAvatar);
                $this->deleteAllUserAvatars($userId);
            }
        } catch (Exception $e) {
            Logger::error("Error deleting old avatar for user $userId: " . $e->getMessage());
        }
    }    
    /**
     * Удаление файлов аватара (основной и миниатюра)
     */
    private function deleteAvatarFiles($avatarPath) {
        if (!$avatarPath) return;
        $cleanPath = str_replace('frontend/', '', $avatarPath);
        $fullPath = __DIR__ . '/../../frontend/' . $cleanPath;
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
            Logger::info("Deleted avatar file: $fullPath");
        }
        $dir = dirname($fullPath);
        $filename = basename($fullPath);
        $thumbPath = $dir . '/thumb_' . $filename;
        
        if (file_exists($thumbPath)) {
            unlink($thumbPath);
            Logger::info("Deleted thumbnail: $thumbPath");
        }
    }
    
    /**
     * Удаление всех аватаров пользователя по маске имени файла
     */
    private function deleteAllUserAvatars($userId) {
        $avatarDir = __DIR__ . '/../../frontend/assets/uploads/avatars/';
        
        if (!is_dir($avatarDir)) {
            return;
        }
        $pattern = $avatarDir . 'avatar_' . $userId . '_*';
        $avatarFiles = glob($pattern);
        
        foreach ($avatarFiles as $file) {
            if (is_file($file)) {
                unlink($file);
                Logger::info("Deleted old avatar file: $file");
                $filename = basename($file);
                $thumbPath = dirname($file) . '/thumb_' . $filename;
                if (file_exists($thumbPath)) {
                    unlink($thumbPath);
                    Logger::info("Deleted old thumbnail: $thumbPath");
                }
            }
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
        
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = intval($originalWidth * $ratio);
        $newHeight = intval($originalHeight * $ratio);
        
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
        
        $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
        
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbImage, false);
            imagesavealpha($thumbImage, true);
            $transparent = imagecolorallocatealpha($thumbImage, 255, 255, 255, 127);
            imagefilledrectangle($thumbImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
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
