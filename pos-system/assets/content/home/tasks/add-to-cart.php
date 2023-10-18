<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';


$ProductID = $_POST['ProductID'];
$UserName = $_POST['LoggedUser'];
$CustomerID = $_POST['CustomerID'];
$ItemPrice = $_POST['ItemPrice'];
$ItemDiscount = $_POST['ItemDiscount'];
$Quantity = $_POST['ItemQty'];

$result = AddToCart($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity);
echo $result;
