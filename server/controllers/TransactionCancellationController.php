<?php
require_once './models/TransactionCancellation.php';

class TransactionCancellationController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionCancellation($pdo);
    }

    public function getAllRecords()
    {
        echo json_encode($this->model->getAll());
    }

    public function getRecordById($id)
    {
        $record = $this->model->getById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['cancellation_type'], $data['ref_key'], $data['reason'], $data['created_by'], $data['created_at'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(['message' => 'Record updated successfully']);
    }

    public function deleteRecord($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Record deleted successfully']);
    }
}
?>