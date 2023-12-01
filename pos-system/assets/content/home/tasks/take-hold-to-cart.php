<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$invoice_number = $_POST['invoice_number'];
$LoggedUser = $_POST['LoggedUser'];

$Invoices = GetInvoices($link);
$CartProducts = GetInvoiceItems($link, $invoice_number);
$deleteResult = deleteRecordsWithHoldStatusNotHold($link, $LoggedUser);

$CustomerID = $Invoices[$invoice_number]['customer_code'];
$TableID = $Invoices[$invoice_number]['table_id'];

if (!empty($CartProducts)) {
    foreach ($CartProducts as $SelectRecord) {
        $selling_price = $SelectRecord['item_price'];
        $item_quantity = $SelectRecord['quantity'];
        $item_discount = $SelectRecord['item_discount'];
        $product_id = $SelectRecord['product_id'];


        $result = AddToCart($link, $product_id, $LoggedUser, $CustomerID, $selling_price, $item_discount, $item_quantity, $TableID);
    }
}
echo $result;
