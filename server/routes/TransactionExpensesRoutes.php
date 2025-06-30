<?php
require_once './controllers/TransactionExpensesController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionExpensesController($pdo);

return [
    'GET /transaction-expenses/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-expenses/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-expenses/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-expenses/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-expenses/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
