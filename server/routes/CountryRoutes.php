<?php

require_once './controllers/CountryController.php';

$pdo = $GLOBALS['pdo'];
$countryController = new CountryController($pdo);

return [
    'GET /countries/' => function () use ($countryController) {
        $countryController->getAllRecords();
    },
    'GET /countries/{id}/' => function ($id) use ($countryController) {
        $countryController->getRecordById($id);
    },
    'POST /countries/' => function () use ($countryController) {
        $countryController->createRecord();
    },
    'PUT /countries/{id}/' => function ($id) use ($countryController) {
        $countryController->updateRecord($id);
    },
    'DELETE /countries/{id}/' => function ($id) use ($countryController) {
        $countryController->deleteRecord($id);
    }
];
