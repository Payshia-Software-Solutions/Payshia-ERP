<?php
require_once './controllers/TempTransferNoteController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TempTransferNoteController($pdo);

return [
    'GET /temp-transfer-note/' => function () use ($controller) {
        $controller->getAllRecords();
    },
    'GET /temp-transfer-note/{id}/' => function ($id) use ($controller) {
        $controller->getRecordById($id);
    },
    'POST /temp-transfer-note/' => function () use ($controller) {
        $controller->createRecord();
    },
    'PUT /temp-transfer-note/{id}/' => function ($id) use ($controller) {
        $controller->updateRecord($id);
    },
    'DELETE /temp-transfer-note/{id}/' => function ($id) use ($controller) {
        $controller->deleteRecord($id);
    }
];
