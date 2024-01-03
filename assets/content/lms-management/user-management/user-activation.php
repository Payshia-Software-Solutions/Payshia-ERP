<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';
include '../../../../include/sms-API.php';
include '../../../../include/email-API.php';

$CourseBatches = getLmsBatches();
$cityList = GetCities($link);
$DistrictList = getDistricts($link);

// Get POST Parameters
$LoggedUser = $_POST['LoggedUser'];
$refId = $_POST['refId'];
$studentBatch = $_POST['studentBatch'];
$activeStatus = $_POST['activeStatus'];
$createUserLevel = $_POST['createUserLevel'];
$account_status = "Active";

if ($activeStatus == "Rejected") {
    $userUpdateStatus = UpdateTempUserStatus($refId, $activeStatus, 'Not Set');
    echo json_encode(array('status' => 'success', 'message' => 'User Rejected!', 'username' => "Not Set"));
    exit;
}

// Generate Index Number
$batchCode = $CourseBatches[$studentBatch]['id'];
$generatedUserName = GenerateLmsIndexNumber($batchCode)['userName'];
$generatedUserId = GenerateLmsIndexNumber($batchCode)['userId'];
$selectedArray = GetTemporaryUsers()[$refId];

$email_address = $selectedArray['email_address'];
$civil_status = $selectedArray['civil_status'];
$first_name = $selectedArray['first_name'];
$last_name = $selectedArray['last_name'];
$nic_number = $selectedArray['nic_number'];
$phone_number = $selectedArray['phone_number'];
$whatsapp_number = $selectedArray['whatsapp_number'];
$address_l1 = $selectedArray['address_l1'];
$address_l2 = $selectedArray['address_l2'];
$city = $selectedArray['city'];
$district = $selectedArray['district'];
$postal_code = $selectedArray['postal_code'];
$approved_status = $selectedArray['aprroved_status'];
$created_at = $selectedArray['created_at'];
$paid_amount = $selectedArray['paid_amount'];
$password = $selectedArray['password'];
$full_name = $selectedArray['full_name'];
$name_with_initials = $selectedArray['name_with_initials'];
$gender = $selectedArray['gender'];

$districtId = $cityList[(int)$city]['district_id'];
$postalCode = $cityList[(int)$city]['postcode'];

$updateKey = $generatedUserName;
$name_on_certificate = $name_with_initials;
$birth_day = "";
$update_by = "";
$enrollKey = "ForceAdmin";


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Account Creation
$accountCreateResult = CreateNewLmsUser($generatedUserId, $first_name, $last_name, $generatedUserName, $password, $createUserLevel, $LoggedUser, $account_status, $civil_status, $phone_number, $email_address, $batchCode);

$submitArray = json_decode($accountCreateResult);
$status = $submitArray->status;

if ($status == 'success') {
    $fullDetailsCreateResult = CreateNewLmsUserFullDetails($updateKey, $generatedUserId, $first_name, $last_name, $gender, $generatedUserName, $password, $createUserLevel, $LoggedUser, $account_status, $civil_status, $phone_number, $email_address, $batchCode, $address_l1, $address_l2, $city, $whatsapp_number, $nic_number, $districtId, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $update_by);
}


$userUpdateStatus = UpdateTempUserStatus($refId, $activeStatus, $generatedUserName);
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------


$submitArray = json_decode($fullDetailsCreateResult);
$status = $submitArray->status;

if ($status == 'success') {
    // Course Enrollment
    $enrollmentResult = StudentEnrollment($studentBatch, $generatedUserId, $enrollKey);
}


$submitArray = json_decode($enrollmentResult);

// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Load SMS content from the file
$smsTemplateFilePath = '../../../../assets/sms-templates/account-activation-message.txt'; // Replace with the actual path to your SMS template file
$smsTemplate = file_get_contents($smsTemplateFilePath);

// Replace variables in the SMS content
$smsMessage = str_replace('{{FIRST_NAME}}', $first_name, $smsTemplate);
$smsMessage = str_replace('{{COURSE_NAME}}', $CourseBatches[$studentBatch]['course_name'], $smsMessage);
$smsMessage = str_replace('{{GENERATED_USER_NAME}}', $generatedUserName, $smsMessage);

// Format phone number
$phone_number = '0' . $phone_number;

// Send SMS
$smsResult = SentSMS($phone_number, 'Pharma C.', $smsMessage);



// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Send Email
$fullName = $name_with_initials;
$toAddress = $email_address;
$fromAddress = 'info@pharmacollege,lk';
$mailSubject = "New User Activation | Ceylon Pharma College";
$mailBodyHtml = '';

// Load HTML content from the file
$htmlFilePath = '../../../../assets/mail-templates/register-mail.html'; // Replace with the actual path to your HTML file
$mailBodyHtml = file_get_contents($htmlFilePath);

// Replace variables in the HTML content
$mailBodyHtml = str_replace('{{FULL_NAME}}', $fullName, $mailBodyHtml);
$mailBodyHtml = str_replace('{{USER_NAME}}', $generatedUserName, $mailBodyHtml);
$mailBodyHtml = str_replace('{{YEAR}}', date('Y'), $mailBodyHtml);

$mailResult = sentEmail($fullName, $toAddress, $fromAddress, $mailSubject, $mailBodyHtml);
echo $userUpdateStatus;
