<?php
require_once './models/CustomerRoute.php';

class CustomerRouteController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new CustomerRoute($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllRoutes();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getRouteById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer route not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['route_title']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['created_at']) && isset($data['region_id'])
        ) {

            $this->model->createRoute($data);
            http_response_code(201);
            echo json_encode(['message' => 'Customer route created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['route_title']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['region_id'])
        ) {

            $this->model->updateRoute($id, $data);
            echo json_encode(['message' => 'Customer route updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteRoute($id);
        echo json_encode(['message' => 'Customer route deleted successfully']);
    }
}
?>