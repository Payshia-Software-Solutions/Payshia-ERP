<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';


$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);


// Parameter

$LoggedUser = $_POST['LoggedUser'];
$invoice_number = $_POST['invoice_number'];

$reason = $invoice_number . " cancelled";

$SelectedArray = GetInvoices($link)[$invoice_number];
$InvProducts = GetInvoiceItems($link, $invoice_number);
$receiptList =  GetReceiptsByInvoice($link, $invoice_number);
$close_type = $SelectedArray['close_type'];

$location_id = $SelectedArray['location_id'];
$grand_total = $SelectedArray['grand_total'];
$invoice_date = $SelectedArray['invoice_date'];
$total_cost = $SelectedArray['cost_value'];
$invoice_status = $SelectedArray['invoice_status'];

$location_name = $Locations[$location_id]['location_name'];

// Invoice Cancellation
$invoiceCancelResult = CancelInvoice($link, $invoice_number);

// Cancellation Entry
$cancellationEntry = CancellationEntry($link, 'Invoice',  $LoggedUser, $invoice_number, $reason);


if ($invoice_status == 2) {
    // Receipt Cancellation
    if (!empty($receiptList)) {
        foreach ($receiptList as $selectedArray) {
            $rec_number = $selectedArray['rec_number'];
            $receiptCancelStatus = CancelReceipt($link, $rec_number);

            // Cancellation Entry
            $cancellationEntry = CancellationEntry($link, 'Receipt',  $LoggedUser, $rec_number, $reason);
        }
    }

    // Item Stock Reverse
    if (!empty($InvProducts)) {
        foreach ($InvProducts as $SelectRecord) {
            $product_id = $SelectRecord['product_id'];
            $inactiveStockEntry = CancelStockEntryReceipt($link, $invoice_number, $product_id);

            // Cancellation Entry
            $cancellationEntry = CancellationEntry($link, 'Stock Entry',  $LoggedUser, $product_id, $reason);
        }
    }

    // Recipe
    $ProductRecipe = GetItemRecipe($link, $product_id);
    $product_name = $Products[$product_id]['product_name'];
    if (!empty($ProductRecipe)) {
        foreach ($ProductRecipe as $SelectedArray) {
            $product_id = $SelectedArray['recipe_product'];
            $inactiveStockEntry = CancelStockEntryReceipt($link, $invoice_number, $product_id);

            // Cancellation Entry
            $cancellationEntry = CancellationEntry($link, 'Stock Entry',  $LoggedUser, $product_id, $reason);
        }
    }

    // Accounting
    if ($close_type == 0) {
        // Journal Entry (Debit then Credit)
        $description = "Reverse: Cash Sale - " . $invoice_number . " @ " . $location_name;
        $journal_entry = addDoubleEntryTransaction($salesRevenueAccountId, $cashAccountId, $grand_total, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
    } else if ($close_type == 1) {
        // Journal Entry (Debit then Credit)
        $description = "Reverse: Credit Sale - " . $invoice_number . " @ " . $location_name;
        $journal_entry = addDoubleEntryTransaction($salesRevenueAccountId, $accountsReceivableAccountId, $grand_total, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
    }

    $description = "Reverse: Cost Amount for - " . $invoice_number . " @ " . $location_name;
    $journal_entry = addDoubleEntryTransaction($inventoryAccountId, $costOfGoodsAccountId, $total_cost, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
}

echo $cancellationEntry;
