<?php
require_once './controllers/TransactionProductionItemController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TransactionProductionItemController($pdo);

return [
    'GET /production-items/' => function () use ($controller) {
        $controller->getAll();
    },
    'GET /production-items/{id}/' => function ($id) use ($controller) {
        $controller->getById($id);
    },
    'GET /production-items/by-pn/{pn_id}/' => function ($pn_id) use ($controller) {
        $controller->getByPnId($pn_id);
    },
    'POST /production-items/' => function () use ($controller) {
        $controller->create();
    },
    'PUT /production-items/{id}/' => function ($id) use ($controller) {
        $controller->update($id);
    },
    'DELETE /production-items/{id}/' => function ($id) use ($controller) {
        $controller->delete($id);
    }
];
