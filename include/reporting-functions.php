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

function getReceiptsCollection($link, $date, $location_id)
{
    $ArrayResult = 0;

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT SUM(`amount`) as total_amount FROM `transaction_receipt` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1 AND `today_invoice` = 0 AND `location_id` LIKE '$location_id' ";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = ($row['total_amount'] !== null) ? $row['total_amount'] : 0;
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

function getReceiptsByDateAllFilterDated($link, $date)
{
    $ArrayResult = 0;

    // Format the date in the same format as stored in the database
    $formattedDate = date('Y-m-d', strtotime($date));

    $sql = "SELECT SUM(`amount`) as total_amount FROM `transaction_receipt` WHERE DATE(`current_time`) = '$formattedDate' AND `is_active` = 1 AND `today_invoice` = 1";

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

function getRevenueInfoByDateRangeAll($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT SUM(`grand_total`) AS `revenue`, SUM(`discount_amount`) AS `total_discount`, SUM(`service_charge`) AS `total_charge` FROM `transaction_invoice` WHERE DATE(`current_time`) BETWEEN ? AND ? AND `is_active` = 1 AND `location_id` = ? AND `invoice_status` = '2' ORDER BY `id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult[0];
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

    $sql = "SELECT ti.`id`, ti.`user_id`, ti.`product_id`, ti.`item_price`, ti.`item_discount`, ti.`item_discount` AS `total_discounts`, SUM(ti.`quantity`) AS `total_quantity`, ti.`added_date`, t.`is_active` AS `inv_status`, ti.`customer_id`, ti.`hold_status`, ti.`table_id`, ti.`invoice_number`, ti.`cost_price`, t.`invoice_date`, t.`location_id` 
FROM `transaction_invoice_items` ti 
JOIN `transaction_invoice` t ON ti.`invoice_number` = t.`invoice_number` 
WHERE DATE(`added_date`) BETWEEN '$fromDate' AND '$toDate' AND t.`is_active` = 1 AND t.`location_id` LIKE '$location_id' AND t.`invoice_status` LIKE '2' GROUP BY ti.`product_id`, ti.`item_price`, ti.`item_discount`";

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


function getChargeInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT `steward_id`, SUM(service_charge) AS `chargeAmount`, SUM(`inv_amount`) AS `TotalInvoice`, COUNT(`id`) AS `BillCount` FROM `transaction_invoice` WHERE DATE(`current_time`) BETWEEN ? AND ? AND `is_active` = 1 AND `location_id` = ? AND `invoice_status` = '2' GROUP BY `steward_id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['steward_id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetStockBalanceByProductByLocationToDate($link, $product_code, $location_id, $toDate)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`quantity`) AS `credit_count` FROM `transaction_stock_entry` WHERE `location_id` LIKE '$location_id' AND `product_id` LIKE '$product_code' AND `type` LIKE 'CREDIT' AND `is_active` LIKE 1 AND DATE(`created_at`) <= '$toDate'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $credit_count = $row['credit_count'];
        }
    }

    $sql = "SELECT SUM(`quantity`) AS `debit_count` FROM `transaction_stock_entry` WHERE `location_id` LIKE '$location_id' AND `product_id` LIKE '$product_code' AND `type` LIKE 'DEBIT'  AND `is_active` LIKE 1 AND DATE(`created_at`) <= '$toDate'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $debit_count = $row['debit_count'];
        }
    }

    $ArrayResult = $debit_count - $credit_count;

    return $ArrayResult;
}

function GetStockBalancesByLocationToDate($link, $location_id, $toDate)
{
    $stockBalanceByProduct = array();

    $sql = "SELECT `product_id`, SUM(CASE WHEN `type` = 'DEBIT' THEN `quantity` ELSE -`quantity` END) AS `stock_balance`
            FROM `transaction_stock_entry`
            WHERE `location_id` = '$location_id' 
            AND DATE(`created_at`) <= '$toDate'
            AND `is_active` = 1
            GROUP BY `product_id`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $stockBalance = $row['stock_balance'];
            $stockBalanceByProduct[$product_id] = $stockBalance;
        }
    }

    return $stockBalanceByProduct;
}


function getReceiptsByDateRangeAllFilterDated($link, $startDate, $endDate)
{
    $totalAmount = 0;

    // Format the dates in the same format as stored in the database
    $formattedStartDate = date('Y-m-d', strtotime($startDate));
    $formattedEndDate = date('Y-m-d', strtotime($endDate));

    $sql = "SELECT SUM(`amount`) as total_amount FROM `transaction_receipt` WHERE DATE(`current_time`) BETWEEN ? AND ? AND `is_active` = 1 AND `today_invoice` = 1";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("ss", $formattedStartDate, $formattedEndDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalAmount = ($row['total_amount'] !== null) ? $row['total_amount'] : 0;
        }
    }

    return $totalAmount;
}

