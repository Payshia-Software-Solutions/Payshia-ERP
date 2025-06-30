<?php
require_once './models/TransactionExpensesTypes.php';

class TransactionExpensesTypesController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionExpensesTypes($pdo);
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
            echo json_encode(['error' => 'Not found']);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['type'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction Expenses Types Created']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: type']);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(['message' => 'Transaction Expenses Types Updated']);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Transaction Expenses Types Deleted']);
    }
}
