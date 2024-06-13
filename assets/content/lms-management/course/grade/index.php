<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';
$batchList = getLmsBatches();

?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-10">
            <h2 class="site-title mb-0">Grading Assignments</h2>
        </div>

        <div class="col-2 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>

    <div class="row mb-2 mt-3">
        <div class="col-12">
            <label>Select Course</label>
            <select class="form-control" name="batchId" id="batchId" onchange="GetTemplateExcel(this.value)">
                <?php
                if (!empty($batchList)) {
                    foreach ($batchList as $selectedArray) {
                        $CourseAssignments = GetAssignments($selectedArray['course_code']);

                ?>
                        <option value="<?= $selectedArray['course_code'] ?>"><?= $selectedArray['course_code'] ?> - <?= $selectedArray['course_name'] ?> - <?= count($CourseAssignments) ?></option>
                <?php

                    }
                }
                ?>
            </select>
        </div>
    </div>

    <div class="mt-2" id="templateExcel"></div>
    <div class="border-bottom my-2"></div>
    <h5>Please select the Import Data file.</h5>

    <form id="excelUploadForm" action="#" method="post" enctype="multipart/form-data">
        <div class="row g-2">
            <div class="col-md-10">
                <input type="file" class="form-control form-control-sm" name="fileToUpload" id="fileToUpload">
            </div>
            <div class="col-md-2">
                <button onclick="SaveGradeData()" class="btn btn-dark w-100" style="height: 45px;" type="button" value="Upload" name="submit"><i class="fa-solid fa-table"></i> View Dataset</button>
            </div>
        </div>
    </form>
</div>