<?php
require_once './controllers/EmployeeDetailController.php';

$pdo = $GLOBALS['pdo'];
$controller = new EmployeeDetailController($pdo);

return [
    'GET /employee-details/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /employee-details/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /employee-details/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /employee-details/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /employee-details/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
