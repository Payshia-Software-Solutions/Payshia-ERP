<?php
require_once './controllers/EmployeePositionController.php';

$employeePositionController = new EmployeePositionController($pdo);

return [
    // Existing category routes...

    // Employee Position routes
    'GET /positions/' => function () use ($employeePositionController) {
        $employeePositionController->getAllRecords();
    },
    'GET /positions/{id}/' => function ($id) use ($employeePositionController) {
        $employeePositionController->getRecordById($id);
    },
    'POST /positions/' => function () use ($employeePositionController) {
        $employeePositionController->createRecord();
    },
    'PUT /positions/{id}/' => function ($id) use ($employeePositionController) {
        $employeePositionController->updateRecord($id);
    },
    'DELETE /positions/{id}/' => function ($id) use ($employeePositionController) {
        $employeePositionController->deleteRecord($id);
    }
];