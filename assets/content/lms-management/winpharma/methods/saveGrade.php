<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Include the classes
include_once '../classes/LmsDatabase.php';
include_once '../classes/Submissions.php';

// Integration with employee
include_once '../../../employee_management/classes/Database.php';
include_once '../../../employee_management/classes/Employee.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$database2 = new LmsDatabase($config_file);
$db2 = $database2->getConnection();

// $specialAccounts = GetLmsSpecialAccounts();

$specialAccounts =  GetLmsStudents();
$submissionId = $_POST['submissionId'];
$LoggedUser = $_POST['LoggedUser'];
$reason = $_POST['reason'];
$grade_status = $_POST['grade_status'];
$grade = $_POST['grade'];
$preReason = $_POST['pre-reason'];

if ($preReason != '') {
    $reason = $preReason;
}

// Create a new Employee object
$employee = new Employee($database);
$submissions = new Submissions($database2);
$submission = $submissions->fetchById($submissionId);
$IndexNumber = $submission['index_number'];

// Accounts Link Check
$userAccountLink = $employee->CheckAccountLinkByUser($LoggedUser, 1);
$employeeId = $userAccountLink['employee_id'];
$lmsAccountLink = $employee->CheckLMSAccountLinkByEmployee($employeeId, 2);
$linkedAccount = $lmsAccountLink['user_id'];

if (isset($specialAccounts[$linkedAccount])) {
    $accountInfo = $specialAccounts[$linkedAccount];
    $updatedBy = $accountInfo['username'];
}

if (!isset($updatedBy)) {
    return json_encode(['status' => 'error', 'message' => 'Please contact Administrator to Link LMS Account.']);
    exit;
}

// Update an employee
$updateData = [
    'grade' => $grade,
    'grade_status' => $grade_status,
    'reason' => $reason,
    'update_by' => $updatedBy,
];

if ($submissions->update($updateData, $submissionId)) {
    $error = array('status' => 'success', 'message' => 'Grade Updated successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update grade.' . $submissions->getLastError());
}

echo json_encode($error);

$reasonMsg = ($reason != "") ? 'Reason - ' . $reason : '';
// Send SMS
$UserDetails = GetLmsStudentsByUserName($IndexNumber);
$mobile = formatPhoneNumber($UserDetails['telephone_1']);

$phone_number = '0' . $UserDetails['telephone_1'];
$message = 'Hi ' . $IndexNumber . ',

Winpharama Submission is graded. Check Now!
Grade - ' . $grade_status . '

Ceylon Pharma College
www.pharamacollege.lk';
$senderId = 'Pharma C.';
$smsResult = SentSMS($phone_number, $senderId, $message);
// echo $smsResult;
