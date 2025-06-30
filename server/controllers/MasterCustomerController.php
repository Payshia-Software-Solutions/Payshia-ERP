<?php

require_once './models/MasterCustomer.php';

class CustomerController
{
    private Customer $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Customer($pdo);
    }

    public function getAllRecords(): void
    {
        header('Content-Type: application/json');
        $records = $this->model->getAllCustomers();
        echo json_encode($records);
    }

    public function getRecordById(int $id): void
    {
        header('Content-Type: application/json');
        $record = $this->model->getCustomerById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer not found']);
        }
    }

    public function createRecord(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->validateData($data)) {
            $newId = $this->model->createCustomer($data);
            http_response_code(201);
            echo json_encode(['message' => 'Customer created successfully', 'id' => $newId]);
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
            $rowsAffected = $this->model->updateCustomer($id, $data);
            if ($rowsAffected > 0) {
                echo json_encode(['message' => 'Customer updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Customer not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord(int $id): void
    {
        header('Content-Type: application/json');
        $rowsDeleted = $this->model->deleteCustomer($id);
        if ($rowsDeleted > 0) {
            echo json_encode(['message' => 'Customer deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer not found']);
        }
    }

    private function validateData(?array $data): bool
    {
        if (!$data)
            return false;

        $requiredFields = [
            'customer_first_name',
            'customer_last_name',
            'phone_number',
            'address_line1',
            'address_line2',
            'city_id',
            'opening_balance',
            'created_by',
            'created_at',
            'company_id',
            'location_id',
            'is_active',
            'credit_limit',
            'credit_days',
            'region_id',
            'route_id',
            'area_id'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        // Optional email_address can be null or string, no need to validate here further

        return true;
    }
}
?>