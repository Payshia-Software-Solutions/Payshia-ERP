<?php
require_once './controllers/SettingDefaultValuesController.php';

$pdo = $GLOBALS['pdo'];
$settingDefaultValuesController = new SettingDefaultValuesController($pdo);

return [
    'GET /setting-default-values/' => function () use ($settingDefaultValuesController) {
        $settingDefaultValuesController->getAllRecords();
    },
    'GET /setting-default-values/{id}/' => function ($id) use ($settingDefaultValuesController) {
        $settingDefaultValuesController->getRecordById($id);
    },
    'POST /setting-default-values/' => function () use ($settingDefaultValuesController) {
        $settingDefaultValuesController->createRecord();
    },
    'PUT /setting-default-values/{id}/' => function ($id) use ($settingDefaultValuesController) {
        $settingDefaultValuesController->updateRecord($id);
    },
    'DELETE /setting-default-values/{id}/' => function ($id) use ($settingDefaultValuesController) {
        $settingDefaultValuesController->deleteRecord($id);
    }
];
