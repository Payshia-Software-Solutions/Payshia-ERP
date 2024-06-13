<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';


include_once 'migration/Database.php';
include_once 'migration/Employee.php';
include_once 'migration/Position.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$employee = new Employee($db);
$position = new Position($db);
// Fetch all employees
$employees = $employee->fetchAllEmployees();

include './methods/functions.php'; //Ticket Methods

$LoggedUser = $_POST['LoggedUser'];
$studentBatch = $_POST['studentBatch'];

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
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-users icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Employees</p>
                <h1><?= $userCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-user-shield icon-card"></i>
            </div>
            <div class="card-body">
                <p>Admins</p>
                <h1><?= count($adminAccounts) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-user-group icon-card"></i>
            </div>
            <div class="card-body">
                <p>Other</p>
                <h1><?= count($userAccounts) - count($adminAccounts) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3 text-end mt-4 mt-md-0">
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewEmployee()"><i class="fa-solid fa-plus"></i> New Employee</button>
        <button class="btn btn-dark rounded-2" type="button" onclick="OpenMigrations()"><i class="fa-solid fa-plus"></i> Migrations</button>
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewPosition()"><i class="fa-solid fa-plus"></i> Position</button>
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewDepartment()"><i class="fa-solid fa-plus"></i> Department</button>
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewWorkLocation()"><i class="fa-solid fa-plus"></i> Work Location</button>
    </div>
</div>

<div class="border-bottom my-3"></div>

<div class="row g-2">

    <div class="col-md-8">
        <div class="table-title font-weight-bold mt-0 mb-3 ">Employee List</div>
        <div class="card">
            <div class="card-body">
                <?php if (empty($employees)) : ?>
                    <p>No employees found.</p>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hovered table-striped" id="employee-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Position</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) :
                                    if ($employee['is_active'] != 1) {
                                        continue;
                                    }

                                    $positionName = $position->fetchById($employee['position'])['position_name'];

                                    $activeStatus = ($statusList[$employee['is_active']])['value'];
                                    if ($employee['is_active'] == 0) {
                                        $bgColor = "danger";
                                    } else if ($employee['is_active'] == 1) {
                                        $bgColor = "success";
                                    } else if ($employee['is_active'] == 3) {
                                        $bgColor = "warning";
                                    } else {
                                        $bgColor = "info";
                                    }

                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($employee['employee_id']) ?></td>
                                        <td><?= htmlspecialchars($employee['full_name']) ?></td>
                                        <td><?= htmlspecialchars($employee['phone_number']) ?></td>
                                        <td><span class="badge bg-<?= $bgColor ?>"><?= $activeStatus ?></span></td>
                                        <td><?= $positionName ?></td>
                                        <td>
                                            <button onclick="AddNewEmployee('<?= $employee['employee_id'] ?>')" class="btn btn-success btn-sm" type="button"><i class="fa-solid fa-pencil-alt"></i> Edit</button>
                                            <button onclick="DisableEmployee('<?= $employee['employee_id'] ?>', '0')" class="btn btn-warning btn-sm" type="button"><i class="fa-solid fa-trash"></i> Disable</button>
                                            <button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-eye"></i> View</button>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="row g-3">
            <div class="col-12">

                <div class="table-title font-weight-bold mt-0 mb-3 ">
                    Winpharma Earnings
                    <div class="badge bg-success">Addon+</div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php
                        if (!empty($specialAccounts)) {
                            $loopCount = 1;
                            foreach ($specialAccounts as $userAccount) {

                                $paidMarkingCount = $NotPaidMarkingCount = $approvedPayments = $NonApprovedPayments = 0;
                                $employeeUsername = $userAccount['username'];
                                $winpharmaMarkings = GetWinpharmaMarking($employeeUsername);

                                // Details of Payments
                                $PaymentInfo = WinpharmaEmployeePaymentsSummary($employeeUsername);
                                if (isset($PaymentInfo[0])) {
                                    $paidMarkingCount = $PaymentInfo[0]['total_marking_count'];
                                    $NonApprovedPayments = $PaymentInfo[0]['total_payment_count'];
                                }

                                if (isset($PaymentInfo[1])) {
                                    $NotPaidMarkingCount = $PaymentInfo[1]['total_marking_count'];
                                    $approvedPayments = $PaymentInfo[1]['total_payment_count'];
                                }

                                $paidMarkingCount =  $paidMarkingCount + $NotPaidMarkingCount;
                                $payableMarkings = count($winpharmaMarkings) - $paidMarkingCount;
                                $payableAmount = $payableMarkings * $earningPerMarking;

                                $loopCount++;
                        ?>
                                <div class="clickable p-2 w-100 hover">
                                    <div class="fw-bold pb-1"><?= $userAccount['username'] ?> - <?= $userAccount['fname'] ?> <?= $userAccount['lname'] ?></div>
                                    <div class="badge bg-success"><?= $userAccount['userlevel'] ?></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-0">Markings</p>
                                            <h4><?= $payableMarkings ?> x <?= number_format($earningPerMarking, 2) ?> = <?= number_format($payableAmount, 2) ?></h4>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                if ($loopCount <= $userCount) {
                                ?>
                                    <div class="border-bottom my-1"></div>
                                <?php
                                }
                                ?>
                        <?php
                            }
                        }
                        ?>

                        <?php
                        if (empty($userAccounts)) {
                            $loopCount = 1;
                            foreach ($userAccounts as $userAccount) {

                                $paidMarkingCount = $NotPaidMarkingCount = $approvedPayments = $NonApprovedPayments = 0;
                                $employeeUsername = $userAccount['user_name'];
                                $winpharmaMarkings = GetWinpharmaMarking($employeeUsername);

                                // Details of Payments
                                $PaymentInfo = WinpharmaEmployeePaymentsSummary($employeeUsername);
                                if (isset($PaymentInfo[0])) {
                                    $paidMarkingCount = $PaymentInfo[0]['total_marking_count'];
                                    $NonApprovedPayments = $PaymentInfo[0]['total_payment_count'];
                                }

                                if (isset($PaymentInfo[1])) {
                                    $NotPaidMarkingCount = $PaymentInfo[1]['total_marking_count'];
                                    $approvedPayments = $PaymentInfo[1]['total_payment_count'];
                                }

                                $paidMarkingCount =  $paidMarkingCount + $NotPaidMarkingCount;
                                $payableMarkings = count($winpharmaMarkings) - $paidMarkingCount;
                                $payableAmount = $payableMarkings * $earningPerMarking;

                                $loopCount++;
                        ?>
                                <div class="clickable p-2 w-100 hover">
                                    <div class="fw-bold pb-1"><?= $userAccount['user_name'] ?> - <?= $userAccount['first_name'] ?> <?= $userAccount['last_name'] ?></div>


                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-0">Markings</p>
                                            <h4><?= count($winpharmaMarkings) ?> x <?= number_format($earningPerMarking, 2) ?> = <?= number_format($payableAmount, 2) ?></h4>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                if ($loopCount <= $userCount) {
                                ?>
                                    <div class="border-bottom my-1"></div>
                                <?php
                                }
                                ?>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#employee-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [1, 'desc'],
                [0, 'asc']
            ]
        });

    });
</script>