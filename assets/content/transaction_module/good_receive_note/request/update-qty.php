<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$po_number = $_POST['po_number'];
$LoggedUser = $_POST['LoggedUser'];
$receivedQty = $_POST['receivedQty'];
$ProductID = $_POST['ProductID'];

$PurchaseOrder = GetPurchaseOrderItems($link, $po_number);
$grn_qty = GetGRNItemCountByPO($link, $po_number, $ProductID);

$OrderQuantity = $PurchaseOrder[$ProductID]['quantity'];
$pendingQty = $OrderQuantity - $grn_qty;

if ($receivedQty > $pendingQty) {
    $result = json_encode(array('status' => 'error', 'message' => 'Quantity cannot exceed the Ordered Quantity'));
    $receivedQty = $pendingQty;
} else if ($receivedQty == 0) {
    $result = json_encode(array('status' => 'error', 'message' => 'Quantity cannot be Zero'));
} else {
    $result = UpdateGRNQty($link, $LoggedUser, $ProductID, $po_number, $receivedQty);
}

echo $result;
