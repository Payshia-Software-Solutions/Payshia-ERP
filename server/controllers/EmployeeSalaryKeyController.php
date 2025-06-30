<?php
require_once './models/EmployeeSalaryKey.php';

class EmployeeSalaryKeyController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeSalaryKey($pdo);
    }

    // Get all salary key records
    public function getAllRecords()
    {
        $records = $this->model->getAllSalaryKeys();
        echo json_encode($records);
    }

    // Get a single salary key record
    public function getRecordById($id)
    {
        $record = $this->model->getSalaryKeyById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Salary key not found']);
        }
    }

    // Create a new salary key
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['key_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createSalaryKey($data);
            http_response_code(201);
            echo json_encode(['message' => 'Salary key created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing salary key
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['key_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->model->updateSalaryKey($id, $data);
            echo json_encode(['message' => 'Salary key updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a salary key
    public function deleteRecord($id)
    {
        $this->model->deleteSalaryKey($id);
        echo json_encode(['message' => 'Salary key deleted successfully']);
    }
}
?>