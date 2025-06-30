<?php

require_once './controllers/FinanceTransactionsController.php';

$financeTransactionsController = new FinanceTransactionsController($pdo);

return [
    // Other routes...

    'GET /transactions/' => function () use ($financeTransactionsController) {
        $financeTransactionsController->getAllRecords();
    },
    'GET /transactions/{transaction_id}/' => function ($transaction_id) use ($financeTransactionsController) {
        $financeTransactionsController->getRecordById($transaction_id);
    },
    'POST /transactions/' => function () use ($financeTransactionsController) {
        $financeTransactionsController->createRecord();
    },
    'PUT /transactions/{transaction_id}/' => function ($transaction_id) use ($financeTransactionsController) {
        $financeTransactionsController->updateRecord($transaction_id);
    },
    'DELETE /transactions/{transaction_id}/' => function ($transaction_id) use ($financeTransactionsController) {
        $financeTransactionsController->deleteRecord($transaction_id);
    }
];