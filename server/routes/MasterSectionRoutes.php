<?php
require_once './controllers/MasterSectionController.php';

$pdo = $GLOBALS['pdo'];
$sectionController = new SectionController($pdo);

return [
    'GET /master-sections/' => function () use ($sectionController) {
        $sectionController->getAllRecords();
    },
    'GET /master-sections/{id}/' => function ($id) use ($sectionController) {
        $sectionController->getRecordById($id);
    },
    'POST /master-sections/' => function () use ($sectionController) {
        $sectionController->createRecord();
    },
    'PUT /master-sections/{id}/' => function ($id) use ($sectionController) {
        $sectionController->updateRecord($id);
    },
    'DELETE /master-sections/{id}/' => function ($id) use ($sectionController) {
        $sectionController->deleteRecord($id);
    }
];
