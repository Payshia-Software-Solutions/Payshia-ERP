<?php
require_once './models/TempPurchaseOrder.php';

class TempPurchaseOrderController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TempPurchaseOrder($pdo);
    }

    public function getAllRecords()
    {
        echo json_encode($this->model->getAll());
    }

    public function getRecordById($id)
    {
        $record = $this->model->getById($id);
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
        if (isset($data['user_name'], $data['product_id'], $data['quantity'], $data['order_rate'], $data['added_date'], $data['is_active'], $data['supplier_id'], $data['hold_status'], $data['location_id'], $data['order_unit'], $data['tax_type'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(['message' => 'Record updated successfully']);
    }

    public function deleteRecord($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Record deleted successfully']);
    }
}
?>