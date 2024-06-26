<?php

include __DIR__ . '/../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


function GetAccountsByType($accountType)
{
    global $link;

    $ArrayResult = array();

    if ($accountType == 'all') {
        $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by`, `civil_status` FROM `user_accounts`";
    } else {
        $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by`, `civil_status` FROM `user_accounts` WHERE `acc_type` LIKE '$accountType'";
    }

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['user_name']] = $row;
        }
    }
    return $ArrayResult;
}


function GetWinpharmaMarking($userName)
{
    global $lms_link;

    $ArrayResult = array();

    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `date_time`, `attempt`, `course_code`, `reason`, `update_by`, `update_at`, `recorrection_count`, `payment_status` FROM `win_pharma_submission` WHERE `update_by` LIKE '$userName' ";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['submission_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetWinpharmaMarkingByPayment($userName, $paymentStatus = 'Not Paid')
{
    global $lms_link;

    $ArrayResult = array();

    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `date_time`, `attempt`, `course_code`, `reason`, `update_by`, `update_at`, `recorrection_count`, `payment_status` FROM `win_pharma_submission` WHERE `update_by` LIKE '$userName' AND `payment_status` LIKE '$paymentStatus'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['submission_id']] = $row;
        }
    }
    return $ArrayResult;
}

function WinpharmaEmployeePayments($userName)
{
    global $lms_link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `employee_number`, `marking_count`, `payment`, `is_active`, `payment_approval`, `created_at`, `created_by`, `update_by` FROM `employee_winpharma_payments`  WHERE `employee_number` LIKE '$userName' AND `is_active` LIKE 1";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function WinpharmaEmployeePaymentsSummary($userName)
{
    global $link;

    $ArrayResult = array();

    $sql = "SELECT SUM(marking_count) AS `total_marking_count`, SUM(payment) AS `total_payment_count`, `payment_approval`FROM `employee_winpharma_payments`  WHERE `employee_number` LIKE '$userName' AND `is_active` LIKE 1 GROUP BY `payment_approval`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['payment_approval']] = $row;
        }
    }
    return $ArrayResult;
}
