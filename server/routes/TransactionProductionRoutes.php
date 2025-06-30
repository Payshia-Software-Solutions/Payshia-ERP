<?php
require_once './controllers/TransactionProductionController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionProductionController($pdo);

return [
    'GET /transaction-production/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-production/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-production/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-production/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-production/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
