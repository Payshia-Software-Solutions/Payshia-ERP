<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';

// Validate incoming POST data
if (isset($_POST['rtnCustomer'], $_POST['rtnAmount'], $_POST['rtnNumber'], $_POST['rtnLocation'], $_POST['pinDigits'], $_POST['LoggedUser'], $_POST['LocationID'])) {
    $rtnCustomer = $_POST['rtnCustomer'];
    $rtnAmount = $_POST['rtnAmount'];
    $rtnNumber = $_POST['rtnNumber'];
    $rtnLocation = $_POST['rtnLocation'];
    $pinDigits = $_POST['pinDigits'];
    $LoggedUser = $_POST['LoggedUser'];
    $currentLocation = $_POST['LocationID'];


    $isActive = 1;
    $refundPin = 1111;

    // Validate PIN
    if ($refundPin == $pinDigits) {
        // Call function to insert data and return result
        $saveResult =  insertIntoRefundTable($rtnCustomer, $rtnNumber, $rtnAmount, $isActive, $LoggedUser, $rtnLocation, $currentLocation);

        echo json_encode($saveResult);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Invalid PIN!'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Required parameters are missing'));
}
