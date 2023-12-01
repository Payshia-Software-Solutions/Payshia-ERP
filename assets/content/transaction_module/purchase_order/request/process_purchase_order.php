<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$Products = GetProducts($link);
$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];

$location_id = $_POST['location_id'];
$supplier_id = $_POST['supplier_id'];
$currency = $_POST['currency'];
$tax_type = $_POST['tax_type'];

$order_value = $_POST['order_value'];
$po_status = $_POST['po_status'];
$po_number = $_POST['po_number'];
$remarks = $_POST['remarks'];

$POProducts = GetTempPO($link, $LoggedUser);
$is_active = 1;

if ($po_status == 1) {
    $prefix = "POT";
} elseif ($po_status == 2) {
    $prefix = "PO";
}

$invoice_date = date("Y-m-d");
$exists = isPurchaseOrderExists($link, $po_number);

$pre_po_number = $po_number;
if (!$exists) {
    $po_number = generatePurchaseOrderNumber($link, $prefix);
} elseif (strpos($po_number, 'POT') === 0 && $po_status == 2) {
    $po_number = generatePurchaseOrderNumber($link, $prefix);
    $temp_status = 3;
    $finish_result = SetTempPOFinish($link, $pre_po_number, $temp_status);
}


if ($po_number) {
    if (!empty($POProducts)) {
        foreach ($POProducts as $selectedArray) {
            $OrderDate = $selectedArray['order_rate'];
            $Quantity = $selectedArray['quantity'];
            $PerRate = $selectedArray['order_rate'];
            $OrderUnit = $selectedArray['order_unit'];
            $ProductID = $selectedArray['product_id'];
            $productName = $Products[$ProductID]['product_name'];

            $currentStock = 0;
            $lineTotal = $PerRate * $Quantity;

            $insert_result = AddToPurchaseOrder($link, $LoggedUser, $ProductID, $Quantity, $po_number, $OrderUnit, $PerRate);
        }
    }

    $result = CreatePurchaseOrder($link, $po_number, $location_id, $supplier_id, $currency, $tax_type, $order_value, $LoggedUser, $is_active, $po_status, $remarks);
}

echo $result;
