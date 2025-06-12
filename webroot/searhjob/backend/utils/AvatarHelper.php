<?php
/**
 * Утилиты для работы с аватарами пользователей
 */

class AvatarHelper {    /**
     * Получение URL аватара пользователя
     */
    public static function getAvatarUrl($avatarPath, $size = 'full') {
        if (empty($avatarPath)) {
            return null;
        }
        
        // Очищаем путь от префикса frontend/, если он есть
        $cleanPath = $avatarPath;
        if (strpos($cleanPath, 'frontend/') === 0) {
            $cleanPath = substr($cleanPath, 9); // удаляем 'frontend/'
        }
        
        // Используем относительный путь от текущей страницы
        $baseUrl = './';
        
        if ($size === 'thumb') {
            $dir = dirname($cleanPath);
            $filename = basename($cleanPath);
            return $baseUrl . $dir . '/thumb_' . $filename;
        }
        
        return $baseUrl . $cleanPath;
    }
    
    /**
     * Генерация HTML для отображения аватара
     */
    public static function renderAvatar($user, $size = 'medium', $cssClasses = '') {
        $avatarPath = $user['avatar'] ?? null;
        $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        $login = $user['login'] ?? 'User';
        
        // Определяем размеры
        $dimensions = self::getSizeDimensions($size);
        $width = $dimensions['width'];
        $height = $dimensions['height'];
        
        // Базовые CSS классы
        $baseClasses = "user-avatar avatar-{$size}";
        $allClasses = trim($baseClasses . ' ' . $cssClasses);
        
        $html = "<div class=\"{$allClasses}\" style=\"width: {$width}px; height: {$height}px;\">";
        
        if ($avatarPath) {
            $avatarUrl = self::getAvatarUrl($avatarPath, $size === 'small' ? 'thumb' : 'full');
            $altText = !empty($userName) ? $userName : $login;
            
            $html .= "<img src=\"{$avatarUrl}\" alt=\"{$altText}\" " .
                     "style=\"width: 100%; height: 100%; object-fit: cover; border-radius: 50%;\" " .
                     "onerror=\"this.style.display='none'; this.nextElementSibling.style.display='flex';\">";
            
            // Fallback для случая, когда изображение не загружается
            $initials = self::getInitials($userName ?: $login);
            $html .= "<div class=\"avatar-fallback\" style=\"display: none; width: 100%; height: 100%; " .
                     "background: linear-gradient(135deg, #eaa850, #d4922a); color: white; " .
                     "display: flex; align-items: center; justify-content: center; " .
                     "border-radius: 50%; font-weight: 600; font-size: " . ($width * 0.4) . "px;\">" .
                     $initials . "</div>";
        } else {
            // Отображаем инициалы, если нет аватара
            $initials = self::getInitials($userName ?: $login);
            $fontSize = $width * 0.4;
            
            $html .= "<div class=\"avatar-initials\" style=\"width: 100%; height: 100%; " .
                     "background: linear-gradient(135deg, #eaa850, #d4922a); color: white; " .
                     "display: flex; align-items: center; justify-content: center; " .
                     "border-radius: 50%; font-weight: 600; font-size: {$fontSize}px;\">" .
                     $initials . "</div>";
        }
        
        $html .= "</div>";
        
        return $html;
    }
    
    /**
     * Получение инициалов из имени
     */
    private static function getInitials($name) {
        $name = trim($name);
        if (empty($name)) {
            return 'U';
        }
        
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }
        
        return $initials ?: 'U';
    }
    
    /**
     * Получение размеров для разных типов аватаров
     */
    private static function getSizeDimensions($size) {
        switch ($size) {
            case 'small':
                return ['width' => 32, 'height' => 32];
            case 'medium':
                return ['width' => 48, 'height' => 48];
            case 'large':
                return ['width' => 80, 'height' => 80];
            case 'xlarge':
                return ['width' => 120, 'height' => 120];
            default:
                return ['width' => 48, 'height' => 48];
        }
    }
    
    /**
     * CSS стили для аватаров (можно включить в head)
     */
    public static function getAvatarCSS() {
        return "
        <style>
        .user-avatar {
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e1e8ed;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
        }
        
        .user-avatar:hover {
            transform: scale(1.05);
            border-color: #eaa850;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }
        
        .avatar-small {
            border-width: 1px;
        }
        
        .avatar-large, .avatar-xlarge {
            border-width: 3px;
        }
        
        .avatar-initials, .avatar-fallback {
            cursor: default;
            user-select: none;
        }
        
        /* Темная тема */
        [data-theme='dark'] .user-avatar {
            border-color: #475569;
        }
        
        [data-theme='dark'] .user-avatar:hover {
            border-color: #eaa850;
        }
        </style>";
    }
}
?>
