<?php
require_once __DIR__ . '/controllers/VacancyController.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: my_vacancies.php');
    exit;
}

$controller = new VacancyController();
$controller->edit($id);
?>
