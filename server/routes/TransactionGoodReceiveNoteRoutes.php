<?php
require_once './controllers/TransactionGoodReceiveNoteController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionGoodReceiveNoteController($pdo);

return [
    'GET /transaction-good-receive-note/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-good-receive-note/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
