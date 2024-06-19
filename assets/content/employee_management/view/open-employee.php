<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);

// Classes
include_once '../classes/Database.php';
include_once '../classes/Employee.php';
include_once '../classes/Position.php';
include_once '../classes/Department.php';
include_once '../classes/WorkLocation.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$employee = new Employee($db);
$position = new Position($db);
$department = new Department($db);
$work_location = new WorkLocation($db);

$employeeId = isset($_POST['employee_id']) ? $_POST['employee_id'] : null;

// Read JSON file & Decode JSON data
$arrays = json_decode(file_get_contents('../../../../include/strings.json'), true);

// Assign arrays directly from JSON data
$employeeType = convertSelectBox1DArrayValueOnly($arrays['employeeType']);
$genderList = convertSelectBox1DArrayValueOnly($arrays['genderList']);
$marriedList = convertSelectBox1DArrayValueOnly($arrays['marriedList']);
$userType = convertSelectBox1DArrayValueOnly($arrays['userType']);

// Get Other Values
$cityList = GetCities($link);
$userAccounts = GetAccounts($link);
$lmsUserAccounts =  GetLmsStudents();

$workLocation = convertSelectBox2DArray($work_location->fetchAll(), 'id', 'work_location_name');
$departmentList = convertSelectBox2DArray($department->fetchAll(), 'id', 'department_name');
$positionListSelectValues = convertSelectBox2DArray($position->fetchAll(), 'id', 'position_name');

$allAccountLinks = $employee->fetchAllAccountLinks();
// Fetch Employee Details
$employeeInfo = $employee->fetchEmployeeById($employeeId);
$targetFolder = 'assets/content/employee_management/assets/images/employee';

// Accounts Link Check
$userAccountLink = $employee->CheckAccountLink($employeeId, 1);
$lmsAccountLink = $employee->CheckAccountLink($employeeId, 2);

?>

<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Employee Details</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenEmployee('<?= $employeeId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>




    <div class="row mb-5">

        <div class="col-md-6">
            <?php
            $buttonSet = 1;
            include '../include/employee-info.php' ?>
        </div>
        <div class="col-md-6">
            <!-- Miscellaneous -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="border-bottom pb-2">Miscellaneous</h4>
                            <div class="row g-2">

                                <div class="col-md-6">
                                    <h6 class="text-secondary">NIC</h6>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($employeeInfo['nic'] != '') {
                                    ?>
                                        <a href="<?= $targetFolder ?>/<?= $employeeId ?>/nic/<?= $employeeInfo['nic'] ?>" target="_blank" rel="noopener noreferrer">
                                            <h6 class="btn btn-dark btn-sm">View File</h6>
                                        </a>
                                    <?php
                                    } else {
                                        echo 'Not Available';
                                    }
                                    ?>

                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-secondary">Grama Niladhari Certificate</h6>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($employeeInfo['grama_niladhari_certificate'] != '') {
                                    ?>
                                        <a href="<?= $targetFolder ?>/<?= $employeeId ?>/grama_niladhari_certificate/<?= $employeeInfo['grama_niladhari_certificate'] ?>" target="_blank" rel="noopener noreferrer">
                                            <h6 class="btn btn-dark btn-sm">View File</h6>
                                        </a>
                                    <?php
                                    } else {
                                        echo 'Not Available';
                                    }
                                    ?>

                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-secondary">Police Certificate</h6>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($employeeInfo['police_certificate'] != '') {
                                    ?>
                                        <a href="<?= $targetFolder ?>/<?= $employeeId ?>/police_certificate/<?= $employeeInfo['police_certificate'] ?>" target="_blank" rel="noopener noreferrer">
                                            <h6 class="btn btn-dark btn-sm">View File</h6>
                                        </a>
                                    <?php
                                    } else {
                                        echo 'Not Available';
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Miscellaneous -->

            <div class="row g-2 mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="border-bottom pb-2">User Account Link</h5>
                            <?php
                            if (!empty($userAccountLink) && $userAccountLink['is_active'] == 1) {
                                $accountInfo = $userAccounts[$userAccountLink['user_id']];
                            ?>
                                <div class="badge bg-primary d-inline p-2 mt-2">Linked to <?= $userAccountLink['user_id'] ?> | <?= $accountInfo['first_name'] ?> <?= $accountInfo['last_name'] ?></div>
                                <input type="hidden" name="user_id" id="user_id" value="<?= $userAccountLink['user_id'] ?>">

                                <div class="mt-3 text-end">
                                    <button onclick="CreateUserLink('<?= $employeeId ?>', 1, 0)" type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-link-slash"></i> Unlink</button>
                                </div>
                            <?php
                            } else {
                            ?>
                                <form action="#" method="post">
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <select name="user_id" id="user_id" class="form-control">
                                                <option value="">Select User Account</option>
                                                <?php if (!empty($userAccounts)) : ?>
                                                    <?php foreach ($userAccounts as $accountInfo) : ?>
                                                        <option value="<?= $accountInfo['user_name'] ?>"><?= $accountInfo['user_name'] ?> - <?= $accountInfo['first_name'] ?> <?= $accountInfo['last_name'] ?></option>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button onclick="CreateUserLink('<?= $employeeId ?>', 1, 1)" type="button" class="btn btn-dark d-block btn-lg w-100"><i class="fa-solid fa-link"></i> Link</button>
                                        </div>
                                    </div>
                                </form>
                                <script>
                                    $('#user_id').select2();
                                </script>

                            <?php
                            }
                            ?>


                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="border-bottom pb-2">LMS Account Link <span class="badge bg-success mx-2">Custom+</span></h5>
                            <?php
                            if (!empty($lmsAccountLink) && $lmsAccountLink['is_active'] == 1) {
                                $accountInfo = $lmsUserAccounts[$lmsAccountLink['user_id']];
                            ?>
                                <div class="badge bg-primary d-inline p-2 mt-2">Linked to <?= $lmsAccountLink['user_id'] ?> | <?= $accountInfo['first_name'] ?> <?= $accountInfo['last_name'] ?></div>
                                <input type="hidden" name="lms_user_id" id="lms_user_id" value="<?= $lmsAccountLink['user_id'] ?>">

                                <div class="mt-3 text-end">
                                    <button onclick="CreateUserLink('<?= $employeeId ?>', 2, 0)" type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-link-slash"></i> Unlink</button>
                                </div>
                            <?php
                            } else {
                            ?>
                                <form action="#" method="post" id="lms-user-link-form">
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <select name="lms_user_id" id="lms_user_id" class="form-control">
                                                <option value="">Select LMS User Account</option>
                                                <?php if (!empty($lmsUserAccounts)) : ?>
                                                    <?php foreach ($lmsUserAccounts as $accountInfo) : ?>
                                                        <option value="<?= $accountInfo['username'] ?>"><?= $accountInfo['username'] ?> - <?= $accountInfo['first_name'] ?> <?= $accountInfo['last_name'] ?></option>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button onclick="CreateUserLink('<?= $employeeId ?>', 2, 1)" type="button" class="btn btn-dark d-block btn-lg w-100"><i class="fa-solid fa-link"></i> Link</button>
                                        </div>
                                    </div>
                                </form>
                                <script>
                                    $('#lms_user_id').select2();
                                </script>



                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>