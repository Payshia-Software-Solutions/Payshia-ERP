<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

// Validate incoming POST data
if (isset($_POST['customer_id'], $_POST['location_id'], $_POST['updated_by'], $_POST['reason'], $_POST['return_amount'])) {
    $customer_id = $_POST['customer_id'];
    $location_id = $_POST['location_id'];
    $updated_by = $_POST['updated_by'];
    $reason = $_POST['reason'];
    $refund_id = $_POST['refund_id'];
    $tableData = $_POST['tableData'];
    $ref_invoice = $_POST['invoiceNumber'];
    $return_amount  = $_POST['return_amount'];
    $isActive = 1;

    // Call function to insert data and return result
    $saveResult = insertIntoReturnTable($customer_id, $location_id, $updated_by, $reason, $refund_id, $tableData, $isActive, $ref_invoice, $return_amount);
    echo json_encode($saveResult);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Required parameters are missing'));
}
