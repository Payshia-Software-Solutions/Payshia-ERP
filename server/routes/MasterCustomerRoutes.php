<?php

require_once './controllers/MasterCustomerController.php';

$pdo = $GLOBALS['pdo'];
$customerController = new CustomerController($pdo);

return [
    'GET /master-customers/' => function () use ($customerController) {
        $customerController->getAllRecords();
    },
    'GET /master-customers/{customer_id}/' => function ($customer_id) use ($customerController) {
        $customerController->getRecordById((int) $customer_id);
    },
    'POST /master-customers/' => function () use ($customerController) {
        $customerController->createRecord();
    },
    'PUT /master-customers/{customer_id}/' => function ($customer_id) use ($customerController) {
        $customerController->updateRecord((int) $customer_id);
    },
    'DELETE /master-customers/{customer_id}/' => function ($customer_id) use ($customerController) {
        $customerController->deleteRecord((int) $customer_id);
    }
];
?>