<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Usage
$assignmentId = $_POST['assignmentId'];
$gradeValue = $_POST['gradeValue'];
$studentNumber = $_POST['studentNumber'];

$saveResult = SaveEditedAssignmentGrade($assignmentId, $gradeValue, $studentNumber);
echo json_encode($saveResult);
