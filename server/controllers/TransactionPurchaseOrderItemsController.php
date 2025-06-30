<?php
require_once './models/TransactionPurchaseOrderItems.php';

class TransactionPurchaseOrderItemsController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionPurchaseOrderItems($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllItems();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getItemById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data && isset($data['product_id']) && isset($data['quantity']) &&
            isset($data['order_unit']) && isset($data['order_rate']) &&
            isset($data['created_by']) && isset($data['created_at']) &&
            isset($data['is_active']) && isset($data['po_number'])
        ) {

            $this->model->createItem($data);
            http_response_code(201);
            echo json_encode(['message' => 'Item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            $data && isset($data['product_id']) && isset($data['quantity']) &&
            isset($data['order_unit']) && isset($data['order_rate']) &&
            isset($data['created_by']) && isset($data['is_active']) &&
            isset($data['po_number'])
        ) {

            $this->model->updateItem($id, $data);
            echo json_encode(['message' => 'Item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteItem($id);
        echo json_encode(['message' => 'Item deleted successfully']);
    }
}
?>