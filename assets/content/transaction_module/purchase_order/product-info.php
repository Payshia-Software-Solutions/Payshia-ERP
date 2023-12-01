<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Units = GetUnit($link);
$ProductID = $_POST['ProductID'];
$Product = GetProducts($link)[$ProductID];
$Products = GetProducts($link);

$UnitName = $Units[$Product['measurement']]['unit_name'];
$AvgCostPrice = $Product['cost_price'];

$error = array('status' => 'success', 'unit_name' => $UnitName,  'cost_price' => $AvgCostPrice);
echo json_encode($error);
