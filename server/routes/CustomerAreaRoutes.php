<?php

require_once './controllers/CustomerAreaController.php';

$pdo = $GLOBALS['pdo'];
$customerAreaController = new CustomerAreaController($pdo);

return [
    'GET /customer-areas/' => function () use ($customerAreaController) {
        $customerAreaController->getAllRecords();
    },
    'GET /customer-areas/{id}/' => function ($id) use ($customerAreaController) {
        $customerAreaController->getRecordById($id);
    },
    'POST /customer-areas/' => function () use ($customerAreaController) {
        $customerAreaController->createRecord();
    },
    'PUT /customer-areas/{id}/' => function ($id) use ($customerAreaController) {
        $customerAreaController->updateRecord($id);
    },
    'DELETE /customer-areas/{id}/' => function ($id) use ($customerAreaController) {
        $customerAreaController->deleteRecord($id);
    }
];
