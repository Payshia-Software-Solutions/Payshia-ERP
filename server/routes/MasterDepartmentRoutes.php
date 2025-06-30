<?php

require_once './controllers/MasterDepartmentController.php';

$pdo = $GLOBALS['pdo'];
$departmentController = new DepartmentController($pdo);

return [
    'GET /master-departments/' => function () use ($departmentController) {
        $departmentController->getAllRecords();
    },
    'GET /master-departments/{id}/' => function ($id) use ($departmentController) {
        $departmentController->getRecordById((int) $id);
    },
    'POST /master-departments/' => function () use ($departmentController) {
        $departmentController->createRecord();
    },
    'PUT /master-departments/{id}/' => function ($id) use ($departmentController) {
        $departmentController->updateRecord((int) $id);
    },
    'DELETE /master-departments/{id}/' => function ($id) use ($departmentController) {
        $departmentController->deleteRecord((int) $id);
    }
];
?>