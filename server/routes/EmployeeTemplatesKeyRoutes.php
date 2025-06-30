<?php

require_once './controllers/EmployeeTemplatesKeyController.php';

$employeeTemplatesKeyController = new EmployeeTemplatesKeyController($pdo);

return [
    // Other routes...

    // Employee Templates Key routes
    'GET /template-keys/' => function () use ($employeeTemplatesKeyController) {
        $employeeTemplatesKeyController->getAllRecords();
    },
    'GET /template-keys/{id}/' => function ($id) use ($employeeTemplatesKeyController) {
        $employeeTemplatesKeyController->getRecordById($id);
    },
    'POST /template-keys/' => function () use ($employeeTemplatesKeyController) {
        $employeeTemplatesKeyController->createRecord();
    },
    'PUT /template-keys/{id}/' => function ($id) use ($employeeTemplatesKeyController) {
        $employeeTemplatesKeyController->updateRecord($id);
    },
    'DELETE /template-keys/{id}/' => function ($id) use ($employeeTemplatesKeyController) {
        $employeeTemplatesKeyController->deleteRecord($id);
    }
];