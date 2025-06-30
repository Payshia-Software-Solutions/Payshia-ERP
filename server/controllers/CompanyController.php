<?php
require_once './models/Company.php';

class CompanyController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Company($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllCompanies();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getCompanyById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Company not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['company_name'])) {
            $this->model->createCompany($data);
            http_response_code(201);
            echo json_encode(['message' => 'Company created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['company_name'])) {
            $this->model->updateCompany($id, $data);
            echo json_encode(['message' => 'Company updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteCompany($id);
        echo json_encode(['message' => 'Company deleted successfully']);
    }
}
?>