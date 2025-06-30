<?php

require_once './controllers/AddonProductLinkController.php';

$pdo = $GLOBALS['pdo'];
$addonProductLinkController = new AddonProductLinkController($pdo);

return [
    'GET /addon-links/' => function () use ($addonProductLinkController) {
        $addonProductLinkController->getAllRecords();
    },
    'GET /addon-links/{id}/' => function ($id) use ($addonProductLinkController) {
        $addonProductLinkController->getRecordById($id);
    },
    'POST /addon-links/' => function () use ($addonProductLinkController) {
        $addonProductLinkController->createRecord();
    },
    'PUT /addon-links/{id}/' => function ($id) use ($addonProductLinkController) {
        $addonProductLinkController->updateRecord($id);
    },
    'DELETE /addon-links/{id}/' => function ($id) use ($addonProductLinkController) {
        $addonProductLinkController->deleteRecord($id);
    }
];
