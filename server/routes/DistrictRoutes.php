<?php
require_once './controllers/DistrictController.php';

$pdo = $GLOBALS['pdo'];
$districtController = new DistrictController($pdo);

return [
    'GET /districts/' => function () use ($districtController) {
        $districtController->getAllRecords();
    },
    'GET /districts/{id}/' => function ($id) use ($districtController) {
        $districtController->getRecordById($id);
    },
    'POST /districts/' => function () use ($districtController) {
        $districtController->createRecord();
    },
    'PUT /districts/{id}/' => function ($id) use ($districtController) {
        $districtController->updateRecord($id);
    },
    'DELETE /districts/{id}/' => function ($id) use ($districtController) {
        $districtController->deleteRecord($id);
    }
];
