<?php
require_once './controllers/DeliveryPartnerController.php';

$pdo = $GLOBALS['pdo'];
$deliveryPartnerController = new DeliveryPartnerController($pdo);

return [
    'GET /delivery-partners/' => function () use ($deliveryPartnerController) {
        $deliveryPartnerController->getAllRecords();
    },
    'GET /delivery-partners/{id}/' => function ($id) use ($deliveryPartnerController) {
        $deliveryPartnerController->getRecordById($id);
    },
    'POST /delivery-partners/' => function () use ($deliveryPartnerController) {
        $deliveryPartnerController->createRecord();
    },
    'PUT /delivery-partners/{id}/' => function ($id) use ($deliveryPartnerController) {
        $deliveryPartnerController->updateRecord($id);
    },
    'DELETE /delivery-partners/{id}/' => function ($id) use ($deliveryPartnerController) {
        $deliveryPartnerController->deleteRecord($id);
    }
];
