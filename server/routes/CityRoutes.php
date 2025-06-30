<?php

require_once './controllers/CityController.php';

$pdo = $GLOBALS['pdo'];
$cityController = new CityController($pdo);

return [
    'GET /cities/' => function () use ($cityController) {
        $cityController->getAllRecords();
    },
    'GET /cities/{id}/' => function ($id) use ($cityController) {
        $cityController->getRecordById($id);
    },
    'POST /cities/' => function () use ($cityController) {
        $cityController->createRecord();
    },
    'PUT /cities/{id}/' => function ($id) use ($cityController) {
        $cityController->updateRecord($id);
    },
    'DELETE /cities/{id}/' => function ($id) use ($cityController) {
        $cityController->deleteRecord($id);
    }
];
