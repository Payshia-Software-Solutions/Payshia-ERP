<?php
require_once './models/FinanceChartOfAccounts.php';

class FinanceChartOfAccountsController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new FinanceChartOfAccounts($pdo);
    }

    // Get all records
    public function getAllRecords()
    {
        $records = $this->model->getAllAccounts();
        echo json_encode($records);
    }

    // Get a single record by ID
    public function getRecordById($account_id)
    {
        $record = $this->model->getAccountById($account_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Account not found']);
        }
    }

    // Create a new record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['account_name'], $data['account_type'], $data['created_by'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createAccount($data);
            http_response_code(201);
            echo json_encode(['message' => 'Account created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update a record
    public function updateRecord($account_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['account_name'], $data['account_type'], $data['created_by'])) {
            $this->model->updateAccount($account_id, $data);
            echo json_encode(['message' => 'Account updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a record
    public function deleteRecord($account_id)
    {
        $this->model->deleteAccount($account_id);
        echo json_encode(['message' => 'Account deleted successfully']);
    }
}
?>