<?php
require_once './controllers/MasterProductController.php';

$pdo = $GLOBALS['pdo'];
$productController = new ProductController($pdo);

return [
    'GET /master-products/' => function () use ($productController) {
        $productController->getAllRecords();
    },
    'GET /master-products/{product_id}/' => function ($product_id) use ($productController) {
        $productController->getRecordById($product_id);
    },
    'POST /master-products/' => function () use ($productController) {
        $productController->createRecord();
    },
    'PUT /master-products/{product_id}/' => function ($product_id) use ($productController) {
        $productController->updateRecord($product_id);
    },
    'DELETE /master-products/{product_id}/' => function ($product_id) use ($productController) {
        $productController->deleteRecord($product_id);
    }
];