function getInvoicesByDateRangeAllLatest($link, $fromDate, $toDate, $location_id)
{
    $invoiceData = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $formattedFromDate = date('Y-m-d', strtotime($fromDate));
    $formattedToDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM `transaction_invoice` WHERE DATE(`current_time`) BETWEEN ? AND ? AND `is_active` = 1 AND `location_id` = ? AND `invoice_status` = '2' ORDER BY `id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $formattedFromDate, $formattedToDate, $location_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoiceData[$row['invoice_number']] = $row;
        }
    }

    return $invoiceData;
}


function GetReturnByRange($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT tr.id, tr.rtn_number, tr.customer_id, tr.location_id AS return_location_id, tr.created_at AS return_created_at, tr.updated_by AS return_updated_by, tr.reason, tr.refund_id, tr.is_active AS return_is_active, tr.ref_invoice, tr.return_amount, tr.settled_invoice, tri.id AS return_item_id, tri.product_id, tri.item_rate, SUM(tri.item_qty) as `item_qty`, tri.updated_at AS return_item_updated_at, tri.update_by AS return_item_updated_by, tri.is_active AS return_item_is_active 
            FROM transaction_return tr 
            INNER JOIN transaction_return_items tri ON tr.rtn_number = tri.rtn_number 
            WHERE DATE(tr.created_at) BETWEEN '$fromDate' AND '$toDate' AND tr.is_active = 1 AND tr.location_id = '$location_id' 
            GROUP BY tri.product_id, tri.item_rate";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['return_item_id']] = $row;
        }
    }

    return $ArrayResult;
}


function GetReturnValueByRange($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = 0;

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT `id`, `rtn_number`, `customer_id`, `location_id`, `created_at`, `updated_by`, `reason`, `refund_id`, `is_active`, `ref_invoice`, SUM(`return_amount`) AS `return_amount`, `settled_invoice` FROM `transaction_return` WHERE DATE(`created_at`) BETWEEN '$fromDate' AND '$toDate' AND `is_active` = 1 AND `location_id` = '$location_id'";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['return_amount'];
        }
    }

    return $ArrayResult;
}


function GetUnsettledReturnValues($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT 
                tr.`rtn_number` AS unsettled_return_number,
                tr.`return_amount` AS return_amount,
                SUM(trs.`settled_amount`) AS total_settled_amount,
                tr.`return_amount` - COALESCE(SUM(trs.`settled_amount`), 0) AS unsettled_amount
            FROM 
                `transaction_return` tr
            LEFT JOIN 
                `translation_return_settlement` trs ON tr.`rtn_number` = trs.`rtn_number`
            WHERE 
                tr.`is_active` = 1 AND
                DATE(tr.`created_at`) BETWEEN '$fromDate' AND '$toDate' AND
                tr.`location_id` = $location_id
            GROUP BY 
                tr.`rtn_number`, tr.`return_amount`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row; // Append each row to the result array
        }
    }

    return $ArrayResult;
}


function GetUnsettledReturnValuesTotal($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT 
                SUM(tr.`return_amount`) AS return_amount,
                SUM(trs.`settled_amount`) AS total_settled_amount,
                SUM(tr.`return_amount`) - COALESCE(SUM(trs.`settled_amount`), 0) AS unsettled_amount
            FROM 
                `transaction_return` tr
            LEFT JOIN 
                `translation_return_settlement` trs ON tr.`rtn_number` = trs.`rtn_number`
            WHERE 
                tr.`is_active` = 1 AND
                DATE(tr.`created_at`) BETWEEN '$fromDate' AND '$toDate' AND
                tr.`location_id` = $location_id";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row; // Append each row to the result array
        }
    }

    return $ArrayResult[0];
}


function CustomerStatement($customerId, $link, $fromDate, $toDate)
{
    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "-- Combine data from all tables and order by date
    SELECT * FROM (
        -- Fetch data from the invoice table
        SELECT 
            `grand_total` AS `Amount`,
            `invoice_number` AS `Reference_Number`,
            `location_id` AS `Location_ID`,
            `invoice_date` AS `Transaction_Date`,
            'Invoice' AS `Transaction_Type`
        FROM 
            `transaction_invoice`
        WHERE 
            `is_active` = 1
            AND `customer_code` = $customerId
            AND `invoice_status` LIKE '2'
            AND `invoice_date` BETWEEN '$fromDate' AND '$toDate'
    
        UNION ALL
    
        -- Fetch data from the receipt table
        SELECT 
            `amount` AS `Amount`,
            `rec_number` AS `Reference_Number`,
            `location_id` AS `Location_ID`,
            `date` AS `Transaction_Date`,
            'Receipt' AS `Transaction_Type`
        FROM 
            `transaction_receipt`
        WHERE 
            `is_active` = 1
            AND `customer_id` = $customerId
            AND `date` BETWEEN '$fromDate' AND '$toDate'
    
        UNION ALL
    
        -- Fetch data from the returns table
        SELECT 
            `return_amount` AS `Amount`,
            `rtn_number` AS `Reference_Number`,
            `location_id` AS `Location_ID`,
            `created_at` AS `Transaction_Date`,
            'Return' AS `Transaction_Type`
        FROM 
            `transaction_return`
        WHERE 
            `is_active` = 1
            AND `customer_id` = $customerId
            AND `created_at` BETWEEN '$fromDate' AND '$toDate'
    ) AS combined_data
    ORDER BY 
        `Transaction_Date`,
        CASE 
        WHEN `Transaction_Type` = 'Invoice' THEN 1
        WHEN `Transaction_Type` = 'Return' THEN 2
        WHEN `Transaction_Type` = 'Receipt' THEN 3
        END;
    ";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row; // Append each row to the result array
        }
    }

    return $ArrayResult;
}


// Function to get previous data for balance forward calculation
function getPreviousBalanceForward($customerId, $link, $fromDate)
{
    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));

    $previousBalanceForward = array();

    $sql = "
        SELECT 
            SUM(`grand_total`) AS `Total_Invoice`,
            SUM(`amount`) AS `Total_Receipt`,
            SUM(`return_amount`) AS `Total_Return`
        FROM 
        (
            SELECT `grand_total`, 0 AS `amount`, 0 AS `return_amount`
            FROM `transaction_invoice`
            WHERE `is_active` = 1
            AND `customer_code` = $customerId
            AND `invoice_status` LIKE '2'
            AND `invoice_date` < '$fromDate'

            UNION ALL

            SELECT 0 AS `grand_total`, `amount`, 0 AS `return_amount`
            FROM `transaction_receipt`
            WHERE `is_active` = 1
            AND `customer_id` = $customerId
            AND `date` < '$fromDate'

            UNION ALL

            SELECT 0 AS `grand_total`, 0 AS `amount`, `return_amount`
            FROM `transaction_return`
            WHERE `is_active` = 1
            AND `customer_id` = $customerId
            AND `created_at` < '$fromDate'
        ) AS previous_data
    ";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $previousBalanceForward = array(
            'Total_Invoice' => $row['Total_Invoice'],
            'Total_Receipt' => $row['Total_Receipt'],
            'Total_Return' => $row['Total_Return'],
            'Reference_Number' => 'Balance Forward',
            'Location_ID' => null,
            'Transaction_Date' => $fromDate,
            'Transaction_Type' => 'Balance Forward'
        );
    }

    return $previousBalanceForward;
}


function GetGRNTotalByRange($fromDate, $toDate, $location_id)
{

    global $link;

    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT SUM(`grand_total`) as `total_grn_value` FROM `transaction_good_receive_note` WHERE `is_active` = 1 AND DATE(`created_at`) BETWEEN '$fromDate' AND '$toDate' AND `location_id` = $location_id";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row; // Append each row to the result array
        }
    }

    return $ArrayResult[0];
}


function GetExpensesListTotal($fromDate, $toDate, $location_id)
{

    global $link;

    $ArrayResult = array();

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT `ex_type`, SUM(`amount`) as `expense_amount` FROM `transaction_expenses` WHERE `is_active` = 1 AND DATE(`updated_at`) BETWEEN '$fromDate' AND '$toDate' AND `location_id` = $location_id GROUP BY `ex_type`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ex_type']] = $row; // Append each row to the result array
        }
    }

    return $ArrayResult;
}


function getCreditInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT 
    SUM(balanceAmount) AS totalCreditSales
FROM (
    SELECT 
        ti.invoice_number,
        ti.grand_total AS invoiceAmount, 
        COALESCE(SUM(tr.amount), 0) AS paymentAmount, 
        COALESCE(SUM(trs.settled_amount), 0) AS settledAmount,
        (ti.grand_total - COALESCE(SUM(tr.amount), 0) - COALESCE(SUM(trs.settled_amount), 0)) AS balanceAmount 
    FROM 
        transaction_invoice ti 
        LEFT JOIN transaction_receipt tr ON ti.invoice_number = tr.ref_id AND tr.is_active = 1
        LEFT JOIN translation_return_settlement trs ON ti.invoice_number = trs.invoice_number AND trs.is_active = 1
    WHERE 
        ti.is_active = 1 
        AND DATE(ti.current_time) BETWEEN ? AND ?
        AND ti.location_id = ?
        AND ti.invoice_status = '2'    
    GROUP BY 
        ti.invoice_number
) AS invoice_summary
WHERE 
    balanceAmount > 0;
";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ArrayResult[] = $row;
    }

    return $ArrayResult[0];
}


function getReceiptsByDateRange($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT 
                SUM(CASE WHEN DATE(ti.`current_time`) BETWEEN ? AND ? THEN tr.amount ELSE 0 END) AS inRangeReceipts,
                SUM(CASE WHEN DATE(tr.`date`) BETWEEN ? AND ? THEN tr.amount ELSE 0 END) AS AllReceipts
            FROM 
                `transaction_invoice` ti
            JOIN 
                `transaction_receipt` tr ON ti.invoice_number= tr.ref_id  AND ti.is_active = 1 AND tr.is_active = 1 
            WHERE 
                ti.location_id = ?";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sssss", $fromDate, $toDate, $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ArrayResult[] = $row;
    }

    return $ArrayResult[0];
}


function getReceiptsByDateRangeByType($link, $fromDate, $toDate, $location_id)
{
    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    $sql = "SELECT 
                tr.type as `PaymentTypeId`,
                pt.text as `PaymentType`,
                SUM(CASE WHEN DATE(ti.`current_time`) BETWEEN ? AND ? AND DATE(tr.`date`) BETWEEN ? AND ? THEN tr.amount ELSE 0 END) AS inRangeReceipts,
                SUM(CASE WHEN DATE(tr.`date`) BETWEEN ? AND ? THEN tr.amount ELSE 0 END) AS AllReceipts
            FROM 
                `transaction_invoice` ti
            JOIN 
                `transaction_receipt` tr ON ti.invoice_number= tr.ref_id AND ti.is_active = 1 AND tr.is_active = 1 
            JOIN 
                `payment_types` pt ON tr.type = pt.id
            WHERE 
                ti.location_id = ? 
            GROUP BY 
                tr.type";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("sssssss", $fromDate, $toDate,  $fromDate, $toDate, $fromDate, $toDate, $location_id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ArrayResult[$row['PaymentType']] = $row;
    }

    return $ArrayResult;
}


function GetDateWiseSaleReport($fromDate, $toDate, $location_id)
{
    global $link;

    $ArrayResult = array(); // Initialize an empty array

    // Format the dates in the same format as stored in the database
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Generate the sequence of dates
    $dateRange = createDateRangeArray($fromDate, $toDate);

    // Prepare the SQL query template
    $sql = "SELECT 
                DATE(`invoice_date`) AS `date`,
                COUNT(`id`) AS `total_invoices`,
                SUM(`grand_total`) AS `total_sales_amount`,
                SUM(`discount_amount`) AS `total_discount_amount`,
                SUM(`service_charge`) AS `total_service_charge`,
                SUM(`inv_amount`) AS `total_invoice_amount`
            FROM 
                `transaction_invoice`
            WHERE 
                DATE(`invoice_date`) = ?
                AND location_id = ?
                AND invoice_status = '2'
                AND is_active = 1
            GROUP BY 
                `date`
            ORDER BY 
                `date`;";

    // Fetch the sales data for each date in the range
    foreach ($dateRange as $date) {
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $date, $location_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If there are sales data for the date, fetch and add to ArrayResult
            while ($row = $result->fetch_assoc()) {
                $ArrayResult[] = $row;
            }
        } else {
            // If there are no sales data for the date, add a row with zeros
            $ArrayResult[] = array(
                'date' => $date,
                'total_invoices' => 0,
                'total_sales_amount' => 0,
                'total_discount_amount' => 0,
                'total_service_charge' => 0,
                'total_invoice_amount' => 0
            );
        }
    }

    return $ArrayResult;
}

// Function to generate array of dates between two dates
function createDateRangeArray($startDate, $endDate)
{
    $dates = array();
    $currentDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    while ($currentDate <= $endDate) {
        $dates[] = date('Y-m-d', $currentDate);
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $dates;
}
