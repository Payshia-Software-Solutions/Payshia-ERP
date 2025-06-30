<?php
require_once './models/EmployeeWinpharmaPayments.php';

class EmployeeWinpharmaPaymentsController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new EmployeeWinpharmaPayments($pdo);
    }

    // Get all payment records
    public function getAllRecords()
    {
        $records = $this->model->getAllPayments();
        echo json_encode($records);
    }

    // Get a single payment record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getPaymentById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Payment record not found']);
        }
    }

    // Create a new payment record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['employee_number'], $data['marking_count'], $data['payment'], $data['is_active'], $data['payment_approval'], $data['created_by'])) {
            // Set created_at as current datetime with microseconds
            $data['created_at'] = date('Y-m-d H:i:s.u');
            $this->model->createPayment($data);
            http_response_code(201);
            echo json_encode(['message' => 'Payment record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing payment record
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['employee_number'], $data['marking_count'], $data['payment'], $data['is_active'], $data['payment_approval'], $data['update_by'])) {
            $this->model->updatePayment($id, $data);
            echo json_encode(['message' => 'Payment record updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a payment record
    public function deleteRecord($id)
    {
        $this->model->deletePayment($id);
        echo json_encode(['message' => 'Payment record deleted successfully']);
    }
}
?>