<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/Position.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$position = new Position($database);

$newPosition = $_POST;

// Unset Items
unset($newPosition['UserLevel']);
unset($newPosition['company_id']);
unset($newPosition['positionId']);

// Append Data
$newPosition['updated_at'] = date('Y-m-d');

if ($_POST['positionId'] == 0) {
    // Insert a new employee
    if ($position->add($newPosition)) {
        $error = array('status' => 'success', 'message' => 'Position added successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to add employee.' . $position->getLastError());
    }
} else {
    // Update the Employee
    $positionId = $_POST['positionId']; // Replace with the actual employee ID to update
    if ($position->update($newPosition, $positionId)) {
        $error = array('status' => 'success', 'message' => 'Position Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $position->getLastError());
    }
}
echo json_encode($error);
