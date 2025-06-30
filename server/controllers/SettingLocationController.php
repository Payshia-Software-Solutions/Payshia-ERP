<?php
require_once './models/SettingLocation.php';

class SettingLocationController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new SettingLocation($pdo);
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
            echo json_encode(['error' => 'Setting not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['location_id'], $data['setting'], $data['default_value'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Setting created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['location_id'], $data['setting'], $data['default_value'])) {
            $this->model->update($id, $data);
            echo json_encode(['message' => 'Setting updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Setting deleted successfully']);
    }
}
?>