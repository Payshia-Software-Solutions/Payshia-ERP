<?php
require_once './models/EmployeeTemplatesKey.php';

class EmployeeTemplatesKeyController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeTemplatesKey($pdo);
    }

    // Get all template keys
    public function getAllRecords()
    {
        $records = $this->model->getAllTemplateKeys();
        echo json_encode($records);
    }

    // Get a single template key by ID
    public function getRecordById($id)
    {
        $record = $this->model->getTemplateKeyById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Template key not found']);
        }
    }

    // Create a new template key
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['template_id'], $data['set_key'], $data['is_active'], $data['created_by'], $data['pay_mode'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            try {
                $this->model->createTemplateKey($data);
                http_response_code(201);
                echo json_encode(['message' => 'Template key created successfully']);
            } catch (PDOException $e) {
                if ($e->errorInfo[1] == 1062) { // Duplicate entry error code
                    http_response_code(409);
                    echo json_encode(['error' => 'Duplicate template_id and set_key combination']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing template key
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['template_id'], $data['set_key'], $data['is_active'], $data['created_by'], $data['pay_mode'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            try {
                $this->model->updateTemplateKey($id, $data);
                echo json_encode(['message' => 'Template key updated successfully']);
            } catch (PDOException $e) {
                if ($e->errorInfo[1] == 1062) {
                    http_response_code(409);
                    echo json_encode(['error' => 'Duplicate template_id and set_key combination']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a template key by ID
    public function deleteRecord($id)
    {
        $this->model->deleteTemplateKey($id);
        echo json_encode(['message' => 'Template key deleted successfully']);
    }
}
?>