<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';


include_once 'classes/Database.php';
include_once 'classes/Employee.php';
include_once 'classes/Position.php';

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
    <div class="col-md-12">
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
                                        <td class="text-end">
                                            <button onclick="AddNewEmployee('<?= $employee['employee_id'] ?>')" class="btn btn-success btn-sm" type="button"><i class="fa-solid fa-pencil-alt"></i> Edit</button>
                                            <button onclick="DisableEmployee('<?= $employee['employee_id'] ?>', '0')" class="btn btn-warning btn-sm" type="button"><i class="fa-solid fa-trash"></i> Disable</button>
                                            <button onclick="OpenEmployee('<?= $employee['employee_id'] ?>')" class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-eye"></i> View</button>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
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

        });

    });
</script>