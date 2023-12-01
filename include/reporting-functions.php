<?php
$cashAccountId = 1; // Cash
$salesRevenueAccountId = 15; //Sales/Revenue
$accountsReceivableAccountId = 3; // AccountReceivable
$accountsPayableAccountId = 2; // Account Payable
$inventoryAccountId = 4; // Inventory Account
$costOfGoodsAccountId = 18; // COGS

include __DIR__ . '/config.php';
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getInvoicesByDate($link, $date, $location_id)
{
    $ArrayResult = 0;

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT SUM(`grand_total`) AS `total_sale` FROM `transaction_invoice` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1 AND `location_id` LIKE '$location_id' AND `invoice_status` LIKE '2'";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['total_sale'];
        }
    }

    return $ArrayResult;
}


function getReceiptsByDate($link, $date, $location_id)
{
    $ArrayResult = array();

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT `type`, SUM(`amount`) as total_amount FROM `transaction_receipt` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1 AND `location_id` LIKE '$location_id'  GROUP BY `type`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['type']] = $row['total_amount'];
        }
    }

    return $ArrayResult;
}


function getInvoicesByDateAll($link, $date)
{
    $ArrayResult = 0;

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT SUM(`grand_total`) AS `total_sale` FROM `transaction_invoice` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1 AND `invoice_status` LIKE '2' ORDER BY `id`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = ($row['total_sale'] !== null) ? $row['total_sale'] : 0;
        }
    }

    return $ArrayResult;
}



function getReceiptsByDateAll($link, $date)
{
    $ArrayResult = 0;

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT SUM(`amount`) as total_amount FROM `transaction_receipt` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = ($row['total_amount'] !== null) ? $row['total_amount'] : 0;
        }
    }

    return $ArrayResult;
}

function getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM `transaction_invoice` WHERE DATE(`current_time`) BETWEEN ? AND ? AND `is_active` = 1 AND `location_id` = ? AND `invoice_status` = '2' ORDER BY `id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }

    return $ArrayResult;
}


function stockBinCard($link, $fromDate, $toDate, $location_id, $product_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM `transaction_stock_entry` WHERE DATE(`created_at`) BETWEEN ? AND ? AND `is_active` = 1 AND `location_id` = ? AND `product_id` LIKE ?  ORDER BY `id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("ssss", $fromDate, $toDate, $location_id, $product_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }

    return $ArrayResult;
}


function getCumulativeBinCardTotals($link, $fromDate, $product_id, $location_id)
{

    $fromDate = date('Y-m-d', strtotime($fromDate));

    $query = "SELECT `type`, SUM(`quantity`) AS `cumulative_total` FROM `transaction_stock_entry` WHERE DATE(`created_at`) < '$fromDate' AND `product_id` LIKE '$product_id' AND `location_id` LIKE '$location_id' AND `is_active` = 1 GROUP BY `type`";

    $result = $link->query($query);

    $totals = array();
    while ($row = $result->fetch_assoc()) {
        $totals[$row['type']] = $row;
    }

    return $totals;
}


function GetItemWiseSale($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT ti.`id`, ti.`user_id`, ti.`product_id`, ti.`item_price`, SUM(ti.`item_discount`) AS `total_discounts`, SUM(ti.`quantity`) AS `total_quantity`, ti.`added_date`, t.`is_active` AS `inv_status`, ti.`customer_id`, ti.`hold_status`, ti.`table_id`, ti.`invoice_number`, ti.`cost_price`, t.`invoice_date`, t.`location_id` 
FROM `transaction_invoice_items` ti 
JOIN `transaction_invoice` t ON ti.`invoice_number` = t.`invoice_number` 
WHERE DATE(`added_date`) BETWEEN '$fromDate' AND '$toDate' AND t.`is_active` = 1 AND t.`location_id` LIKE '$location_id' AND t.`invoice_status` LIKE '2' GROUP BY ti.`product_id`, ti.`item_price`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }

    return $ArrayResult;
}


function GetReceiptsByLocation($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array();


    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT * FROM `transaction_receipt` WHERE DATE(`current_time`) BETWEEN '$fromDate' AND '$toDate' AND `is_active` = 1 AND `location_id` LIKE '$location_id' ORDER BY `id`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['rec_number']] = $row;
        }
    }

    return $ArrayResult;
}
