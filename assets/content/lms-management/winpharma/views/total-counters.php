<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$specialAccounts = GetLmsAccounts();
$LoggedUser = $_POST['LoggedUser'];
$paymentCurrency = 'LKR';
$paidCount = 0;
$paymentAmount = 0;

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

// Create a new Employee object
$employee = new Employee($database);
$submissions = new Submissions($database2);

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

$userCounts = $submissions->fetchSubmissionByInstructor($updatedBy);
$completedSubmissions = $submissions->fetchSubmissionByInstructor($updatedBy, 'Completed');
?>

<div class="row mt-5 mb-3">
    <div class="col-md-3">
        <div class="card item-card shadow">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>Completed</p>
                <h1><?= count($completedSubmissions) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card shadow">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>In-Progress</p>
                <h1><?= count($userCounts) - count($completedSubmissions) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card shadow">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>Paid Count</p>
                <h1><?= $paidCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card shadow">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>Withdrawals</p>
                <h1><?= $paymentCurrency ?> <?= number_format($paymentAmount, 2) ?></h1>
            </div>
        </div>
    </div>

</div>