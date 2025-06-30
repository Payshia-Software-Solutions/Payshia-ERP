<?php
require_once './controllers/TransactionCancellationController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionCancellationController($pdo);

return [
    'GET /transaction-cancellation/' => function () use ($controller) {
        $controller->getAllRecords();
    },
    'GET /transaction-cancellation/{id}/' => function ($id) use ($controller) {
        $controller->getRecordById($id);
    },
    'POST /transaction-cancellation/' => function () use ($controller) {
        $controller->createRecord();
    },
    'PUT /transaction-cancellation/{id}/' => function ($id) use ($controller) {
        $controller->updateRecord($id);
    },
    'DELETE /transaction-cancellation/{id}/' => function ($id) use ($controller) {
        $controller->deleteRecord($id);
    }
];
