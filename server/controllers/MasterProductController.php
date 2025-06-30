<?php
require_once './models/MasterProduct.php';

class ProductController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Product($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllProducts();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getProductById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            isset(
            $data['product_code'],
            $data['display_name'],
            $data['print_name'],
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['created_by'],
            $data['created_at'],
            $data['recipe_type'],
            $data['expiry_good'],
            $data['opening_stock']
        )
        ) {

            $this->model->createProduct($data);
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
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
            $data['product_code'],
            $data['display_name'],
            $data['print_name'],
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['created_by'],
            $data['recipe_type'],
            $data['expiry_good'],
            $data['opening_stock']
        )
        ) {

            $this->model->updateProduct($id, $data);
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deleteProduct($id);
        echo json_encode(['message' => 'Product deleted successfully']);
    }
}
?>