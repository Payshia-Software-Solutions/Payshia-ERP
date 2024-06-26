<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include_once './classes/Database.php';
include_once './classes/Employee.php';
include_once './classes/Position.php';
include_once './classes/Department.php';
include_once './classes/WorkLocation.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$employee = new Employee($db);
$position = new Position($db);
$department = new Department($db);
$work_location = new WorkLocation($db);

// Fetch all employees
$employees = $employee->fetchAllEmployees();

include './methods/functions.php'; //Ticket Methods

$LoggedUser = $_POST['LoggedUser'];
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';

$userAccounts =  GetAccounts($link);
$userCount = count($userAccounts);

$Locations = GetLocations($link);
$specialAccounts = GetLmsSpecialAccounts();

$adminAccounts = GetAccountsByType('Admin');
$earningPerMarking = 2;

// Read JSON file
$jsonData = file_get_contents('../../../include/strings.json');

// Decode JSON data
$arrays = json_decode($jsonData, true);
$statusList = convertSelectBox1DArrayValueOnly($arrays['statusValues']);

// Accounts Link Check
$userAccountLink = $employee->CheckAccountLinkByUser($LoggedUser, 1);
$employeeId = $userAccountLink['employee_id'];
$lmsAccountLink = $employee->CheckLMSAccountLinkByEmployee($employeeId, 2);

// Create a new Employee object
$cityList = GetCities($link);
$userAccounts = GetAccounts($link);
$lmsUserAccounts =  GetLmsStudents();

// Assign arrays directly from JSON data
$employeeType = convertSelectBox1DArrayValueOnly($arrays['employeeType']);
$genderList = convertSelectBox1DArrayValueOnly($arrays['genderList']);
$marriedList = convertSelectBox1DArrayValueOnly($arrays['marriedList']);
$userType = convertSelectBox1DArrayValueOnly($arrays['userType']);

$workLocation = convertSelectBox2DArray($work_location->fetchAll(), 'id', 'work_location_name');
$departmentList = convertSelectBox2DArray($department->fetchAll(), 'id', 'department_name');
$positionListSelectValues = convertSelectBox2DArray($position->fetchAll(), 'id', 'position_name');

$employeeInfo = $employee->fetchEmployeeById($employeeId);
?>

<div class="row g-3 mt-3">
    <div class="col-7">
        <?php
        if (!empty($userAccountLink) && $userAccountLink['is_active'] == 1) {
            $accountInfo = $userAccounts[$userAccountLink['user_id']];
            $buttonSet = 0;
            include './include/employee-info.php';
        } else {
            echo 'Not User Account Linked!';
        }
        ?>
    </div>

    <div class="col-5">
        <div class="table-title font-weight-bold mt-0 mb-3 ">
            <i class="fa-solid fa-user"></i> User Manager
        </div>
        <?php if (!empty($userAccountLink) && $userAccountLink['is_active'] == 1) :
            $linkedAccount = $userAccountLink['user_id'];
            if (isset($specialAccounts[$linkedAccount])) {
                $accountInfo = $userAccounts[$userAccountLink['user_id']];
            }
        ?>
            <div class="card">
                <div class="card-body">
                    <i class="fa-solid fa-circle-check"></i> Account Link to <?= $linkedAccount ?>
                    <?php if (isset($specialAccounts[$linkedAccount])) : ?>
                        <h4 class="mb-0"><?= $accountInfo['user_name'] ?> - <?= $accountInfo['first_name'] ?> <?= $accountInfo['last_name'] ?></h4>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>

        <div class="table-title font-weight-bold my-3 ">
            <i class="fa-solid fa-graduation-cap"></i> LMS Manager
        </div>

        <?php if (!empty($lmsAccountLink) && $lmsAccountLink['is_active'] == 1) :
            $linkedAccount = $lmsAccountLink['user_id'];
            if (isset($specialAccounts[$linkedAccount])) {
                $accountInfo = $specialAccounts[$linkedAccount];
            }
        ?>
            <div class="card">
                <div class="card-body">
                    <i class="fa-solid fa-circle-check"></i> Account Link to <?= $linkedAccount ?>
                    <?php if (isset($specialAccounts[$linkedAccount])) : ?>
                        <h4 class="mb-0"><?= $accountInfo['username'] ?> - <?= $accountInfo['fname'] ?> <?= $accountInfo['lname'] ?></h4>
                        <p class="mb-0 mt-2">User Level : <span class="badge bg-success"><?= $accountInfo['userlevel'] ?></span></p>
                    <?php endif ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="table-title font-weight-bold mt-0 ">
                                Winpharma Earnings
                            </div>
                        </div>
                        <?php if (isset($specialAccounts[$linkedAccount])) : ?>
                            <div class="col-6">

                                <?php
                                $userAccount = $specialAccounts[$linkedAccount];
                                $paidMarkingCount = $NotPaidMarkingCount = $approvedPayments = $NonApprovedPayments = 0;
                                $employeeUsername = $userAccount['username'];

                                $winpharmaMarkings = GetWinpharmaMarking($employeeUsername);
                                $winpharmaMarkings =  GetWinpharmaMarkingByPayment($employeeUsername, 'Paid');
                                $payableMarkings = GetWinpharmaMarkingByPayment($employeeUsername, 'Not Paid');

                                // Calculation
                                $payableMarkings = count($payableMarkings);
                                $payableAmount = $payableMarkings * $earningPerMarking;
                                ?>

                                <div class="card clickable">
                                    <div class="card-body">
                                        <h4 class="mb-0"><?= $payableMarkings ?></h4>
                                        <p class="mb-0 text-muted">Unpaid Markings</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <?php
                                $userAccount = $specialAccounts[$linkedAccount];
                                $paidMarkingCount = $NotPaidMarkingCount = $approvedPayments = $NonApprovedPayments = 0;
                                $employeeUsername = $userAccount['username'];

                                $winpharmaMarkings = GetWinpharmaMarking($employeeUsername);
                                $PaidMarkings = GetWinpharmaMarkingByPayment($employeeUsername, 'Paid');
                                $payableMarkings = GetWinpharmaMarkingByPayment($employeeUsername, 'Not Paid');

                                // Calculation
                                $PaidMarkings = count($PaidMarkings);
                                $PaidAmount = $PaidMarkings * $earningPerMarking;
                                ?>

                                <div class="card clickable">
                                    <div class="card-body">
                                        <h4 class="mb-0"><?= $PaidMarkings ?> | <?= number_format($PaidAmount, 2) ?></h4>
                                        <p class="mb-0 text-muted">Paid Markings</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Under Maintenance -->
                            <div class="col-6">
                                <div class="table-title font-weight-bold mt-0 mb-3">
                                    Ticket Rating
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-0">Your Rating : 3.8</h4>
                                        <p class="mb-0 text-muted">Out of 29 Ticket Reviews</p>
                                    </div>
                                </div>
                            </div>

                        <?php else : ?>
                            <div class="alert alert-light d-flex">
                                <i class="fa-solid fa-circle-exclamation fa-3x"></i>
                                <div class="mx-2">
                                    <h5 class="mb-0"> Not the Account Privileges are not Enough.</h5>
                                    <p class="mb-0 text-muted">Please contact Administrator to Link Account</p>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="alert alert-light d-flex">
                <i class="fa-solid fa-circle-exclamation fa-3x"></i>
                <div class="mx-3">
                    <h4 class="mb-0"> Not LMS Account Linked!</h4>
                    <p class="mb-0 text-muted">Please contact Administrator to Link Account</p>
                </div>
            </div>
        <?php endif ?>



    </div>
</div>