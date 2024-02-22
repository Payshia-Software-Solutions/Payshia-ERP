<?php
require_once('./include/config.php');
include './include/function-update.php';
$Products = GetProducts($link);

// if (!empty($Products)) {
//     $rowNumber = 1;
//     foreach ($Products as $Product) {
//         $product_id = $Product['product_id'];

//         // Update queries using the obtained product_id
//         $update_query1 = "UPDATE `master_product` SET `master_product`.`product_id` = $rowNumber WHERE `master_product`.`product_id` = $product_id;";
//         $update_query2 = "UPDATE `transaction_invoice_items` SET `transaction_invoice_items`.`product_id` = $rowNumber WHERE `transaction_invoice_items`.`product_id` = $product_id;";
//         $update_query3 = "UPDATE `transaction_production_items` SET `transaction_production_items`.`product_id` = $rowNumber WHERE `transaction_production_items`.`product_id` = $product_id;";
//         $update_query4 = "UPDATE `transaction_stock_entry` SET `transaction_stock_entry`.`product_id` = $rowNumber WHERE `transaction_stock_entry`.`product_id` = $product_id;";

//         // Execute your SQL queries here...
//         $result1 = mysqli_query($link, $update_query1);
//         $result2 = mysqli_query($link, $update_query2);
//         $result3 = mysqli_query($link, $update_query3);
//         $result4 = mysqli_query($link, $update_query4);

//         // Check if queries were successful
//         if ($result1 && $result2 && $result3 && $result4) {
//             echo "Update for row $rowNumber successful.<br>";
//         } else {
//             echo "Update for row $rowNumber failed.<br>";
//         }

//         $rowNumber++;
//     }
// }



// if (!empty($Products)) {
//     $rowNumber = 1;
//     foreach ($Products as $Product) {
//         $product_id = $Product['product_id'];

//         // Update queries using the obtained product_id
//         $update_query1 = "UPDATE `master_product` SET `master_product`.`product_id` = $rowNumber WHERE `master_product`.`product_id` = $product_id;";
//         $update_query2 = "UPDATE `transaction_invoice_items` SET `transaction_invoice_items`.`product_id` = $rowNumber WHERE `transaction_invoice_items`.`product_id` = $product_id;";
//         $update_query3 = "UPDATE `transaction_production_items` SET `transaction_production_items`.`product_id` = $rowNumber WHERE `transaction_production_items`.`product_id` = $product_id;";
//         $update_query4 = "UPDATE `transaction_stock_entry` SET `transaction_stock_entry`.`product_id` = $rowNumber WHERE `transaction_stock_entry`.`product_id` = $product_id;";

//         // Execute your SQL queries here...
//         $result1 = mysqli_query($link, $update_query1);
//         $result2 = mysqli_query($link, $update_query2);
//         $result3 = mysqli_query($link, $update_query3);
//         $result4 = mysqli_query($link, $update_query4);

//         // Check if queries were successful
//         if ($result1 && $result2 && $result3 && $result4) {
//             echo "Update for row $rowNumber successful.<br>";
//         } else {
//             echo "Update for row $rowNumber failed.<br>";
//         }

//         $rowNumber++;
//     }
// }


function GetFinanceInvoiceList($link)
{

    $ArrayResult = array();
    $sql = "SELECT `transaction_id`, `debit_account_id`, `credit_account_id`, `amount`, `transaction_date`, `description`, `ref_key`, `location_id`, `created_by`, `timestamp` FROM `finance_transactions` ORDER BY `transaction_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['transaction_id']] = $row;
        }
    }
    return $ArrayResult;
}


// $Invoices = GetInvoices($link);
$Invoices = GetFinanceInvoiceList($link);

// var_dump($Invoices);
if (!empty($Invoices)) {
    $rowNumber = 1;
    foreach ($Invoices as $Invoice) {
        $invoiceNumber_new = 'INV' . str_pad($rowNumber, 4, '0', STR_PAD_LEFT);
        $invoice_number = $Invoice['ref_key'];

        if($invoice_number)

        // Update queries using the obtained product_id
        $update_query1 = "UPDATE `finance_transactions` SET `transaction_id` = '$rowNumber', ref_key = '$invoiceNumber_new' WHERE ref_key LIKE '$invoice_number'";
        // $update_query2 = "UPDATE `transaction_invoice_items` SET `transaction_invoice_items`.`invoice_number` = '$invoiceNumber_new' WHERE `transaction_invoice_items`.`invoice_number` LIKE '$invoice_number';";
        // $update_query3 = "UPDATE `transaction_receipt` SET `transaction_receipt`.`ref_id` = '$invoiceNumber_new' WHERE `transaction_invoice_items`.`ref_id` LIKE '$invoice_number';";
        // $update_query4 = "UPDATE `transaction_stock_entry` SET  `transaction_stock_entry`.`ref_id` = '$invoiceNumber_new' WHERE `transaction_stock_entry`.`ref_id` LIKE '$invoice_number';";

        // Execute your SQL queries here...
        // $result1 = mysqli_query($link, $update_query1);
        // $result2 = mysqli_query($link, $update_query2);
        // $result3 = mysqli_query($link, $update_query3);

        echo $update_query1;
        // $result4 = mysqli_query($link, $update_query4);

        // Check if queries were successful
        if ($result1) {
            echo "Update for $invoice_number to $invoiceNumber_new successful.<br>";
        } else {
            // Display the error message
            echo "Error updating row $rowNumber: " . mysqli_error($link) . "<br>";
        }
        echo "$invoice_number <br>";

        
    }
}


// $cashAccountId = 1; // Cash
// $salesRevenueAccountId = 15; //Sales/Revenue
// $accountsReceivableAccountId = 3; // AccountReceivable
// $accountsPayableAccountId = 2; // Account Payable
// $inventoryAccountId = 4; // Inventory Account
// $costOfGoodsAccountId = 18; // COGS

// $receipts = GetReceipts($link);

// if (!empty($Invoices)) {
//     $rowNumber = 1;
//     foreach ($Invoices as $Invoice) {

//         $description = "Cash Sale - " . $invoice_number . " @ " . $location_name;
//         $journal_entry = addDoubleEntryTransaction($cashAccountId, $salesRevenueAccountId, $grand_total, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);

//         $description = "Cost Amount for - " . $invoice_number . " @ " . $location_name;
//         $journal_entry = addDoubleEntryTransaction($costOfGoodsAccountId, $inventoryAccountId, $total_cost, $invoice_date, $description, $invoice_number, $LoggedUser, $location_id);
//     }
// }
