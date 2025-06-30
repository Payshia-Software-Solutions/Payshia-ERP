<?php
require_once './models/Country.php';

class CountryController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Country($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllCountries();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getCountryById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Country not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['sortname']) && isset($data['name']) && isset($data['phonecode'])) {
            $this->model->createCountry($data);
            http_response_code(201);
            echo json_encode(['message' => 'Country created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['sortname']) && isset($data['name']) && isset($data['phonecode'])) {
            $this->model->updateCountry($id, $data);
            echo json_encode(['message' => 'Country updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteCountry($id);
        echo json_encode(['message' => 'Country deleted successfully']);
    }
}
?>