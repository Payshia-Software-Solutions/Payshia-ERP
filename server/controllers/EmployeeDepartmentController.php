<?php
require_once './models/EmployeeDepartment.php';

class EmployeeDepartmentController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeDepartment($pdo);
    }

    public function getAll()
    {
        echo json_encode($this->model->getAll());
    }

    public function getById($id)
    {
        $record = $this->model->getById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['department_name'], $data['is_active'], $data['created_at'], $data['created_by'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['department_name'], $data['is_active'], $data['updated_at'], $data['created_by'])) {
            $this->model->update($id, $data);
            echo json_encode(['message' => 'Updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Deleted successfully']);
    }
}
