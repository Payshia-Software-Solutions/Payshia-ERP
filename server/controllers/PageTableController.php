<?php
require_once './models/PageTable.php';

class PageTableController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PageTable($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllPages();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getPageById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Page not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['page_name'], $data['display_name'], $data['pack_icon'], $data['is_active'])) {
            // Optional fields: type, root, open_type
            $data['type'] = $data['type'] ?? null;
            $data['root'] = $data['root'] ?? null;
            $data['open_type'] = $data['open_type'] ?? '_self';

            $this->model->createPage($data);
            http_response_code(201);
            echo json_encode(['message' => 'Page created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['page_name'], $data['display_name'], $data['pack_icon'], $data['is_active'])) {
            $data['type'] = $data['type'] ?? null;
            $data['root'] = $data['root'] ?? null;
            $data['open_type'] = $data['open_type'] ?? '_self';

            $this->model->updatePage($id, $data);
            echo json_encode(['message' => 'Page updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deletePage($id);
        echo json_encode(['message' => 'Page deleted successfully']);
    }
}
?>