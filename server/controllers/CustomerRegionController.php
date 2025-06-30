<?php
require_once './models/CustomerRegion.php';

class CustomerRegionController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new CustomerRegion($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllRegions();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getRegionById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer region not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['region_name']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['created_at'])
        ) {

            $this->model->createRegion($data);
            http_response_code(201);
            echo json_encode(['message' => 'Customer region created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['region_name']) && isset($data['is_active']) &&
            isset($data['created_by'])
        ) {

            $this->model->updateRegion($id, $data);
            echo json_encode(['message' => 'Customer region updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteRegion($id);
        echo json_encode(['message' => 'Customer region deleted successfully']);
    }
}
?>