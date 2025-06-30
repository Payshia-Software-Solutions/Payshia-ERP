<?php

require_once './models/MasterDepartment.php';

class DepartmentController
{
    private Department $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Department($pdo);
    }

    public function getAllRecords(): void
    {
        header('Content-Type: application/json');
        $records = $this->model->getAllDepartments();
        echo json_encode($records);
    }

    public function getRecordById(int $id): void
    {
        header('Content-Type: application/json');
        $record = $this->model->getDepartmentById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Department not found']);
        }
    }

    public function createRecord(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->validateData($data)) {
            $newId = $this->model->createDepartment($data);
            http_response_code(201);
            echo json_encode(['message' => 'Department created successfully', 'id' => $newId]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord(int $id): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->validateData($data)) {
            $rowsAffected = $this->model->updateDepartment($id, $data);
            if ($rowsAffected > 0) {
                echo json_encode(['message' => 'Department updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Department not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord(int $id): void
    {
        header('Content-Type: application/json');
        $rowsDeleted = $this->model->deleteDepartment($id);
        if ($rowsDeleted > 0) {
            echo json_encode(['message' => 'Department deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Department not found']);
        }
    }

    private function validateData(?array $data): bool
    {
        if (!$data)
            return false;

        $requiredFields = [
            'section_id',
            'department_name',
            'is_active',
            'created_at',
            'created_by',
            'pos_display'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]))
                return false;
        }

        return true;
    }
}
?>