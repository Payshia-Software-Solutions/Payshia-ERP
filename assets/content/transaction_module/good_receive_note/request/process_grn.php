<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';

$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);
$Suppliers =  GetSupplier($link);
$LoggedUser = $_POST['LoggedUser'];

$location_id = $_POST['location_id'];
$supplier_id = $_POST['supplier_id'];
$currency = $_POST['currency'];
$tax_type = $_POST['tax_type'];

$sub_total = $_POST['subTotal'];
$tax_value = $_POST['taxAmount'];
$grand_total = $_POST['grandTotal'];
$payment_status = $_POST['payment_status'];

$grn_status = $_POST['grn_status'];
$grn_number = $_POST['grn_number'];
$po_number = $_POST['po_number'];
$remarks = $_POST['remarks'];

$supplier_name = $Suppliers[$supplier_id]['supplier_name'];
$location_name = $Locations[$location_id]['location_name'];
$GRNProducts =  GetTempGRNItems($link, $LoggedUser);
$is_active = 1;

if ($grn_status == 1) {
    $prefix = "GRT";
} elseif ($grn_status == 2) {
    $prefix = "GRN";
}

$invoice_date = date("Y-m-d");
$exists = isGRNExists($link, $grn_number);

$pre_grn_number = $grn_number;
if (!$exists) {
    $grn_number = generateGRNNumber($link, $prefix);
} elseif (strpos($po_number, 'GRT') === 0 && $grn_status == 2) {
    $grn_number = generateGRNNumber($link, $prefix);
    $temp_status = 3;
    $finish_result = SetTempPOFinish($link, $pre_grn_number, $temp_status);
}


if ($grn_number) {
    if (!empty($GRNProducts)) {
        foreach ($GRNProducts as $selectedArray) {
            $OrderDate = $selectedArray['order_rate'];
            $Quantity = $selectedArray['received_qty'];
            $PerRate = $selectedArray['order_rate'];
            $OrderUnit = $selectedArray['order_unit'];
            $ProductID = $selectedArray['product_id'];
            $productName = $Products[$ProductID]['product_name'];

            $pre_cost_price = GetCostPrice($link, $ProductID);
            $currentStock = GetStockBalanceByProduct($link, $ProductID); // All Stocks

            $pre_stock_valuation = $pre_cost_price * $currentStock;
            $grn_stock_valuation = $OrderDate * $Quantity;

            $total_valuation = $pre_stock_valuation + $grn_stock_valuation;
            $updated_cost_price = $total_valuation / ($currentStock + $Quantity);

            $cost_update_result = updateCostPrice($link, $ProductID, $updated_cost_price);

            $currentStock = 0;
            $lineTotal = $PerRate * $Quantity;

            $insert_result =  AddToGRN($link, $LoggedUser, $ProductID, $OrderUnit, $PerRate, $Quantity, $grn_number, $po_number);

            // Stock Entry

            $product_name = $Products[$selectedArray['product_id']]['product_name'];
            $stock_type = "DEBIT";
            $reference = $stock_type . " : " . $Quantity . " " . $product_name . "(s) is Debited to " . $grn_number;
            $stock_result = CreateStockEntry($link, $stock_type, $Quantity, $ProductID, $reference, $location_id, $LoggedUser, 1, $grn_number);
        }
    }

    // Journal Entry (Debit then Credit)
    // Debit: Inventory (Asset)
    // Credit: Accounts Payable (Liability)

    $dateTime = new DateTime();
    $current_date = $dateTime->format("Y-m-d");
    $description = "Good Received - " . $grn_number . " @ " . $location_name . " From " . $supplier_name;
    $journal_entry = addDoubleEntryTransaction($inventoryAccountId, $accountsPayableAccountId, $sub_total, $current_date, $description, $grn_number, $LoggedUser, $location_id);

    $result = ProcessGRN($link, $grn_number, $location_id, $supplier_id, $currency, $tax_type, $sub_total, $LoggedUser, $is_active, $grn_status, $remarks, $grand_total, $tax_value, $payment_status, $po_number);
}

echo $result;
