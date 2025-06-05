<?php
// Страница "Мои вакансии" (MVC архитектура)
require_once __DIR__ . '/controllers/VacancyController.php';

$controller = new VacancyController();
$controller->myVacancies();
?>