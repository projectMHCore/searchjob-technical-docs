<?php
// Страница подачи заявки на вакансию (MVC архитектура)
require_once __DIR__ . '/controllers/ApplicationController.php';

$controller = new ApplicationController();
$controller->apply();
?>
