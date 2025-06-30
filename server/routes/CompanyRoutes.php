<?php

require_once './controllers/CompanyController.php';

$pdo = $GLOBALS['pdo'];
$companyController = new CompanyController($pdo);

return [
    'GET /companies/' => function () use ($companyController) {
        $companyController->getAllRecords();
    },
    'GET /companies/{id}/' => function ($id) use ($companyController) {
        $companyController->getRecordById($id);
    },
    'POST /companies/' => function () use ($companyController) {
        $companyController->createRecord();
    },
    'PUT /companies/{id}/' => function ($id) use ($companyController) {
        $companyController->updateRecord($id);
    },
    'DELETE /companies/{id}/' => function ($id) use ($companyController) {
        $companyController->deleteRecord($id);
    }
];
