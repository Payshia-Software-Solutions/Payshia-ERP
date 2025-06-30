<?php
require_once './models/TransactionExpenses.php';

class TransactionExpensesController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionExpenses($pdo);
    }

    public function getAll()
    {
        echo json_encode($this->model->getAll());
    }

    public function getById($id)
    {
        $data = $this->model->getById($id);
        if ($data) {
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['expense_id'], $data['ex_type'], $data['ex_description'], $data['amount'], $data['updated_by'], $data['updated_at'], $data['location_id'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Expense record created']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(['message' => 'Expense record updated']);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Expense record deleted']);
    }
}
?>