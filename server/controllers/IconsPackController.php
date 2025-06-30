<?php
require_once './models/IconsPack.php';

class IconsPackController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new IconsPack($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllIcons();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getIconById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Icon not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['pack_prefix'], $data['icon_name'], $data['category'])) {
            $this->model->createIcon($data);
            http_response_code(201);
            echo json_encode(['message' => 'Icon added successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['pack_prefix'], $data['icon_name'], $data['category'])) {
            $this->model->updateIcon($id, $data);
            echo json_encode(['message' => 'Icon updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteIcon($id);
        echo json_encode(['message' => 'Icon deleted successfully']);
    }
}
?>