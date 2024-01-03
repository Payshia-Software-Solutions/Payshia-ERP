<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';

$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);

$CustomerID = $_POST['CustomerID'];
$LoggedUser = $_POST['LoggedUser'];
$stewardId = $_POST['LoggedUser'];
$quoteStatus = $_POST['quoteStatus'];
$quote_number = $_POST['quoteNumber'];

$sub_total = $_POST['sub_total_hidden'];
$tax_rate = $_POST['tax_rate_hidden'];
$tax_amount = $_POST['tax_amount_hidden'];
$discount_rate = $_POST['discount_percentage_hidden'];
$discount_amount = $_POST['discount_value_hidden'];
$grand_total = $_POST['grand_total_hidden'];
$location_id = $_POST['location_id'];

$remarks = $_POST['remarks'];
$quote_amount = $sub_total;
$location_name = $Locations[$location_id]['location_name'];
$CurrencySelected = "LKR";
$is_active = 1;

$tableData = $_POST['tableData'];
$quote_date = date("Y-m-d");
$exists = isQuoteExist($link, $quote_number);

$pre_invoice_number = $quote_number;
if (!$exists) {
    $quote_number = generateQuoteNumber($link);
}

// var_dump($tableData);
if ($quote_number) {
    if (!empty($tableData)) {
        $total_cost = 0;
        foreach ($tableData as $SelectRecord) {
            $selling_price = $SelectRecord['rate'];
            $item_quantity = $SelectRecord['quantity'];
            $item_discount = 0;
            $product_id = $SelectRecord['productID'];
            $total_cost += GetCostPrice($link, $product_id) * $item_quantity;

            $line_total = ($selling_price - $item_discount) * $item_quantity;
            $sub_total += $line_total;
            $insert_result = AddToQuote($link, $product_id, $LoggedUser, $CustomerID, $selling_price, $item_discount, $item_quantity, 1, $quote_number);
            // print_r($insert_result);
        }
    }

    $service_charge = $tax_amount;
    $result = CreateQuote($link, $quote_number, $quote_date, $quote_amount, $grand_total, $discount_amount, $discount_rate, $CustomerID, $service_charge, $quoteStatus, $location_id, $LoggedUser, $is_active, $total_cost, $remarks);
}

echo $result;
