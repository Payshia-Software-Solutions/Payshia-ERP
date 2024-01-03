<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);

// Parameters
$location_id = $_POST['location_id'];
$LoggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$invoiceNumber = $_POST['invoiceNumber'];
$customerId = $_POST['customerId'];
$paymentMethod = $_POST['paymentMethod'];
$payment_amount = $_POST['payment_amount'];
$invoice_date = date("Y-m-d");

$invoiceCreateDate = GetInvoiceByNumber($link, $invoiceNumber)['invoice_date'];
$today_invoice = 0;
if ($invoice_date == $invoiceCreateDate) {
    $today_invoice = 1;
}

$location_name = $Locations[$location_id]['location_name'];
$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];

$rec_prefix = "REC";
$rec_number = generateRecNumber($link, $rec_prefix);

$receipt_result = CreateReceipt($link, $rec_number, $paymentMethod, 1, $invoice_date, $payment_amount, $LoggedUser, $invoiceNumber, $location_id, $customerId, $today_invoice);

if ($paymentMethod == 0) {
    // Journal Entry (Debit then Credit)
    $description = "Cash Receipt for - " . $invoiceNumber . " @ " . $location_name;
    $journal_entry = addDoubleEntryTransaction($cashAccountId, $salesRevenueAccountId, $payment_amount, $invoice_date, $description, $invoiceNumber, $LoggedUser, $location_id);
} else if ($paymentMethod == 1) {
    // Journal Entry (Debit then Credit)
    $description = "Credit Card Receipt for - " . $invoiceNumber . " @ " . $location_name;
    $journal_entry = addDoubleEntryTransaction($accountsReceivableAccountId, $salesRevenueAccountId, $payment_amount, $invoice_date, $description, $invoiceNumber, $LoggedUser, $location_id);
}

echo $receipt_result;
