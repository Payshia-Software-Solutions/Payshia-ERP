<?php
require_once './models/CustomerArea.php';

class CustomerAreaController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new CustomerArea($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllAreas();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getAreaById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer area not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['area_title']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['created_at']) &&
            isset($data['route_id']) && isset($data['region_id'])
        ) {

            $this->model->createArea($data);
            http_response_code(201);
            echo json_encode(['message' => 'Customer area created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['area_title']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['route_id']) &&
            isset($data['region_id'])
        ) {

            $this->model->updateArea($id, $data);
            echo json_encode(['message' => 'Customer area updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteArea($id);
        echo json_encode(['message' => 'Customer area deleted successfully']);
    }
}
?>