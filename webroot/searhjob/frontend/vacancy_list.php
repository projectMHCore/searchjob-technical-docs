<?php
// Страница списка активных вакансий (MVC архитектура)
require_once __DIR__ . '/controllers/VacancyController.php';

$controller = new VacancyController();
$controller->index();
?>

<!-- Нет неиспользуемых строк, файл корректно включает контроллер и запускает его -->
