<?php
require_once './controllers/EmployeeDepartmentController.php';

$pdo = $GLOBALS['pdo'];
$controller = new EmployeeDepartmentController($pdo);

return [
    'GET /employee-departments/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /employee-departments/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /employee-departments/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /employee-departments/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /employee-departments/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
