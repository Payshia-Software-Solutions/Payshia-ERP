<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$assignmentSubmissions = GetAssignmentSubmissions();

// // Parameters
$courseCode = $_POST['studentBatch'];
$uploadedFile = $_POST['uploadedFile'];

$CourseAssignments = GetAssignments($courseCode);
$CourseAssignmentsCount = count($CourseAssignments);
// $LoggedUser = $_POST['LoggedUser'];

require '../../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
// Initialize an array to store table data
$tableData = array();


// Create a new PhpSpreadsheet object
$spreadsheet = IOFactory::load($uploadedFile);
$sheet = $spreadsheet->getActiveSheet();

// Initialize a flag to identify the first row
$isFirstRow = true;
$FirstRow = array();
// Loop through each row in the worksheet
foreach ($sheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    if ($isFirstRow) {
        // After the first iteration, set the flag to false
        $isFirstRow = false;
        foreach ($cellIterator as $cell) {
            $FirstRow[] =  $cell->getValue();
        }
        continue;
    }


    $rowData = array(); // Initialize an array to store row data
    // Loop through each cell in the row

    $i = 0;
    foreach ($cellIterator as $cell) {
        if ($i == 0) {
            $rowData[$FirstRow[$i]] = $cell->getValue();
        }

        for ($j = 1; $j < $CourseAssignmentsCount + 1; $j++) {
            $rowData['assignment' . $j]['assignmentId'] = $FirstRow[$j];
            if ($i != 0) {
                $rowData['assignment' . $i]['score'] = $cell->getValue();
            }
        }
        $i += 1;
    }

    // Add row data to the table data array
    $tableData[] = $rowData;
}

$UpdateErrorArray = $insertErrorArray = array();
// Initialize variables to track insert and update results
$insertSuccess = false;
$updateSuccess = false;
// Get the current timestamp
$currentTime = date('Y-m-d H:i:s');
// Prepare the SQL statements
$insertSQL = "INSERT INTO `assignment_submittion` (`assignment_id`, `created_by`, `created_at`, `status`, `grade`) VALUES (?, ?, ?, 'Graded', ?)";
$updateSQL = "UPDATE `assignment_submittion` SET grade = ? WHERE `assignment_id` = ? AND `created_by` = ?";

// Prepare the INSERT statement
$insertStmt = $lms_link->prepare($insertSQL);
if (!$insertStmt) {
    echo "Error preparing insert statement: " . $lms_link->error;
}

// Prepare the UPDATE statement
$updateStmt = $lms_link->prepare($updateSQL);
if (!$updateStmt) {
    echo "Error preparing update statement: " . $lms_link->error;
}

// Loop through the table data to execute queries
foreach ($tableData as $data) {
    $student_id = $data['student_id'];

    for ($j = 1; $j < $CourseAssignmentsCount + 1; $j++) {
        $assignment_id = $data['assignment' . $j]['assignmentId'];
        $grade = $data['assignment' . $j]['score'];

        if (isset($assignmentSubmissions[$assignment_id . '-' . $student_id])) {
            // Update
            $updateStmt->bind_param("sss", $grade, $assignment_id, $student_id);
            // Execute the statement
            if ($updateStmt->execute()) {
                $affected_rows = $updateStmt->affected_rows;
                $updateSuccess = true;
            } else {
                $UpdateErrorArray[] = "Error executing update statement: " . $updateStmt->error;
            }
        } else {
            // Insert
            $currentTime = date('Y-m-d H:i:s');
            $insertStmt->bind_param("ssss", $assignment_id, $student_id, $currentTime, $grade);

            if ($insertStmt->execute()) {
                $affected_rows = $insertStmt->affected_rows;
                $insertSuccess = true;
            } else {
                $insertErrorArray[] = "Error executing update statement: " . $updateStmt->error;
            }
        }
    }
}

// Close prepared statements
$insertStmt->close();
$updateStmt->close();

// Check and display results

if ($updateSuccess) {
    echo "Data updated successfully!";
} else if ($insertSuccess) {
    echo "Data inserted successfully!";
} else {
    echo "Error inserting/Updating data: " . $lms_link->error;
}
