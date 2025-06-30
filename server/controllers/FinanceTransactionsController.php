<?php
require_once './models/FinanceTransactions.php';

class FinanceTransactionsController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new FinanceTransactions($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllTransactions();
        echo json_encode($records);
    }

    public function getRecordById($transaction_id)
    {
        $record = $this->model->getTransactionById($transaction_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Transaction not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            isset(
            $data['debit_account_id'],
            $data['credit_account_id'],
            $data['amount'],
            $data['transaction_date'],
            $data['location_id'],
            $data['created_by']
        )
        ) {
            $data['timestamp'] = date('Y-m-d H:i:s');
            $this->model->createTransaction($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($transaction_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            isset(
            $data['debit_account_id'],
            $data['credit_account_id'],
            $data['amount'],
            $data['transaction_date'],
            $data['location_id'],
            $data['created_by']
        )
        ) {
            $data['timestamp'] = date('Y-m-d H:i:s');
            $this->model->updateTransaction($transaction_id, $data);
            echo json_encode(['message' => 'Transaction updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($transaction_id)
    {
        $this->model->deleteTransaction($transaction_id);
        echo json_encode(['message' => 'Transaction deleted successfully']);
    }
}
?>