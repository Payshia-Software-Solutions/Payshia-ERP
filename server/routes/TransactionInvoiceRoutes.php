<?php
require_once './controllers/TransactionInvoiceController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionInvoiceController($pdo);

return [
    'GET /transaction-invoice/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-invoice/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-invoice/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-invoice/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-invoice/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
