<?php
require_once __DIR__ . '/controllers/VacancyController.php';
session_start();

// авторизация
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header('Location: login.php');
    exit;
}

$controller = new VacancyController();

// Обработка удаления вакансии
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $vacancyId = intval($_POST['vacancy_id'] ?? 0);
    if ($vacancyId > 0) {
        $controller->delete($vacancyId);
    }
} else {
    $controller->myVacancies();
}
?>
