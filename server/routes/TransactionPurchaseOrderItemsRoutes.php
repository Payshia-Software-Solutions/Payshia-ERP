<?php
require_once './controllers/TransactionPurchaseOrderItemsController.php';

$pdo = $GLOBALS['pdo'];
$poItemsController = new TransactionPurchaseOrderItemsController($pdo);

return [
    'GET /purchase-order-items/' => function () use ($poItemsController) {
        $poItemsController->getAllRecords();
    },
    'GET /purchase-order-items/{id}/' => function ($id) use ($poItemsController) {
        $poItemsController->getRecordById($id);
    },
    'POST /purchase-order-items/' => function () use ($poItemsController) {
        $poItemsController->createRecord();
    },
    'PUT /purchase-order-items/{id}/' => function ($id) use ($poItemsController) {
        $poItemsController->updateRecord($id);
    },
    'DELETE /purchase-order-items/{id}/' => function ($id) use ($poItemsController) {
        $poItemsController->deleteRecord($id);
    }
];
?>