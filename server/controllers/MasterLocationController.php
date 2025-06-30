<?php
require_once './models/MasterLocation.php';

class LocationController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new Location($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllLocations();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getLocationById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Location not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['location_name']) && isset($data['is_active']) &&
            isset($data['created_at']) && isset($data['created_by']) &&
            isset($data['pos_status']) && isset($data['location_type'])
        ) {
            $this->model->createLocation($data);
            http_response_code(201);
            echo json_encode(['message' => 'Location created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['location_name']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['pos_status']) &&
            isset($data['location_type'])
        ) {
            $this->model->updateLocation($id, $data);
            echo json_encode(['message' => 'Location updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteLocation($id);
        echo json_encode(['message' => 'Location deleted successfully']);
    }
}
?>