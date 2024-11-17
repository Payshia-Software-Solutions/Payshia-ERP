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

$courseCode = $_POST['studentBatch'];
$LoggedUser = $_POST['LoggedUser'];
$assignmentId = $_POST['assignmentId'];
$activeStatus = $_POST['activeStatus'];
$indexNumber = $_POST['student_number'];
$grade_value = $_POST['grade_value'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);
$submissions = new AssignmentSubmissions($database);

// Update an employee
$updateData = [
    'assignment_id' => $assignmentId,
    'updated_at' => date("Y-m-d H:i:s"),
    'grade' => $grade_value,
    'grade_status' =>  $activeStatus
];

if ($submissions->updateGrade($updateData, $assignmentId, $indexNumber)) {
    $error = array('status' => 'success', 'message' => 'Assignment Graded successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update Assignment.' . $assignments->getLastError());
}

echo json_encode($error);
