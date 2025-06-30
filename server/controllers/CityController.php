<?php
require_once './models/City.php';

class CityController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new City($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllCities();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getCityById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'City not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['district_id'])) {
            $this->model->createCity($data);
            http_response_code(201);
            echo json_encode(['message' => 'City created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['district_id'])) {
            $this->model->updateCity($id, $data);
            echo json_encode(['message' => 'City updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteCity($id);
        echo json_encode(['message' => 'City deleted successfully']);
    }
}
?>