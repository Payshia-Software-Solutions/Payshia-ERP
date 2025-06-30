<?php

require_once './controllers/EmployeeSalaryTemplateController.php';

$employeeSalaryTemplateController = new EmployeeSalaryTemplateController($pdo);

return [
    // Other routes...

    // Employee Salary Template routes
    'GET /salary-templates/' => function () use ($employeeSalaryTemplateController) {
        $employeeSalaryTemplateController->getAllRecords();
    },
    'GET /salary-templates/{id}/' => function ($id) use ($employeeSalaryTemplateController) {
        $employeeSalaryTemplateController->getRecordById($id);
    },
    'POST /salary-templates/' => function () use ($employeeSalaryTemplateController) {
        $employeeSalaryTemplateController->createRecord();
    },
    'PUT /salary-templates/{id}/' => function ($id) use ($employeeSalaryTemplateController) {
        $employeeSalaryTemplateController->updateRecord($id);
    },
    'DELETE /salary-templates/{id}/' => function ($id) use ($employeeSalaryTemplateController) {
        $employeeSalaryTemplateController->deleteRecord($id);
    }
];