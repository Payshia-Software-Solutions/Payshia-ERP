<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

require '../../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


$courseCode = $_POST['studentBatch'];

$CourseAssignments = GetAssignments($courseCode);
$CourseAssignmentsCount = count($CourseAssignments);

// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// Initialize an array to store table data
$tableData = array();

$assignmentSubmissions = GetAssignmentSubmissions();
if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
    // Get file details
    $fileName = $_FILES["fileToUpload"]["name"];
    $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];

    // Generate timestamp
    $timestamp = date('Y-m-d_H-i-s');

    // Append timestamp to the filename
    $newFileName = $timestamp . '_' . $fileName;

    // Move the uploaded file to a location of your choice with the new filename
    $uploadPath = "../../../../../assets/uploads/" . basename($newFileName);
    move_uploaded_file($fileTmpName, $uploadPath);
    // Create a new PhpSpreadsheet object
    $spreadsheet = IOFactory::load($uploadPath);
    $sheet = $spreadsheet->getActiveSheet();

    // Initialize a flag to identify the first row
    $isFirstRow = true;
    $FirstRow = array();
} else {
    echo "Error uploading file.";
}
?>

<div class="loading-popup-content">
    <div class="row g-2">
        <div class="col-10">
            <h4 class="site-title mb-0">Review Data before Commit</h4>
        </div>

        <div class="col-2 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="col-12">
            <div class="border-bottom"></div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <table id="tableGrades" class="table table-hover table-bordered">

                <?php
                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    if ($isFirstRow) {
                        $isFirstRow = false;
                        foreach ($cellIterator as $cell) {
                            $FirstRow[] =  $cell->getValue();
                        }
                ?>
                        <thead>
                            <th><?= $FirstRow[0] ?></th>
                            <?php
                            for ($i = 1; $i <= $CourseAssignmentsCount; $i++) {
                            ?>
                                <th><?= $FirstRow[$i] ?></th>
                            <?php
                            }
                            ?>

                        </thead>
                <?php
                        break;
                    }
                }
                ?>

                <tbody>
                    <?php
                    $isFirstRow = true;
                    foreach ($sheet->getRowIterator() as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);

                        if ($isFirstRow) {
                            $isFirstRow = false;
                            continue;
                        }

                        // Start building the table row
                        echo '<tr>';

                        $rowData = array(); // Initialize an array to store row data
                        // Loop through each cell in the row

                        $i = 0;
                        foreach ($cellIterator as $cell) {
                            $value = 0;
                            if ($i == 0) {
                                $rowData[$FirstRow[$i]] = $cell->getValue();
                            }
                            $value = $cell->getValue();
                            for ($j = 1; $j < $CourseAssignmentsCount + 1; $j++) {
                                $rowData['assignment' . $j]['assignmentId'] = $FirstRow[$j];
                                if ($i != 0) {

                                    // Set the value to '0.00' if it's null
                                    if ($value === null) {
                                        $value = '0.00';
                                    }
                                    $rowData['assignment' . $j]['score'] = $value;
                                }
                            }


                            echo '<td class="text-center">' . $value . '</td>'; // Get cell value for the body
                            $i += 1;
                        }
                        // End building the table row
                        echo '</tr>';
                        // Add row data to the table data array
                        $tableData[] = $rowData;
                    }
                    ?>
                </tbody>
                <?php

                ?>

            </table>
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button type="button" onclick="CommitChanges()" class="btn btn-success btn-sm">Commit <?= $fileName; ?></button>
                </div>
            </div>
            <form id="commitForm">
                <input type="hidden" name="uploadedFile" id="uploadedFile" value="<?= $uploadPath; ?>">
                <input type="hidden" id="studentBatch" name="studentBatch" value="<?= $courseCode ?>">
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tableGrades').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ],
            pageLength: 5
        });
    });
</script>