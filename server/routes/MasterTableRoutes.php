<?php
require_once './controllers/MasterTableController.php';

$pdo = $GLOBALS['pdo'];
$tableController = new TableController($pdo);

return [
    'GET /master-tables/' => function () use ($tableController) {
        $tableController->getAllRecords();
    },
    'GET /master-tables/{id}/' => function ($id) use ($tableController) {
        $tableController->getRecordById($id);
    },
    'POST /master-tables/' => function () use ($tableController) {
        $tableController->createRecord();
    },
    'PUT /master-tables/{id}/' => function ($id) use ($tableController) {
        $tableController->updateRecord($id);
    },
    'DELETE /master-tables/{id}/' => function ($id) use ($tableController) {
        $tableController->deleteRecord($id);
    }
];
