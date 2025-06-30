<?php

require_once './controllers/EmployeeSalaryKeyController.php';

$employeeSalaryKeyController = new EmployeeSalaryKeyController($pdo);

return [
    // Existing routes...

    // Employee Salary Key routes
    'GET /salary-keys/' => function () use ($employeeSalaryKeyController) {
        $employeeSalaryKeyController->getAllRecords();
    },
    'GET /salary-keys/{id}/' => function ($id) use ($employeeSalaryKeyController) {
        $employeeSalaryKeyController->getRecordById($id);
    },
    'POST /salary-keys/' => function () use ($employeeSalaryKeyController) {
        $employeeSalaryKeyController->createRecord();
    },
    'PUT /salary-keys/{id}/' => function ($id) use ($employeeSalaryKeyController) {
        $employeeSalaryKeyController->updateRecord($id);
    },
    'DELETE /salary-keys/{id}/' => function ($id) use ($employeeSalaryKeyController) {
        $employeeSalaryKeyController->deleteRecord($id);
    }
];