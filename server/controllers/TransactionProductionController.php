<?php
require_once './models/TransactionProduction.php';

class TransactionProductionController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionProduction($pdo);
    }

    public function getAll()
    {
        echo json_encode($this->model->getAll());
    }

    public function getById($id)
    {
        $production = $this->model->getById($id);
        if ($production) {
            echo json_encode($production);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Production record not found"]);
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $this->model->create($data);
        echo json_encode(["message" => "Production created", "id" => $id]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(["message" => "Production updated"]);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(["message" => "Production deleted"]);
    }
}
