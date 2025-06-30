<?php
require_once './controllers/SettingLocationController.php';

$pdo = $GLOBALS['pdo'];
$settingLocationController = new SettingLocationController($pdo);

return [
    'GET /setting-location/' => function () use ($settingLocationController) {
        $settingLocationController->getAllRecords();
    },
    'GET /setting-location/{id}/' => function ($id) use ($settingLocationController) {
        $settingLocationController->getRecordById($id);
    },
    'POST /setting-location/' => function () use ($settingLocationController) {
        $settingLocationController->createRecord();
    },
    'PUT /setting-location/{id}/' => function ($id) use ($settingLocationController) {
        $settingLocationController->updateRecord($id);
    },
    'DELETE /setting-location/{id}/' => function ($id) use ($settingLocationController) {
        $settingLocationController->deleteRecord($id);
    }
];
