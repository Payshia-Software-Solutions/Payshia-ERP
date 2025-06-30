<?php

require_once './controllers/MasterCategoriesController.php';

$pdo = $GLOBALS['pdo'];
$categoriesController = new CategoriesController($pdo);

return [
    'GET /master-categories/' => function () use ($categoriesController) {
        $categoriesController->getAllRecords();
    },
    'GET /master-categories/{category_id}/' => function ($category_id) use ($categoriesController) {
        $categoriesController->getRecordById((int) $category_id);
    },
    'POST /master-categories/' => function () use ($categoriesController) {
        $categoriesController->createRecord();
    },
    'PUT /master-categories/{category_id}/' => function ($category_id) use ($categoriesController) {
        $categoriesController->updateRecord((int) $category_id);
    },
    'DELETE /master-categories/{category_id}/' => function ($category_id) use ($categoriesController) {
        $categoriesController->deleteRecord((int) $category_id);
    }
];
?>