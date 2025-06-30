<?php
require_once './controllers/TempOrderController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TempOrderController($pdo);

return [
    'GET /temp-order/' => function () use ($controller) {
        $controller->getAllRecords();
    },
    'GET /temp-order/{id}/' => function ($id) use ($controller) {
        $controller->getRecordById($id);
    },
    'POST /temp-order/' => function () use ($controller) {
        $controller->createRecord();
    },
    'PUT /temp-order/{id}/' => function ($id) use ($controller) {
        $controller->updateRecord($id);
    },
    'DELETE /temp-order/{id}/' => function ($id) use ($controller) {
        $controller->deleteRecord($id);
    }
];
