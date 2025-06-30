<?php
require_once './controllers/TransactionPurchaseOrderController.php';

$pdo = $GLOBALS['pdo'];
$transactionPOController = new TransactionPurchaseOrderController($pdo);

return [
    'GET /purchase-orders/' => function () use ($transactionPOController) {
        $transactionPOController->getAllRecords();
    },
    'GET /purchase-orders/{id}/' => function ($id) use ($transactionPOController) {
        $transactionPOController->getRecordById($id);
    },
    'POST /purchase-orders/' => function () use ($transactionPOController) {
        $transactionPOController->createRecord();
    },
    'PUT /purchase-orders/{id}/' => function ($id) use ($transactionPOController) {
        $transactionPOController->updateRecord($id);
    },
    'DELETE /purchase-orders/{id}/' => function ($id) use ($transactionPOController) {
        $transactionPOController->deleteRecord($id);
    }
];
?>