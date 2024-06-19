<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/WorkLocation.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$work_location = new WorkLocation($database);

$newData = $_POST;

// Unset Items
unset($newData['UserLevel']);
unset($newData['company_id']);
unset($newData['work_location_id']);

// Append Data
$newData['updated_at'] = date('Y-m-d');

if ($_POST['work_location_id'] == 0) {
    // Insert a new employee
    if ($work_location->add($newData)) {
        $error = array('status' => 'success', 'message' => 'Work Location Type added successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to add employee.' . $work_location->getLastError());
    }
} else {
    // Update the Employee
    $work_location_id = $_POST['work_location_id']; // Replace with the actual employee ID to update
    if ($work_location->update($newData, $work_location_id)) {
        $error = array('status' => 'success', 'message' => 'Work Location Type Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $work_location->getLastError());
    }
}
echo json_encode($error);
