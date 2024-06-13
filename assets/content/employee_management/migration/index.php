<?php
// Use the classes
// Include the classes
include_once 'Database.php';
include_once 'TableCreator.php';
include_once 'Employee.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new TableCreator object
$tableCreator = new TableCreator($database);

// Create the Employee table
if ($tableCreator->CreateWorkLocationTable()) {
    $error = array('status' => 'success', 'message' => 'employee_position Table Created successfully.');
} else {
    // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
    $error = array('status' => 'error', 'message' => 'Failed to Create employee_position Table.' . $tableCreator->getLastError());
}

echo json_encode($error);


// Delete an employee
// $employeeIdToDelete = 1; // Replace with the actual employee ID to delete
// if ($employee->deleteEmployee($employeeIdToDelete)) {
//     echo "Employee deleted successfully.";
// } else {
//     echo "Failed to delete employee.";
// }


// Alter the employee table to add a new column
// $alterQuery = "ALTER TABLE employee_details ADD COLUMN email VARCHAR(255)";
// $tableCreator->alterEmployeeTable($alterQuery);
