<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/Employee.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$employee = new Employee($database);

// Update an employee
$updateData = [
    'is_active' => $_POST['is_active']
];

$employee_id = $_POST['employee_id']; // Replace with the actual employee ID to update
if ($employee->updateEmployee($updateData, $employee_id)) {
    $error = array('status' => 'success', 'message' => 'Employee Updated successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $employee->getLastError());
}


echo json_encode($error);
