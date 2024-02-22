<?php
$cashAccountId = 1; // Cash
$salesRevenueAccountId = 15; //Sales/Revenue
$accountsReceivableAccountId = 3; // AccountReceivable
$accountsPayableAccountId = 2; // Account Payable
$inventoryAccountId = 4; // Inventory Account
$costOfGoodsAccountId = 18; // COGS
$expenseAccountId = 29;


include __DIR__ . '/config.php';
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function addDoubleEntryTransaction($debitAccountId, $creditAccountId, $amount, $date, $description, $ref_key, $created_by, $location_id)
{
    global $link;
    $dateTime = new DateTime();
    $timestamp = $dateTime->format("Y-m-d H:i:s.u");
    $link->begin_transaction();

    try {
        // Debit entry
        $debitSql = "INSERT INTO `finance_transactions` (`debit_account_id`, `amount`, `transaction_date`, `description`, `ref_key`, `created_by`, `timestamp`, `location_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $debitStmt = $link->prepare($debitSql);
        $debitStmt->bind_param('idssssss', $debitAccountId, $amount, $date, $description, $ref_key, $created_by, $timestamp, $location_id);
        $debitResult = $debitStmt->execute();
        $debitStmt->close();

        // Credit entry
        $creditSql = "INSERT INTO `finance_transactions` (`credit_account_id`, `amount`, `transaction_date`, `description`, `ref_key`, `created_by`,`timestamp`, `location_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $creditStmt = $link->prepare($creditSql);
        $creditStmt->bind_param('idssssss', $creditAccountId, $amount, $date, $description, $ref_key, $created_by, $timestamp, $location_id);
        $creditResult = $creditStmt->execute();
        $creditStmt->close();

        if (!$debitResult || !$creditResult) {
            $link->rollback();
            $result = ["success" => false, "message" => "Transaction failed"];
        }

        $link->commit();
        $result = ["success" => true, "message" => "Double-entry transaction added successfully"];
    } catch (mysqli_sql_exception $e) {
        $link->rollback();
        $result = ["success" => false, "message" => "MySQL Error: " . $e->getMessage()];
    }

    return json_encode($result);
}


function addChartOfAccount($accountName, $accountType, $createdBy)
{
    global $link;

    try {
        $dateTime = new DateTime();
        $timestamp = $dateTime->format("Y-m-d H:i:s.u");

        $sql = "INSERT INTO `finance_chart_of_accounts` (`account_name`, `account_type`, `created_by`, `created_at`) VALUES (?, ?, ?, ?)";

        $stmt = $link->prepare($sql);
        $stmt->bind_param('ssss', $accountName, $accountType, $createdBy, $timestamp);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $result = ["success" => false, "message" => "Chart of account added successfully"];
        } else {
            $result = ["success" => false, "message" => "Error: " . $link->error];
        }
    } catch (mysqli_sql_exception $e) {
        $result = ["success" => false, "message" => "MySQL Error: " . $e->getMessage()];
    }
    return json_encode($result);
}


function ChartOfAccounts()
{
    global $link;

    $ArrayResult = array();
    $sql = "SELECT `account_id`, `account_name`, `account_type`, `created_by`, `created_at` FROM `finance_chart_of_accounts`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $account_id = $row['account_id'];
            $row['account_balance'] = formatAccountBalance(getAccountBalance($account_id));
            $ArrayResult[$account_id] = $row;
        }
    }
    return $ArrayResult;
}


function getAccountBalance($accountId)
{
    global $link;

    $sql = "SELECT `debit_account_id`, `credit_account_id`, `amount` FROM `finance_transactions` WHERE `debit_account_id` = ? OR `credit_account_id` = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('ii', $accountId, $accountId);
    $stmt->execute();
    $stmt->bind_result($debitAccountId, $creditAccountId, $amount);

    $totalDebits = 0;
    $totalCredits = 0;

    while ($stmt->fetch()) {
        if ($debitAccountId == $accountId) {
            $totalDebits += $amount;
        } elseif ($creditAccountId == $accountId) {
            $totalCredits += $amount;
        }
    }

    $stmt->close();

    $balance = $totalDebits - $totalCredits;
    return $balance;
}


function formatAccountBalance($accountBalance)
{
    if ($accountBalance != null) {
        // Check if the balance is negative
        if ($accountBalance < 0) {
            // Add brackets around the formatted number
            return '(' . number_format(abs($accountBalance), 2) . ')';
        } else {
            return number_format($accountBalance, 2);
        }
    } else {
        return number_format(0, 2);;
    }
}
