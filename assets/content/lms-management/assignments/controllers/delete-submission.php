<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include_once '../classes/LmsDatabase.php';
include_once '../classes/Assignments.php';
include_once '../classes/AssignmentSubmissions.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

$courseCode = $_POST['courseCode'];
$LoggedUser = $_POST['LoggedUser'];
$assignmentId = $_POST['assignmentId'];
$is_active = $_POST['isActive'];
$indexNumber = $_POST['indexNumber'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);
$submissions = new AssignmentSubmissions($database);

// Update an employee
$updateData = [
    'assignment_id' => $assignmentId,
    'updated_at' => date("Y-m-d H:i:s"),
    'is_active' =>  $is_active
];

if ($submissions->updateGrade($updateData, $assignmentId, $indexNumber)) {
    $error = array('status' => 'success', 'message' => 'Submission Deleted successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update Assignment.' . $assignments->getLastError());
}

echo json_encode($error);
