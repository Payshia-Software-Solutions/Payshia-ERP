<?php
require_once './controllers/PageTableController.php';

$pdo = $GLOBALS['pdo'];
$pageTableController = new PageTableController($pdo);

return [
    'GET /pages-table/' => function () use ($pageTableController) {
        $pageTableController->getAllRecords();
    },
    'GET /pages-table/{id}/' => function ($id) use ($pageTableController) {
        $pageTableController->getRecordById($id);
    },
    'POST /pages-table/' => function () use ($pageTableController) {
        $pageTableController->createRecord();
    },
    'PUT /pages-table/{id}/' => function ($id) use ($pageTableController) {
        $pageTableController->updateRecord($id);
    },
    'DELETE /pages-table/{id}/' => function ($id) use ($pageTableController) {
        $pageTableController->deleteRecord($id);
    }
];
