<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Units = GetUnit($link);
$ProductID = $_POST['ProductID'];
$Product = GetProducts($link)[$ProductID];
$Products = GetProducts($link);
$location_id = $_POST['location_id'];

$UnitName = $Units[$Product['measurement']]['unit_name'];
$AvgCostPrice = $Product['cost_price'];
$stockBalance = GetStockBalanceByProductByLocation($link, $ProductID, $location_id);

$error = array('status' => 'success', 'unit_name' => $UnitName,  'cost_price' => $AvgCostPrice, 'stockBalance' => $stockBalance);
echo json_encode($error);
