<?php

require_once './controllers/IconsPackController.php';

$iconsPackController = new IconsPackController($pdo);

return [
    // Icons Pack Routes
    'GET /icons/' => function () use ($iconsPackController) {
        $iconsPackController->getAllRecords();
    },
    'GET /icons/{id}/' => function ($id) use ($iconsPackController) {
        $iconsPackController->getRecordById($id);
    },
    'POST /icons/' => function () use ($iconsPackController) {
        $iconsPackController->createRecord();
    },
    'PUT /icons/{id}/' => function ($id) use ($iconsPackController) {
        $iconsPackController->updateRecord($id);
    },
    'DELETE /icons/{id}/' => function ($id) use ($iconsPackController) {
        $iconsPackController->deleteRecord($id);
    }
];
