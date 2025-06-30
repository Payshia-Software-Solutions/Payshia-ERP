<?php

require_once './controllers/FinanceChartOfAccountsController.php';

$financeAccountsController = new FinanceChartOfAccountsController($pdo);

return [
    // Other routes...

    'GET /accounts/' => function () use ($financeAccountsController) {
        $financeAccountsController->getAllRecords();
    },
    'GET /accounts/{account_id}/' => function ($account_id) use ($financeAccountsController) {
        $financeAccountsController->getRecordById($account_id);
    },
    'POST /accounts/' => function () use ($financeAccountsController) {
        $financeAccountsController->createRecord();
    },
    'PUT /accounts/{account_id}/' => function ($account_id) use ($financeAccountsController) {
        $financeAccountsController->updateRecord($account_id);
    },
    'DELETE /accounts/{account_id}/' => function ($account_id) use ($financeAccountsController) {
        $financeAccountsController->deleteRecord($account_id);
    }
];
