<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$Products = GetProducts($link);

$LoggedUser = $_POST['LoggedUser'];
$default_location = $_POST['default_location'];
$orderStatus = $_POST['orderStatus'];
$refId = $_POST['refId'];
$trackingNumber = $_POST['trackingNumber'];
$codAmount = $_POST['codAmount'];
$packageWeight =  $_POST['packageWeight'];

$selectedArray = GetOrders()[$refId];

$phone_1 = $selectedArray['phone_1'];
$delivery_id = $selectedArray['delivery_id'];
$index_number = $selectedArray['index_number'];
$deliveryItem =  GetDeliverySetting()[$delivery_id]['delivery_title'];
$queryResult = UpdateOrderStatus($refId, $trackingNumber, $orderStatus, $codAmount, $packageWeight);
if ($orderStatus == 2) {

    $messageText = 'Dear ' . $index_number . ',

Your order is ready for delivery!

Product - ' . $deliveryItem . ' 
Tracking Number - ' . $trackingNumber . ' 

Thank you!
Ceylon Pharma College
www.pharmacollege.lk';
} else if ($orderStatus == 3) {
    $messageText = 'Dear ' . $index_number . ',

Your order has been handed over to the delivery partner!

Product - ' . $deliveryItem . ' 
Tracking Number - ' . $trackingNumber . ' 
COD Amount - ' . $codAmount . ' 
Delivery Partner - Royal Express Courier 
    
Thank you!
Ceylon Pharma College
www.pharmacollege.lk';
}

if ($orderStatus != 4) {
    SentSMS($phone_1, 'Pharma C.', $messageText);
}


echo $queryResult;

$selectedArray = GetOrders()[$refId];

$delivery_id = $selectedArray['delivery_id'];
$erpProductId = GetProductLinkStatus($delivery_id)[$delivery_id]['product_code'];
if ($orderStatus == 3) {
    $item_quantity = 1;
    $product_name = $Products[$erpProductId]['product_name'];
    $stock_type = "CREDIT";
    $reference = "ADDON : " . $stock_type . " : " . $item_quantity . " " . $product_name . "(s) is Credited to Delivery Order - " . $trackingNumber;

    $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $erpProductId, $reference, $default_location, $LoggedUser, 1, $trackingNumber);
    // echo $stock_result;
}
