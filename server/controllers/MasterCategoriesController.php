<?php

require_once './models/MasterCategories.php';

class CategoriesController
{
    private Categories $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Categories($pdo);
    }

    public function getAllRecords(): void
    {
        $records = $this->model->getAllCategories();
        header('Content-Type: application/json');
        echo json_encode($records);
    }

    public function getRecordById(int $id): void
    {
        $record = $this->model->getCategoryById($id);
        header('Content-Type: application/json');
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }

    public function createRecord(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        header('Content-Type: application/json');

        if ($this->validateData($data)) {
            $newId = $this->model->createCategory($data);
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully', 'id' => $newId]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord(int $id): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        header('Content-Type: application/json');

        if ($this->validateData($data)) {
            $rowsAffected = $this->model->updateCategory($id, $data);
            if ($rowsAffected > 0) {
                echo json_encode(['message' => 'Category updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Category not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord(int $id): void
    {
        header('Content-Type: application/json');
        $rowsDeleted = $this->model->deleteCategory($id);

        if ($rowsDeleted > 0) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }

    private function validateData(?array $data): bool
    {
        if (!$data)
            return false;

        $requiredFields = ['section_id', 'department_id', 'category_name', 'is_active', 'created_by', 'pos_display'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }
}
?>