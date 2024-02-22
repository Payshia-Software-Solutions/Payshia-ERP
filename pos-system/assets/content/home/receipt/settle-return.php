<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

$locationId = $_POST['LocationID'];
$customerId = $_POST['customerId'];
$invNumber = $_POST['invNumber'];
$LoggedUser = $_POST['LoggedUser'];
$rtnNumber = $_POST['rtnNumber'];

$selectedReturn = GetReturns()[$rtnNumber];
$settledAmount = GetSettledAmount($rtnNumber);
$invoiceDetails =  GetInvoicesByCustomer($link, $customerId)[$invNumber];
$paymentValue = GetReceiptsValueByInvoice($link, $invNumber);

$returnAmount = $selectedReturn['return_amount'];
$invAmount = $invoiceDetails['inv_amount'];
$InvoiceSettlement =  GetInvoiceSettlement($invNumber);
$balanceAmount = $invAmount - $paymentValue - $InvoiceSettlement;
$unsettledReturnBalance = $returnAmount - $settledAmount;

if ($balanceAmount > $unsettledReturnBalance) {
    $settlement = $unsettledReturnBalance;
} else {
    $settlement = $balanceAmount;
}

$is_active = 1;
$saveResult = SaveReturnSettlement($rtnNumber, $invNumber, $settlement, $is_active, $LoggedUser, $locationId, $customerId);
echo json_encode($saveResult);
