<?php
require_once './models/TransactionInvoiceItem.php';

class TransactionInvoiceItemController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionInvoiceItem($pdo);
    }

    public function getAll()
    {
        echo json_encode($this->model->getAll());
    }

    public function getById($id)
    {
        $item = $this->model->getById($id);
        if ($item) {
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Invoice item not found"]);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['user_id'], $data['product_id'], $data['invoice_number'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        $id = $this->model->create($data);
        echo json_encode(["message" => "Invoice item created", "id" => $id]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(["message" => "Invoice item updated"]);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(["message" => "Invoice item deleted"]);
    }
}
