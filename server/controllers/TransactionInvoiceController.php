<?php
require_once './models/TransactionInvoice.php';

class TransactionInvoiceController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionInvoice($pdo);
    }

    public function getAll()
    {
        echo json_encode($this->model->getAll());
    }

    public function getById($id)
    {
        $invoice = $this->model->getById($id);
        if ($invoice) {
            echo json_encode($invoice);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Invoice not found"]);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['invoice_number'], $data['invoice_date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        $id = $this->model->create($data);
        echo json_encode(["message" => "Invoice created", "id" => $id]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(["message" => "Invoice updated"]);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(["message" => "Invoice deleted"]);
    }
}
