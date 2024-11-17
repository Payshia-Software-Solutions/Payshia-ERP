<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include_once '../classes/LmsDatabase.php';
include_once '../classes/Assignments.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

$assignmentId = $_POST['assignmentId'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);

// Update an employee
$updateData = [
    'active_status' =>  $_POST['activeStatus'],
];

if ($assignments->update($updateData, $assignmentId)) {
    $error = array('status' => 'success', 'message' => 'Assignment Updated successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update Assignment.' . $assignments->getLastError());
}

echo json_encode($error);
