<?php

require_once './controllers/CustomerRouteController.php';

$pdo = $GLOBALS['pdo'];
$customerRouteController = new CustomerRouteController($pdo);

return [
    'GET /customer-routes/' => function () use ($customerRouteController) {
        $customerRouteController->getAllRecords();
    },
    'GET /customer-routes/{id}/' => function ($id) use ($customerRouteController) {
        $customerRouteController->getRecordById($id);
    },
    'POST /customer-routes/' => function () use ($customerRouteController) {
        $customerRouteController->createRecord();
    },
    'PUT /customer-routes/{id}/' => function ($id) use ($customerRouteController) {
        $customerRouteController->updateRecord($id);
    },
    'DELETE /customer-routes/{id}/' => function ($id) use ($customerRouteController) {
        $customerRouteController->deleteRecord($id);
    }
];
