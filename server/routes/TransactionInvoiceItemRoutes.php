<?php
require_once './controllers/TransactionInvoiceItemController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionInvoiceItemController($pdo);

return [
    'GET /transaction-invoice-items/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-invoice-items/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-invoice-items/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-invoice-items/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-invoice-items/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
