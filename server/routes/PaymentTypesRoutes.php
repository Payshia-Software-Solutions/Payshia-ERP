<?php
require_once './controllers/PaymentTypesController.php';

$pdo = $GLOBALS['pdo'];
$paymentTypesController = new PaymentTypesController($pdo);

return [
    'GET /payment-types/' => function () use ($paymentTypesController) {
        $paymentTypesController->getAllRecords();
    },
    'GET /payment-types/{id}/' => function ($id) use ($paymentTypesController) {
        $paymentTypesController->getRecordById($id);
    },
    'POST /payment-types/' => function () use ($paymentTypesController) {
        $paymentTypesController->createRecord();
    },
    'PUT /payment-types/{id}/' => function ($id) use ($paymentTypesController) {
        $paymentTypesController->updateRecord($id);
    },
    'DELETE /payment-types/{id}/' => function ($id) use ($paymentTypesController) {
        $paymentTypesController->deleteRecord($id);
    }
];
