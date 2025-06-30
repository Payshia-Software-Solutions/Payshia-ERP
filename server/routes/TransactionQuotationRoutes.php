<?php
require_once './controllers/TransactionQuotationController.php';

$pdo = $GLOBALS['pdo'];
$quotationController = new TransactionQuotationController($pdo);

return [
    'GET /quotations/' => function () use ($quotationController) {
        $quotationController->getAllRecords();
    },
    'GET /quotations/{id}/' => function ($id) use ($quotationController) {
        $quotationController->getRecordById($id);
    },
    'POST /quotations/' => function () use ($quotationController) {
        $quotationController->createRecord();
    },
    'PUT /quotations/{id}/' => function ($id) use ($quotationController) {
        $quotationController->updateRecord($id);
    },
    'DELETE /quotations/{id}/' => function ($id) use ($quotationController) {
        $quotationController->deleteRecord($id);
    }
];
?>