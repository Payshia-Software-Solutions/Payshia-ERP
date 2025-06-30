<?php
require_once './controllers/EmployeeAccountLinkController.php';

$pdo = $GLOBALS['pdo'];
$employeeLinkController = new EmployeeAccountLinkController($pdo);

return [
    'GET /employee-account-links/' => function () use ($employeeLinkController) {
        $employeeLinkController->getAllRecords();
    },
    'GET /employee-account-links/{id}/' => function ($id) use ($employeeLinkController) {
        $employeeLinkController->getRecordById($id);
    },
    'POST /employee-account-links/' => function () use ($employeeLinkController) {
        $employeeLinkController->createRecord();
    },
    'PUT /employee-account-links/{id}/' => function ($id) use ($employeeLinkController) {
        $employeeLinkController->updateRecord($id);
    },
    'DELETE /employee-account-links/{id}/' => function ($id) use ($employeeLinkController) {
        $employeeLinkController->deleteRecord($id);
    }
];
