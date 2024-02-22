<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

// Validate incoming POST data
if (isset($_POST['exDescription'], $_POST['expenseAmount'], $_POST['expenseType'], $_POST['LoggedUser'], $_POST['LocationID'])) {
    $exDescription = $_POST['exDescription'];
    $expenseAmount = $_POST['expenseAmount'];
    $expenseType = $_POST['expenseType'];
    $LoggedUser = $_POST['LoggedUser'];
    $LocationID = $_POST['LocationID'];

    $isActive = 1;

    $saveResult =  SaveExpense($expenseType, $exDescription, $expenseAmount, $LoggedUser, $LocationID, $isActive);
    echo json_encode($saveResult);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Required parameters are missing'));
}
