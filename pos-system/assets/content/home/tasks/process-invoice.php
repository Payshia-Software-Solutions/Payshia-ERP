<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';


$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);

$CustomerID = $_POST['CustomerID'];
$LoggedUser = $_POST['LoggedUser'];
$TableID = $_POST['TableID'];
$stewardId = $_POST['stewardID'];
$invoice_status = $_POST['InvoiceStatus'];
$invoice_number = $_POST['InvoiceNumber'];

$sub_total = $_POST['sub_total'];
$tax_rate = $_POST['tax_rate'];
$tax_amount = $_POST['tax_amount'];
$discount_rate = $_POST['discount_rate'];
$discount_amount = $_POST['discount_amount'];
$grand_total = $_POST['grand_total'];
$close_type = $_POST['close_type'];
$tendered_amount = $_POST['tendered_amount'];
$location_id = $_POST['location_id'];

$inv_amount = $sub_total;
$location_name = $Locations[$location_id]['location_name'];
$CartProducts = GetCart($link, $LoggedUser);
$CurrencySelected = "LKR";
$is_active = 1;

if ($invoice_status == 1) {
    $inv_prefix = "INT";
} elseif ($invoice_status == 2) {
    $inv_prefix = "INV";
}

$invoice_date = date("Y-m-d");
$exists = isInvoiceNumberExists($link, $invoice_number);

$pre_invoice_number = $invoice_number;
if (!$exists) {
    $invoice_number = generateInvoiceNumber($link, $inv_prefix);
} elseif (strpos($invoice_number, 'INT') === 0 && $invoice_status == 2) {
    $invoice_number = generateInvoiceNumber($link, $inv_prefix);
    $temp_status = 3;
    $finish_result = SetTempInvoiceFinish($link, $pre_invoice_number, $temp_status);
}


if ($invoice_number) {
    if ($invoice_status == 1) {
        $deleteResult = DeleteCurrentInvoiceProducts($link, $invoice_number);
    }
    if (!empty($CartProducts)) {
        $total_cost = 0;
        foreach ($CartProducts as $SelectRecord) {
            $selling_price = $SelectRecord['item_price'];
            $item_quantity = $SelectRecord['quantity'];
            $item_discount = $SelectRecord['item_discount'];
            $product_id = $SelectRecord['product_id'];
            $printed_status = $SelectRecord['printed_status'];
            $total_cost += GetCostPrice($link, $product_id) * $item_quantity;

            $line_total = ($selling_price - $item_discount) * $item_quantity;
            $sub_total += $line_total;

            $insert_result = AddToInvoice($link, $product_id, $LoggedUser, $CustomerID, $selling_price, $item_discount, $item_quantity, $TableID, $invoice_number, $printed_status);

            if ($invoice_status == 2) {
                $recipe_type = $Products[$product_id]['recipe_type'];
                if ($recipe_type == 1) {
                    $product_name = $Products[$SelectRecord['product_id']]['product_name'];
                    $stock_type = "DEBIT";
                    $reference = $stock_type . " : " . $item_quantity . " " . $product_name . "(s) is Debited to " . $invoice_number . " | INV-DEBIT - " . $product_name;

                    $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $product_id, $reference, $location_id, $LoggedUser, 1, $invoice_number);
                }

                $product_name = $Products[$SelectRecord['product_id']]['product_name'];
                $stock_type = "CREDIT";
                $reference = $stock_type . " : " . $item_quantity . " " . $product_name . "(s) is Credited to " . $invoice_number;

                $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $product_id, $reference, $location_id, $LoggedUser, 1, $invoice_number);

                $orderQuantity = $item_quantity;
                $ProductRecipe = GetItemRecipe($link, $product_id);
                $product_name = $Products[$product_id]['product_name'];

                if ($recipe_type == 1) {
                    if (!empty($ProductRecipe)) {
                        foreach ($ProductRecipe as $SelectedArray) {
                            $product_id = $SelectedArray['recipe_product'];
                            $item_quantity = $orderQuantity * $SelectedArray['qty'];

                            $sub_product_name = $Products[$product_id]['product_name'];
                            $stock_type = "CREDIT";
                            $reference = $stock_type . " : " . $item_quantity . " " . $sub_product_name . "(s) is Credited to " . $invoice_number . " | INV-CREDIT - " . $product_name;
                            $stock_result = CreateStockEntry($link, $stock_type, $item_quantity, $product_id, $reference, $location_id, $LoggedUser, 1, $invoice_number);
                        }
                    }
                }
            }
        }
    }

    $service_charge = $tax_amount;
    $result = CreateInvoice($link, $invoice_number, $invoice_date, $inv_amount, $grand_total, $discount_amount, $discount_rate, $CustomerID, $service_charge, $tendered_amount, $close_type, $invoice_status, $location_id, $TableID, $LoggedUser, $is_active, $stewardId, $total_cost, $pre_invoice_number);

    if ($invoice_status == 2 && $close_type != -1) {
        $rec_prefix = "REC";
        $rec_number = generateRecNumber($link, $rec_prefix);
        $receipt_result = CreateReceipt($link, $rec_number, $close_type, 1, $invoice_date, $grand_total, $LoggedUser, $invoice_number, $location_id, $CustomerID, 1);

        // $PaymentTypes = [
        //     ["id" => "0", "text" => "Cash"],
        //     ["id" => "1", "text" => "Visa/Master"],
        //     ["id" => "2", "text" => "Cheque"],
        //     ["id" => "3", "text" => "GV"]
        // ];

        if ($close_type == 0) {
            // Journal Entry (Debit then Credit)
            $description = "Cash Sale - " . $invoice_number . " @ " . $location_name;
            $journal_entry = addDoubleEntryTransaction($cashAccountId, $salesRevenueAccountId, $grand_total, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
        } else if ($close_type == 1) {
            // Journal Entry (Debit then Credit)
            $description = "Credit Sale - " . $invoice_number . " @ " . $location_name;
            $journal_entry = addDoubleEntryTransaction($accountsReceivableAccountId, $salesRevenueAccountId, $grand_total, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
        }

        $description = "Cost Amount for - " . $invoice_number . " @ " . $location_name;
        $journal_entry = addDoubleEntryTransaction($costOfGoodsAccountId, $inventoryAccountId, $total_cost, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
    }
}

echo $result;
