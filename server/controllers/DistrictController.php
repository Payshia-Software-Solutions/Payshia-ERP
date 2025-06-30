<?php
require_once './models/District.php';

class DistrictController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new District($pdo);
    }

    public function getAllRecords()
    {
        echo json_encode($this->model->getAllDistricts());
    }

    public function getRecordById($id)
    {
        $record = $this->model->getDistrictById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'District not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['province_id'], $data['name_en'], $data['name_si'], $data['name_ta'])) {
            $this->model->createDistrict($data);
            http_response_code(201);
            echo json_encode(['message' => 'District created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['province_id'], $data['name_en'], $data['name_si'], $data['name_ta'])) {
            $this->model->updateDistrict($id, $data);
            echo json_encode(['message' => 'District updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteDistrict($id);
        echo json_encode(['message' => 'District deleted successfully']);
    }
}
?>