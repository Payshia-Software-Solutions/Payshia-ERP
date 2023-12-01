<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the AJAX request
    $tableDataJson = $_POST['tableData'];

    // Decode the JSON data
    $tableDataSet = json_decode($tableDataJson, true);

    $main_product = $tableDataSet[0]['main_product_id'];
    $clear_result = ClearRecipe($link, $main_product);

    foreach ($tableDataSet as $tableData) {
        $main_product = $tableData['main_product_id'];
        $recipe_product = $tableData['recipe_product_id'];
        $quantity = $tableData['quantity'];
        $created_by = $tableData['LoggedUser'];

        $result = SaveRecipe($link, $main_product, $recipe_product, $quantity, $created_by);
    }

    $AllProducts = GetProducts($link);
    $Product = GetProducts($link)[$main_product];

    $ProductRecipe = GetItemRecipe($link, $main_product);
    if (!empty($ProductRecipe)) {
        $updated_cost_price = 0;
        foreach ($ProductRecipe as $SelectedArray) {
            $product_id = $SelectedArray['recipe_product'];
            $quantity = $SelectedArray['qty'];
            $cost_price = $AllProducts[$product_id]['cost_price'];
            $line_total = $quantity * $cost_price;
            $updated_cost_price += $line_total;
        }
    }

    $cost_update_result = updateCostPrice($link, $main_product, $updated_cost_price);
}


$error = json_encode(array('status' => 'error', 'message' => 'Invalid Method'));

echo $result;
