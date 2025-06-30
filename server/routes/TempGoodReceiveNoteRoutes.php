<?php
require_once './controllers/TempGoodReceiveNoteController.php';

$pdo = $GLOBALS['pdo'];
$controller = new TempGoodReceiveNoteController($pdo);

return [
    'GET /temp-good-receive-note/' => function () use ($controller) {
        $controller->getAllRecords();
    },
    'GET /temp-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->getRecordById($id);
    },
    'POST /temp-good-receive-note/' => function () use ($controller) {
        $controller->createRecord();
    },
    'PUT /temp-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->updateRecord($id);
    },
    'DELETE /temp-good-receive-note/{id}/' => function ($id) use ($controller) {
        $controller->deleteRecord($id);
    }
];
