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

$courseCode = $_POST['studentBatch'];
$LoggedUser = $_POST['LoggedUser'];
$assignmentId = $_POST['assignmentId'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);

// Handle file upload
$file_name = $_POST['temp_name'];
if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
    $target_dir = "../../../../../uploads/assignments/";
    $target_file = $target_dir . basename($_FILES["assignment_file"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file type is allowed
    $allowedTypes = array("pdf", "doc", "docx", "jpg", "png", "jpeg", "webp");
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
            $file_name = $_FILES["assignment_file"]["name"];
        } else {
            $error = array('status' => 'error', 'message' => 'Failed to upload file.');
            echo json_encode($error);
            exit();
        }
    } else {
        $error = array('status' => 'error', 'message' => 'Invalid file type.');
        echo json_encode($error);
        exit();
    }
}

// Combine due date and due time
$due_date = $_POST['due_date'];
$due_time = $_POST['due_time'];
$due_date_time = $due_date . ' ' . $due_time . ':00'; // Assuming seconds are not included in the input



// Update an employee
$updateData = [
    'type' => $_POST['assignment_type'],
    'assignment_name' => $_POST['assignment_name'],
    'due_date' => $due_date_time,
    'file_path' =>  $file_name,
    'active_status' =>  $_POST['activeStatus'],
    'created_at' =>  date("Y-m-d H:i:s"),
    'created_by' =>  $LoggedUser,
    'course_code' => $courseCode
];

if ($assignmentId == 0) :
    if ($assignments->add($updateData)) {
        $error = array('status' => 'success', 'message' => 'Assignment saved successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Insert Assignment.' . $assignments->getLastError());
    }

else :
    if ($assignments->update($updateData, $assignmentId)) {
        $error = array('status' => 'success', 'message' => 'Assignment Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update Assignment.' . $assignments->getLastError());
    }

endif;

echo json_encode($error);
