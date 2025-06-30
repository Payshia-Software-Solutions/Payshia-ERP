<?php
require_once './models/TransactionQuotation.php';

class TransactionQuotationController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionQuotation($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllQuotations();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getQuotationById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Quotation not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data &&
            isset($data['quote_number']) &&
            isset($data['quote_date']) &&
            isset($data['quote_amount']) &&
            isset($data['grand_total']) &&
            isset($data['discount_amount']) &&
            isset($data['discount_percentage']) &&
            isset($data['customer_code']) &&
            isset($data['service_charge']) &&
            isset($data['invoice_status']) &&
            isset($data['current_time']) &&
            isset($data['location_id']) &&
            isset($data['created_by']) &&
            isset($data['cost_value'])
        ) {
            $this->model->createQuotation($data);
            http_response_code(201);
            echo json_encode(['message' => 'Quotation created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data &&
            isset($data['quote_number']) &&
            isset($data['quote_date']) &&
            isset($data['quote_amount']) &&
            isset($data['grand_total']) &&
            isset($data['discount_amount']) &&
            isset($data['discount_percentage']) &&
            isset($data['customer_code']) &&
            isset($data['service_charge']) &&
            isset($data['invoice_status']) &&
            isset($data['current_time']) &&
            isset($data['location_id']) &&
            isset($data['created_by']) &&
            isset($data['cost_value']) &&
            isset($data['is_active'])
        ) {
            $this->model->updateQuotation($id, $data);
            echo json_encode(['message' => 'Quotation updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteQuotation($id);
        echo json_encode(['message' => 'Quotation deleted successfully']);
    }
}
?>