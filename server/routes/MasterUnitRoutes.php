<?php
require_once './controllers/MasterUnitController.php';

$pdo = $GLOBALS['pdo'];
$unitController = new UnitController($pdo);

return [
    'GET /master-units/' => function () use ($unitController) {
        $unitController->getAllRecords();
    },
    'GET /master-units/{unit_id}/' => function ($unit_id) use ($unitController) {
        $unitController->getRecordById($unit_id);
    },
    'POST /master-units/' => function () use ($unitController) {
        $unitController->createRecord();
    },
    'PUT /master-units/{unit_id}/' => function ($unit_id) use ($unitController) {
        $unitController->updateRecord($unit_id);
    },
    'DELETE /master-units/{unit_id}/' => function ($unit_id) use ($unitController) {
        $unitController->deleteRecord($unit_id);
    }
];
