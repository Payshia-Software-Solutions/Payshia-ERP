<?php
require_once './models/MasterTable.php';

class TableController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Table($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllTables();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getTableById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Table not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['table_name'], $data['is_active'], $data['created_at'], $data['created_by'], $data['location_id'])) {
            $this->model->createTable($data);
            http_response_code(201);
            echo json_encode(['message' => 'Table created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['table_name'], $data['is_active'], $data['created_by'], $data['location_id'])) {
            $this->model->updateTable($id, $data);
            echo json_encode(['message' => 'Table updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteTable($id);
        echo json_encode(['message' => 'Table deleted successfully']);
    }
}
?>