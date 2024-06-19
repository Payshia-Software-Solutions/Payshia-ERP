<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/Employee.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$employee_id = $_POST['employee_id'];
$lms_user_id = $_POST['lms_user_id'];
$user_id = $_POST['user_id'];
$user_type = $_POST['user_type'];
$is_active = $_POST['is_active'];

// Create a new Employee object
$employee = new Employee($database);

$user_id = ($user_type == 2) ? $lms_user_id : $user_id;

$newDataset = [
    'user_id' => $user_id,
    'employee_id' => $employee_id,
    'account_type' => $user_type,
    'is_active' => $is_active,
    'updated_at' => date("Y-m-d H:i:s"),
    'created_by' => $_POST['LoggedUser']
];

$userAccountLink = $employee->CheckAccountLink($employee_id, 1);
$lmsAccountLink = $employee->CheckAccountLink($employee_id, 2);


if (!empty($userAccountLink) && ($user_type == 1)) {
    // Update the employee
    if ($employee->UpdateLinkWithUserAccount($newDataset, $employee_id, $user_type)) {
        $error = array('status' => 'success', 'message' => 'User Link Updated successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to Create User Link.' . $employee->getLastError());
    }
} else if (!empty($lmsAccountLink) && ($user_type == 2)) {
    // Update the employee
    if ($employee->UpdateLinkWithUserAccount($newDataset, $employee_id, $user_type)) {
        $error = array('status' => 'success', 'message' => 'LMS Link Updated successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to Create User Link.' . $employee->getLastError());
    }
} else {
    // Insert a new employee
    if ($employee->LinkWithUserAccount($newDataset)) {
        $error = array('status' => 'success', 'message' => 'Link created successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to Create User Link.' . $employee->getLastError());
    }
}

echo json_encode($error);
