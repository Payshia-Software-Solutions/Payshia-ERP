<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';


$UserName = $_POST['LoggedUser'];
$ProductID = $_POST['select_product'];
$SupplierID = $_POST['supplier_id'];
$OrderUnit = $_POST['order_Unit'];
$LocationID = $_POST['location_id'];
$Quantity = $_POST['new_quantity'];
$OrderRate = $_POST['new_rate'];
$taxType = $_POST['tax_type'];
// tax_type

$result = AddOrUpdateTempPurchaseOrder($link, $UserName, $ProductID, $Quantity, $OrderRate, $SupplierID, $LocationID, $OrderUnit, $taxType);
echo $result;
