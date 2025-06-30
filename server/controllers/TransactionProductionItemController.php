<?php
require_once './models/TransactionProductionItem.php';

class TransactionProductionItemController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionProductionItem($pdo);
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
            echo json_encode(["error" => "Item not found"]);
        }
    }

    public function getByPnId($pn_id)
    {
        $items = $this->model->getByPnId($pn_id);
        echo json_encode($items);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $this->model->create($data);
        echo json_encode(["message" => "Item created", "id" => $id]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->model->update($id, $data);
        echo json_encode(["message" => "Item updated"]);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        echo json_encode(["message" => "Item deleted"]);
    }
}
