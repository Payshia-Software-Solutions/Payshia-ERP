<?php
require_once './controllers/TransactionGoodReceiveNoteItemsController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionGoodReceiveNoteItemsController($pdo);

return [
    'GET /transaction-good-receive-note-items/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /transaction-good-receive-note-items/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'POST /transaction-good-receive-note-items/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /transaction-good-receive-note-items/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /transaction-good-receive-note-items/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
