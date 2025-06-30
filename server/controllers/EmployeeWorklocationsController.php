<?php
require_once './models/EmployeeWorklocations.php';

class EmployeeWorklocationsController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeWorklocations($pdo);
    }

    // Get all locations
    public function getAllRecords()
    {
        $records = $this->model->getAllLocations();
        echo json_encode($records);
    }

    // Get single location by ID
    public function getRecordById($id)
    {
        $record = $this->model->getLocationById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Work location not found']);
        }
    }

    // Create a new location
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['work_location_name'], $data['is_active'], $data['created_by'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createLocation($data);
            http_response_code(201);
            echo json_encode(['message' => 'Work location created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing location
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['work_location_name'], $data['is_active'], $data['created_by'])) {
            $this->model->updateLocation($id, $data);
            echo json_encode(['message' => 'Work location updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a location
    public function deleteRecord($id)
    {
        $this->model->deleteLocation($id);
        echo json_encode(['message' => 'Work location deleted successfully']);
    }
}
?>