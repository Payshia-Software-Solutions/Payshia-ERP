<?php
require_once './controllers/TransactionExpensesTypesController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionExpensesTypesController($pdo);

return [
    'GET /transaction-expenses-types/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-expenses-types/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-expenses-types/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-expenses-types/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-expenses-types/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
