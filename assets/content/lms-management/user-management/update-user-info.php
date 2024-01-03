<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';
include '../../../../include/sms-API.php';
include '../../../../include/email-API.php';

$refId = $_POST['refId'];

$selectedArray = GetTemporaryUsers()[$refId];
$cityList = GetCities($link);
$districtList = getDistricts($link);

$Email = $_POST["email_address"];
$status_id = $_POST["status_id"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$password = $selectedArray['password'];;
$NicNumber = $_POST["NicNumber"];
$phoneNumber = $_POST["phoneNumber"];
$whatsAppNumber = $_POST["whatsAppNumber"];
$addressL1 = $_POST["addressL1"];
$addressL2 = $_POST["addressL2"];
$city = $_POST["city"];
$District = "";
$postalCode = "";
$paid_amount = 0.00;

$gender = $_POST["gender"];
$full_name = $_POST["fullName"];
$name_with_initials = $_POST["nameWithInitials"];
$name_on_certificate = $_POST["nameOnCertificate"];
$selectedCourse = $selectedArray['selected_course'];

$sqlResult = UpdateTempUserDetails($Email, $status_id, $fname, $lname, $password, $NicNumber, $phoneNumber, $whatsAppNumber, $addressL1, $addressL2, $city, $District, $postalCode, $paid_amount, $full_name, $name_with_initials, $name_on_certificate, $gender, $selectedCourse, $refId);
echo $sqlResult;
