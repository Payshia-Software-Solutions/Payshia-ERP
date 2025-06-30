<?php
require_once './models/EmployeeSalaryTemplate.php';

class EmployeeSalaryTemplateController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeSalaryTemplate($pdo);
    }

    // Get all templates
    public function getAllRecords()
    {
        $records = $this->model->getAllTemplates();
        echo json_encode($records);
    }

    // Get a single template by ID
    public function getRecordById($id)
    {
        $record = $this->model->getTemplateById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Template not found']);
        }
    }

    // Create a new template
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['template_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createTemplate($data);
            http_response_code(201);
            echo json_encode(['message' => 'Template created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing template
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['template_name']) && isset($data['is_active']) && isset($data['created_by'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->model->updateTemplate($id, $data);
            echo json_encode(['message' => 'Template updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a template by ID
    public function deleteRecord($id)
    {
        $this->model->deleteTemplate($id);
        echo json_encode(['message' => 'Template deleted successfully']);
    }
}
?>