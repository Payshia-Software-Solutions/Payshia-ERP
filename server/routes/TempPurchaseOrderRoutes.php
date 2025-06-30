<?php
require_once './controllers/TempPurchaseOrderController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TempPurchaseOrderController($pdo);

return [
    'GET /temp-purchase-order/' => function () use ($controller) {
        $controller->getAllRecords();
    },
    'GET /temp-purchase-order/{id}/' => function ($id) use ($controller) {
        $controller->getRecordById($id);
    },
    'POST /temp-purchase-order/' => function () use ($controller) {
        $controller->createRecord();
    },
    'PUT /temp-purchase-order/{id}/' => function ($id) use ($controller) {
        $controller->updateRecord($id);
    },
    'DELETE /temp-purchase-order/{id}/' => function ($id) use ($controller) {
        $controller->deleteRecord($id);
    }
];
