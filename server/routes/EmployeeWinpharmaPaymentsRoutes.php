<?php

require_once './controllers/EmployeeWinpharmaPaymentsController.php';

$employeeWinpharmaPaymentsController = new EmployeeWinpharmaPaymentsController($pdo);

return [
    // Other routes...

    // Employee Winpharma Payments routes
    'GET /winpharma-payments/' => function () use ($employeeWinpharmaPaymentsController) {
        $employeeWinpharmaPaymentsController->getAllRecords();
    },
    'GET /winpharma-payments/{id}/' => function ($id) use ($employeeWinpharmaPaymentsController) {
        $employeeWinpharmaPaymentsController->getRecordById($id);
    },
    'POST /winpharma-payments/' => function () use ($employeeWinpharmaPaymentsController) {
        $employeeWinpharmaPaymentsController->createRecord();
    },
    'PUT /winpharma-payments/{id}/' => function ($id) use ($employeeWinpharmaPaymentsController) {
        $employeeWinpharmaPaymentsController->updateRecord($id);
    },
    'DELETE /winpharma-payments/{id}/' => function ($id) use ($employeeWinpharmaPaymentsController) {
        $employeeWinpharmaPaymentsController->deleteRecord($id);
    }
];