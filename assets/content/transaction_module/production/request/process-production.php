<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';

$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);

$LoggedUser = $_POST['LoggedUser'];
$poStatus = $_POST['poStatus'];
$production_date = $_POST['create_date'];
$remark = $_POST['remarks'];
$pnNumber = $_POST['pnNumber'];
$sub_total = $_POST['sub_total_hidden'];
$location_id = $_POST['location_id'];
$location_name = $Locations[$location_id]['location_name'];
$is_active = 1;
$tableData = $_POST['tableData'];
$exists = isPONumberExists($link, $pnNumber);
if (!$exists) {
    $pnNumber = generatePNNumber($link);
}

// var_dump($tableData);

if ($pnNumber) {
    if (!empty($tableData)) {
        $total_cost = 0;
        foreach ($tableData as $SelectRecord) {
            $cost_price = $SelectRecord['rate'];
            $item_quantity = $SelectRecord['quantity'];
            $item_discount = 0;
            $product_id = $SelectRecord['productID'];
            $total_cost += GetCostPrice($link, $product_id) * $item_quantity;

            $line_total = ($cost_price - $item_discount) * $item_quantity;
            $sub_total += $line_total;
            $insert_result =  CreateProductionNoteItems($link, $product_id, $item_quantity, $cost_price, $pnNumber, $LoggedUser, $is_active);

            $recipe_type = $Products[$product_id]['recipe_type'];
            $product_name = $Products[$product_id]['product_name'];
            $stock_type = "DEBIT";
            $reference = $stock_type . " : " . $item_quantity . " " . $product_name . "(s) is Debited to " . $pnNumber;
            $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $product_id, $reference, $location_id, $LoggedUser, 1, $pnNumber);

            $ProductRecipe = GetItemRecipe($link, $product_id);
            $product_name = $Products[$product_id]['product_name'];
            if (!empty($ProductRecipe)) {
                foreach ($ProductRecipe as $SelectedArray) {
                    $product_id = $SelectedArray['recipe_product'];
                    $item_quantity = $item_quantity * $SelectedArray['qty'];

                    $sub_product_name = $Products[$product_id]['product_name'];
                    $stock_type = "CREDIT";
                    $reference = $stock_type . " : " . $item_quantity . " " . $sub_product_name . "(s) is Credited to " . $pnNumber . " | INV-CREDIT - " . $product_name;
                    $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $product_id, $reference, $location_id, $LoggedUser, 1, $pnNumber);
                }
            }
        }
    }

    $result = CreateProductionNote($link, $total_cost, $location_id, $LoggedUser, $remark, $production_date, $pnNumber, $is_active);
}

echo $result;
