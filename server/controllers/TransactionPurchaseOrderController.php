<?php
require_once './models/TransactionPurchaseOrder.php';

class TransactionPurchaseOrderController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionPurchaseOrder($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllPurchaseOrders();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getPurchaseOrderById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Purchase Order not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data && isset($data['po_number']) && isset($data['location_id']) && isset($data['supplier_id']) &&
            isset($data['currency']) && isset($data['tax_type']) && isset($data['sub_total']) &&
            isset($data['created_by']) && isset($data['created_at']) &&
            isset($data['is_active']) && isset($data['po_status']) && isset($data['remarks'])
        ) {

            $this->model->createPurchaseOrder($data);
            http_response_code(201);
            echo json_encode(['message' => 'Purchase Order created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data && isset($data['po_number']) && isset($data['location_id']) && isset($data['supplier_id']) &&
            isset($data['currency']) && isset($data['tax_type']) && isset($data['sub_total']) &&
            isset($data['created_by']) && isset($data['is_active']) && isset($data['po_status']) &&
            isset($data['remarks'])
        ) {

            $this->model->updatePurchaseOrder($id, $data);
            echo json_encode(['message' => 'Purchase Order updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deletePurchaseOrder($id);
        echo json_encode(['message' => 'Purchase Order deleted successfully']);
    }
}
?>