<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

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

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$userTheme = getUserTheme($userThemeInput);
$employeeId = isset($_POST['employeeId']) ? $_POST['employeeId'] : null;

// Read JSON file
$jsonData = file_get_contents('../../../../include/strings.json');

// Decode JSON data
$arrays = json_decode($jsonData, true);

// Assign arrays directly from JSON data
$employeeType = convertSelectBox1DArrayValueOnly($arrays['employeeType']);
$workLocation = convertSelectBox2DArray($work_location->fetchAll(), 'id', 'work_location_name');
$departmentList = convertSelectBox2DArray($department->fetchAll(), 'id', 'department_name');
$positionListSelectValues = convertSelectBox2DArray($position->fetchAll(), 'id', 'position_name');
$genderList = convertSelectBox1DArrayValueOnly($arrays['genderList']);
$marriedList = convertSelectBox1DArrayValueOnly($arrays['marriedList']);

// Get From Database
$cityListSelectValues = convertSelectBox2DArray(GetCities($link), 'id', 'name_en');

if ($employeeId != 0) {
    // Fetch all employees
    $employeeInfo = $employee->fetchEmployeeById($employeeId);
}
?>

<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-6">
            <h3 class="mb-0">Employee Registration Form</h3>
        </div>

        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="AddNewEmployee('<?= $employeeId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Cancel</button>
            <button class="btn btn-success btn-sm" onclick="SaveEmployee('<?= $employeeId ?>')" type="button"><i class="fa solid fa-floppy-disk"></i> Save Changes</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>


    </div>

    <div class="row mb-5">
        <form action="#" id="employee-form" method="post">
            <div class="row g-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2">Personal Information</h6>
                </div>
                <div class="col-md-7">
                    <?php
                    $ElementName = 'Full Name';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-5">
                    <?php
                    $ElementName = 'Name with Initials';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Phone Number';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnNumberInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Email';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnNumberInput($ElementName, '', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'National Identification Number';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnNumberInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Date of Birth';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnDateInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Gender';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $genderList, $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Married Status';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $marriedList, $defaultValue)
                    ?>
                </div>

                <div class="col-12">
                    <p class="text-secondary mb-0">Current Address</p>
                </div>
                <div class="col-md-4">
                    <?php
                    $ElementName = 'Address Line 1';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    $ElementName = 'Address Line 2';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-4">
                    <?php
                    $ElementName = 'City';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $cityListSelectValues, $defaultValue)
                    ?>
                </div>

                <div class="col-12">
                    <p class="text-secondary mb-0">Permanent Address</p>
                </div>
                <div class="col-md-4">
                    <?php
                    $ElementName = 'Permanent Address Line 1';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    $ElementName = 'Permanent Address Line 2';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-4">
                    <?php
                    $ElementName = 'Permanent City';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $cityListSelectValues, $defaultValue)
                    ?>
                </div>

                <div class="col-12">
                    <h6 class="border-bottom pb-2">Employment Details</h6>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Employee ID';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue, 'readonly')
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Date of Hire';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnDateInput($ElementName, 'required', 'form-control', $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Employee Type';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $employeeType, $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Work Location';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $workLocation, $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Department';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $departmentList, $defaultValue)
                    ?>
                </div>

                <div class="col-md-3">
                    <?php
                    $ElementName = 'Position';
                    $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnSelectInput($ElementName, 'required', 'form-control', $positionListSelectValues, $defaultValue)
                    ?>
                </div>

                <div class="col-12">
                    <h6 class="border-bottom pb-2">Miscellaneous</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $ElementName = 'NIC';
                            $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                            echo ReturnFileInput('NIC', '', 'form-control', $defaultValue) ?>
                        </div>

                        <div class="col-md-3">
                            <?php
                            $ElementName = 'Cover Image';
                            $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                            echo ReturnFileInput('Cover Image', '', 'form-control', $defaultValue) ?>
                        </div>

                        <div class="col-md-3">
                            <?php
                            $ElementName = 'Grama Niladhari Certificate';
                            $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                            echo ReturnFileInput('Grama Niladhari Certificate', '', 'form-control', $defaultValue) ?>
                        </div>

                        <div class="col-md-3">
                            <?php
                            $ElementName = 'Police Certificate';
                            $defaultValue = ($employeeId != 0) ? $employeeInfo[convertToSnakeCase($ElementName)] : '';
                            echo ReturnFileInput('Police Certificate', '', 'form-control', $defaultValue) ?>
                        </div>
                    </div>
                </div>




            </div>
        </form>
    </div>

    <?php
    // echo renderForm($elementArray, $form_name);
    ?>
</div>