<?php
require_once './models/MasterUnit.php';

class UnitController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Unit($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllUnits();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getUnitById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Unit not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['unit_name'], $data['is_active'], $data['created_by'], $data['created_at'])) {
            $this->model->createUnit($data);
            http_response_code(201);
            echo json_encode(['message' => 'Unit created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['unit_name'], $data['is_active'], $data['created_by'], $data['created_at'])) {
            $this->model->updateUnit($id, $data);
            echo json_encode(['message' => 'Unit updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteUnit($id);
        echo json_encode(['message' => 'Unit deleted successfully']);
    }
}
?>