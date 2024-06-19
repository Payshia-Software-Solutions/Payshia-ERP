<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/Department.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$department = new Department($database);

$newData = $_POST;

// Unset Items
unset($newData['UserLevel']);
unset($newData['company_id']);
unset($newData['department_id']);

// Append Data
$newData['updated_at'] = date('Y-m-d');

if ($_POST['department_id'] == 0) {
    // Insert a new employee
    if ($department->add($newData)) {
        $error = array('status' => 'success', 'message' => 'Department added successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to add employee.' . $department->getLastError());
    }
} else {
    // Update the Employee
    $department_id = $_POST['department_id']; // Replace with the actual employee ID to update
    if ($department->update($newData, $department_id)) {
        $error = array('status' => 'success', 'message' => 'Department Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $department->getLastError());
    }
}
echo json_encode($error);
