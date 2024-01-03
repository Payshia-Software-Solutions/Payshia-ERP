<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/lms-functions.php';
$location_id = $_POST['default_location'];
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();
?>
<style>
    .action-button {
        height: 45px;
    }
</style>
<h4 class="border-bottom pb-2">Student Report</h4>
<form id="report-form" action="post">
    <div class="row">
        <div class="col-12 col-md-4">
            <p class="text-secondary mb-0">Batch</p>
            <select class="form-control" name="studentBatch" id="studentBatch" onchange="OpenIndex(this.value, document.getElementById('orderType').value)">
                <option value="0">All</option>
                <?php
                if (!empty($CourseBatches)) {
                    foreach ($CourseBatches as $selectedArray) {

                ?>
                        <option value="<?= $selectedArray['course_code'] ?>"><?= $selectedArray['course_code'] ?> - <?= $selectedArray['course_name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-12 col-md-8 text-end">
            <p class="mb-0">Action</p>
            <button class="mb-0 btn action-button btn-success view-button" type="button" onclick="GetStudentReport()"><i class="fa-solid fa-eye"></i> Get</button>
            <button class="mb-0 btn action-button btn-dark view-button" type="button" onclick="PrintStudentReport()"><i class="fa-solid fa-print"></i> Print Report</button>
            <button class="mb-0 btn action-button btn-dark view-button" type="button" onclick="PrintAddressReport()"><i class="fa-solid fa-print"></i> Print Address List</button>
        </div>

    </div>
</form>


<div class="row mt-3">
    <div class="col-12">
        <div class="border-top mb-3"></div>
        <div id="report-view"></div>
    </div>
</div>