<!-- Компонент загрузки аватара -->
<div class="avatar-upload-component">
    <div class="avatar-section">
        <div class="avatar-container">
            <div class="avatar-preview" id="avatarPreview">
                <img id="avatarImage" src="" alt="Аватар" style="display: none;">
                <div class="avatar-placeholder" id="avatarPlaceholder">
                    <i class="fas fa-user"></i>
                    <span>Немає фото</span>
                </div>
            </div>
            
            <div class="avatar-controls">
                <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                <button type="button" class="btn btn-primary btn-sm" onclick="selectAvatar()">
                    <i class="fas fa-camera"></i> Вибрати фото
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="deleteAvatarBtn" onclick="deleteAvatar()" style="display: none;">
                    <i class="fas fa-trash"></i> Видалити
                </button>
            </div>
        </div>
        
        <div class="upload-progress" id="uploadProgress" style="display: none;">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted">Завантаження...</small>
        </div>        
        <div class="upload-info">
            <small class="text-muted">
                Допустимі формати: JPG, PNG, GIF<br>
                Максимальний розмір: 5MB
            </small>
        </div>
    </div>
</div>

<style>
.avatar-upload-component {
    margin-bottom: 20px;
}

.avatar-section {
    text-align: center;
}

.avatar-container {
    margin-bottom: 15px;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    margin: 0 auto 15px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e0e0e0;
    position: relative;
    background: #f8f9fa;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.avatar-placeholder i {
    font-size: 40px;
    margin-bottom: 8px;
}

.avatar-placeholder span {
    font-size: 12px;
    font-weight: 500;
}

.avatar-controls {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.upload-progress {
    margin: 15px 0;
}

.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #007bff, #0056b3);
    transition: width 0.3s ease;
}

.upload-info {
    margin-top: 10px;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Анимации */
.avatar-preview {
    transition: all 0.3s ease;
}

.avatar-preview:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

/* Адаптивность */
@media (max-width: 576px) {
    .avatar-preview {
        width: 120px;
        height: 120px;
    }
    
    .avatar-controls {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-sm {
        width: 150px;
    }
}
</style>

<script>
/**
 * Функции для работы с аватаром
 */

// Инициализация компонента аватара
document.addEventListener('DOMContentLoaded', function() {
    loadCurrentAvatar();
    
    // Обработчик изменения файла
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            previewAvatar(file);
            uploadAvatar(file);
        }
    });
});

// Загрузка текущего аватара пользователя
function loadCurrentAvatar() {
    // Сначала тестируем простой API
    fetch('../backend/api/avatar_simple_test.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Simple API test:', data);
        
        if (!data.success) {
            console.error('Simple API test failed:', data.message);
            showPlaceholder();
            return;
        }
        
        // Если простой тест прошел, пробуем основной API
        return fetch('../backend/api/avatar.php', {
            method: 'GET',
            credentials: 'same-origin'
        });
    })
    .then(response => {
        if (!response) return null;
        return response.json();
    })
    .then(data => {
        if (data && data.success && data.avatar_url) {
            displayAvatar(data.avatar_url);
        } else {
            showPlaceholder();
            if (data && !data.success) {
                console.error('Avatar API error:', data.message);
            }
        }
    })    .catch(error => {
        console.error('Помилка завантаження аватара:', error);
        showPlaceholder();
    });
}

// Отображение аватара
function displayAvatar(avatarUrl) {
    const avatarImage = document.getElementById('avatarImage');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');
    const deleteBtn = document.getElementById('deleteAvatarBtn');
    
    avatarImage.src = avatarUrl;
    avatarImage.style.display = 'block';
    avatarPlaceholder.style.display = 'none';
    deleteBtn.style.display = 'inline-block';
}

// Отображение заглушки
function showPlaceholder() {
    const avatarImage = document.getElementById('avatarImage');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');
    const deleteBtn = document.getElementById('deleteAvatarBtn');
    
    avatarImage.style.display = 'none';
    avatarPlaceholder.style.display = 'flex';
    deleteBtn.style.display = 'none';
}

// Выбор файла аватара
function selectAvatar() {
    document.getElementById('avatarInput').click();
}

// Предварительный просмотр выбранного файла
function previewAvatar(file) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            displayAvatar(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

// Загрузка аватара на сервер
function uploadAvatar(file) {
    const progressContainer = document.getElementById('uploadProgress');
    const progressBar = progressContainer.querySelector('.progress-bar');
    
    // Показываем прогресс
    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';
    
    const formData = new FormData();
    formData.append('avatar', file);
    
    const xhr = new XMLHttpRequest();
    
    // Отслеживание прогресса загрузки
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            progressBar.style.width = percentComplete + '%';
        }
    });
    
    xhr.onload = function() {
        progressContainer.style.display = 'none';
        
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);                if (response.success) {
                    showAlert('Аватар успішно завантажено!', 'success');
                    displayAvatar(response.avatar_url);
                } else {
                    showAlert('Помилка завантаження: ' + response.message, 'error');
                    loadCurrentAvatar(); // Восстанавливаем предыдущий аватар
                }
            } catch (e) {
                showAlert('Помилка обробки відповіді сервера', 'error');
                loadCurrentAvatar();
            }
        } else {
            showAlert('Помилка завантаження файлу', 'error');
            loadCurrentAvatar();
        }
    };
      xhr.onerror = function() {
        progressContainer.style.display = 'none';
        showAlert('Помилка мережі', 'error');
        loadCurrentAvatar();
    };
    
    xhr.open('POST', '../backend/api/avatar.php');
    xhr.send(formData);
}

// Удаление аватара
function deleteAvatar() {
    if (!confirm('Ви впевнені, що хочете видалити аватар?')) {
        return;
    }
      fetch('../backend/api/avatar.php', {
        method: 'DELETE',
        credentials: 'same-origin'
    })
    .then(response => response.json())    .then(data => {
        if (data.success) {
            showAlert('Аватар успішно видалено', 'success');
            showPlaceholder();
        } else {
            showAlert('Помилка видалення: ' + data.message, 'error');
        }
    })    .catch(error => {
        console.error('Помилка видалення аватара:', error);
        showAlert('Помилка видалення аватара', 'error');
    });
}

// Функция для отображения уведомлений
function showAlert(message, type = 'info') {
    // Создаем элемент уведомления
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Автоматическое удаление через 5 секунд
    setTimeout(() => {
        alert.remove();
    }, 5000);
    
    // Обработчик кнопки закрытия
    alert.querySelector('.btn-close').addEventListener('click', () => {
        alert.remove();
    });
}
</script>
