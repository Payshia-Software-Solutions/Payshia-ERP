<?php
require_once './controllers/MasterSupplierController.php';

$pdo = $GLOBALS['pdo'];
$supplierController = new SupplierController($pdo);

return [
    'GET /master-suppliers/' => function () use ($supplierController) {
        $supplierController->getAllRecords();
    },
    'GET /master-suppliers/{id}/' => function ($id) use ($supplierController) {
        $supplierController->getRecordById($id);
    },
    'POST /master-suppliers/' => function () use ($supplierController) {
        $supplierController->createRecord();
    },
    'PUT /master-suppliers/{id}/' => function ($id) use ($supplierController) {
        $supplierController->updateRecord($id);
    },
    'DELETE /master-suppliers/{id}/' => function ($id) use ($supplierController) {
        $supplierController->deleteRecord($id);
    }
];
