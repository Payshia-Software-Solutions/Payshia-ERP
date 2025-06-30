<?php
require_once './models/EmployeePosition.php';

class EmployeePositionController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeePosition($pdo);
    }

    // Get all position records
    public function getAllRecords()
    {
        $records = $this->model->getAllPositions();
        echo json_encode($records);
    }

    // Get a single position by ID
    public function getRecordById($id)
    {
        $record = $this->model->getPositionById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Position not found']);
        }
    }

    // Create a new position
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['position_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createPosition($data);
            http_response_code(201);
            echo json_encode(['message' => 'Position created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing position
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['position_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->model->updatePosition($id, $data);
            echo json_encode(['message' => 'Position updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a position by ID
    public function deleteRecord($id)
    {
        $this->model->deletePosition($id);
        echo json_encode(['message' => 'Position deleted successfully']);
    }
}
?>