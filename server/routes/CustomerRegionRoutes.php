<?php

require_once './controllers/CustomerRegionController.php';

$pdo = $GLOBALS['pdo'];
$customerRegionController = new CustomerRegionController($pdo);

return [
    'GET /customer-regions/' => function () use ($customerRegionController) {
        $customerRegionController->getAllRecords();
    },
    'GET /customer-regions/{id}/' => function ($id) use ($customerRegionController) {
        $customerRegionController->getRecordById($id);
    },
    'POST /customer-regions/' => function () use ($customerRegionController) {
        $customerRegionController->createRecord();
    },
    'PUT /customer-regions/{id}/' => function ($id) use ($customerRegionController) {
        $customerRegionController->updateRecord($id);
    },
    'DELETE /customer-regions/{id}/' => function ($id) use ($customerRegionController) {
        $customerRegionController->deleteRecord($id);
    }
];
