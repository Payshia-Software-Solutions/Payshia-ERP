<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$cityList = GetCities($link);
$districtList = getDistricts($link);
$batchStudents =  GetLmsStudents();

$loggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];
$selectedArray = $batchStudents[$studentNumber];
$studentId = $selectedArray['student_id'];

// POST Array
$Email = $_POST["email_address"];
$civil_status = $_POST["status_id"];
$first_name = $_POST["fname"];
$last_name = $_POST["lname"];
$NicNumber = $_POST["NicNumber"];
$phoneNumber = $_POST["phoneNumber"];
$whatsAppNumber = $_POST["whatsAppNumber"];
$addressL1 = $_POST["addressL1"];
$addressL2 = $_POST["addressL2"];
$city = $_POST["city"];
$District = "";
$postalCode = "";
$gender = $_POST["gender"];
$birth_day = $_POST['birth_day'];
$full_name = $_POST["fullName"];
$name_with_initials = $_POST["nameWithInitials"];
$name_on_certificate = $_POST["nameOnCertificate"];

$password = $createUserLevel = $account_status = $batchCode = ''; //Not Using for the function

$fullDetailsCreateResult = UpdateUserFullDetails($studentId, $first_name, $last_name, $gender, $studentNumber, $civil_status, $phoneNumber, $Email, $addressL1, $addressL2, $city, $whatsAppNumber, $NicNumber, $District, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $loggedUser);

$mainUserResult = UpdateMainUser($first_name, $last_name, $civil_status, $phoneNumber, $Email, $studentNumber);
echo $fullDetailsCreateResult;
