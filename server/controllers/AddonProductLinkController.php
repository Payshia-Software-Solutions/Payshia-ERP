<?php
require_once './models/AddonProductLink.php';

class AddonProductLinkController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new AddonProductLink($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllLinks();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getLinkById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['product_code']) && isset($data['reference_id']) &&
            isset($data['created_by']) && isset($data['created_at']) && isset($data['is_active'])
        ) {

            $this->model->createLink($data);
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['product_code']) && isset($data['reference_id']) &&
            isset($data['created_by']) && isset($data['is_active'])
        ) {

            $this->model->updateLink($id, $data);
            echo json_encode(['message' => 'Record updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteLink($id);
        echo json_encode(['message' => 'Record deleted successfully']);
    }
}
?>