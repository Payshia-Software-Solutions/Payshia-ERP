<?php

require_once './controllers/EmployeeWorklocationsController.php';

$employeeWorklocationsController = new EmployeeWorklocationsController($pdo);

return [
    // Other routes...

    'GET /worklocations/' => function () use ($employeeWorklocationsController) {
        $employeeWorklocationsController->getAllRecords();
    },
    'GET /worklocations/{id}/' => function ($id) use ($employeeWorklocationsController) {
        $employeeWorklocationsController->getRecordById($id);
    },
    'POST /worklocations/' => function () use ($employeeWorklocationsController) {
        $employeeWorklocationsController->createRecord();
    },
    'PUT /worklocations/{id}/' => function ($id) use ($employeeWorklocationsController) {
        $employeeWorklocationsController->updateRecord($id);
    },
    'DELETE /worklocations/{id}/' => function ($id) use ($employeeWorklocationsController) {
        $employeeWorklocationsController->deleteRecord($id);
    }
];