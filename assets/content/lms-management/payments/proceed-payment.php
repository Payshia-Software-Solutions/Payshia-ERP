<?php


require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Define Variables
$studentBalance = 0;

$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];
$paymentType = $_POST['paymentType'];
$paymentAmount = $_POST['paymentAmount'];
$discountAmount = $_POST['discountAmount'];
$courseCode = $_POST['courseCode'];


$paymentResult = insertStudentPayment($courseCode, $studentNumber, $paymentType, $paymentAmount, $LoggedUser, $discountAmount);
echo json_encode($paymentResult);
