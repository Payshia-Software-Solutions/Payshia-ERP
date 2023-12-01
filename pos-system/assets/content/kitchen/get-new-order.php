<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$PreCount = $_POST['PreCount'];
$LocationID = $_POST['LocationID'];
$Invoices = GetHoldInvoicesByLocation($link, $LocationID);
// Use end() to move the array pointer to the last element
end($Invoices);
// Use key() to get the key of the last element
$lastKey = key($Invoices);

// var_dump($Invoices);


$CountHold = count($Invoices);
if ($PreCount < $CountHold) {
    $error = array('status' => 'success', 'message' => 'New Order Received', 'inv_number' => $lastKey);
} else {
    $error = array('status' => 'error', 'message' => 'No Orders', 'inv_number' => $lastKey);
}

echo json_encode($error);
