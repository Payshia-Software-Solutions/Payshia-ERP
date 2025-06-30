<?php
require_once './models/MasterSupplier.php';

class SupplierController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Supplier($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllSuppliers();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getSupplierById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Supplier not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset(
            $data['supplier_name'],
            $data['opening_balance'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $data['email'],
            $data['contact_person'],
            $data['street_name'],
            $data['city'],
            $data['zip_code'],
            $data['telephone'],
            $data['fax']
        )
        ) {
            $this->model->createSupplier($data);
            http_response_code(201);
            echo json_encode(['message' => 'Supplier created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset(
            $data['supplier_name'],
            $data['opening_balance'],
            $data['is_active'],
            $data['created_by'],
            $data['email'],
            $data['contact_person'],
            $data['street_name'],
            $data['city'],
            $data['zip_code'],
            $data['telephone'],
            $data['fax']
        )
        ) {
            $this->model->updateSupplier($id, $data);
            echo json_encode(['message' => 'Supplier updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteSupplier($id);
        echo json_encode(['message' => 'Supplier deleted successfully']);
    }
}
?>