<?php

require_once './controllers/MasterLocationController.php';

$pdo = $GLOBALS['pdo'];
$locationController = new LocationController($pdo);

return [
    'GET /master-locations/' => function () use ($locationController) {
        $locationController->getAllRecords();
    },
    'GET /master-locations/{location_id}/' => function ($location_id) use ($locationController) {
        $locationController->getRecordById($location_id);
    },
    'POST /master-locations/' => function () use ($locationController) {
        $locationController->createRecord();
    },
    'PUT /master-locations/{location_id}/' => function ($location_id) use ($locationController) {
        $locationController->updateRecord($location_id);
    },
    'DELETE /master-locations/{location_id}/' => function ($location_id) use ($locationController) {
        $locationController->deleteRecord($location_id);
    }
];
