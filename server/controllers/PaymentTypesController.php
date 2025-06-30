<?php
require_once './models/PaymentTypes.php';

class PaymentTypesController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PaymentTypes($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAll();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Payment type not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['text'], $data['is_active'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Payment type created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['text'], $data['is_active'])) {
            $this->model->update($id, $data);
            echo json_encode(['message' => 'Payment type updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Payment type deleted successfully']);
    }
}
?>