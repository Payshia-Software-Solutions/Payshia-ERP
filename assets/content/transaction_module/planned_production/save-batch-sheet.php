<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Units = GetUnit($link);
$Products = GetProducts($link);

// POST Parameters
$productionDate = $_POST['productionDate'];
$targetQuantity = $_POST['targetQuantity'];
$batch_product = $_POST['select_product'];
$batchNumber = $_POST['batchNumber'];
$ProductionRemarks = $_POST['ProductionRemarks'];
$targetQtySnap = $_POST['targetQtySnap'];
$productionQty = $_POST['productionQty'];
$LoggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$default_location = $_POST['default_location'];
$locationId = $_POST['locationId'];
$costPerKgValue = $_POST['costPerKgValue'];
$company_id = $_POST['company_id'];
$productionCost = $_POST['productionCost'];
$tableData = json_decode($_POST['tableData'], true);

// New Batch Number
$prefix = "B";
$forwardVal = 1130;
$newBatchNumber = generateBatchNumber($prefix, $forwardVal);
$productionResult = SaveTransactionBatch($newBatchNumber, $targetQuantity, $productionQty, $batch_product, $ProductionRemarks, $productionDate, $LoggedUser, $costPerKgValue, $locationId, $productionCost);

$product_name = $Products[$batch_product]['product_name'];
$stock_type = "DEBIT";
$reference = $stock_type . " : " . $productionQty . " " . $product_name . "(s) is Debited to " . $newBatchNumber;
$stock_result = CreateStockEntry($link, $stock_type, $productionQty, $batch_product, $reference, $locationId, $LoggedUser, 1, $newBatchNumber);

if (!empty($tableData)) {
    foreach ($tableData as $rowData) {
        $productId = $rowData['product_id'];
        $productName = $rowData['product_name'];
        $quantity = $rowData['quantity'];
        $unit = $rowData['unit'];
        $unitPrice = $rowData['unit_price'];
        $amount = $rowData['amount'];

        $productionItemsResult = SaveTransactionBatchItemList($newBatchNumber, $productId, $quantity, $unitPrice, $unit, $LoggedUser, $locationId);

        $product_name = $Products[$productId]['product_name'];
        $stock_type = "CREDIT";
        $reference = $stock_type . " : " . $quantity . " " . $product_name . "(s) is Credit to " . $newBatchNumber;
        $stock_result = CreateStockEntry($link, $stock_type, $quantity, $productId, $reference, $locationId, $LoggedUser, 1, $newBatchNumber);
    }
}
// Update Cost Price

$pre_cost_price = GetCostPrice($link, $batch_product);
$currentStock = GetStockBalanceByProduct($link, $batch_product); // All Stocks

$pre_stock_valuation = $pre_cost_price * $currentStock;

$total_valuation = $pre_stock_valuation + $productionCost;
$updated_cost_price = $total_valuation / ($currentStock + $productionQty);

$cost_update_result = updateCostPrice($link, $batch_product, $updated_cost_price);

echo json_encode($productionResult);
