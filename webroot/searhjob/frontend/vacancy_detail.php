<?php
// Страница детальной информации о вакансии (MVC архитектура)
require_once __DIR__ . '/controllers/VacancyController.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: vacancy_list.php');
    exit;
}

$controller = new VacancyController();
$controller->show($id);
?>
