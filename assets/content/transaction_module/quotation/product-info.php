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
$selling_price = $Product['selling_price'];
$minimum_price = $Product['minimum_price'];
$wholesale_price = $Product['wholesale_price'];
$price_2 = $Product['price_2'];
$selling_price = $Product['selling_price'];
$stockBalance = GetStockBalanceByProductByLocation($link, $ProductID, $location_id);

$error = array('status' => 'success', 'unit_name' => $UnitName,  'cost_price' => $AvgCostPrice,  'selling_price' => $selling_price,  'minimum_price' => $minimum_price,  'wholesale_price' => $wholesale_price,  'price_2' => $price_2, 'stockBalance' => $stockBalance);
echo json_encode($error);
