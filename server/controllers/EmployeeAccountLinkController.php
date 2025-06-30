<?php
require_once './models/EmployeeAccountLink.php';

class EmployeeAccountLinkController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeAccountLink($pdo);
    }

    public function getAllRecords()
    {
        echo json_encode($this->model->getAllLinks());
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
        if ($data && isset($data['account_type'], $data['employee_id'], $data['user_id'], $data['is_active'], $data['created_at'], $data['created_by'])) {
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
        if ($data && isset($data['account_type'], $data['employee_id'], $data['user_id'], $data['is_active'], $data['updated_at'], $data['created_by'])) {
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