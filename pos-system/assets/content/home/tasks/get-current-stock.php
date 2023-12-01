<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$ProductID = $_POST['ProductID'];
$LocationID = $_POST['LocationID'];
$arrayResult =  array('status' => 'success', 'stock_balance' => GetStockBalanceByProductByLocation($link, $ProductID, $LocationID));
echo json_encode($arrayResult);
