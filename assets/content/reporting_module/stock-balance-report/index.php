<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
$location_id = $_POST['default_location'];
$Locations = GetLocations($link);
$Suppliers =  GetSupplier($link);
$Sections = GetSections($link);
$Departments = GetDepartments($link);
$Categories = GetCategories($link);
?>
<style>
    .action-button {
        height: 45px;
    }
</style>
<h4 class="border-bottom pb-2">Stock Balance Report</h4>
<form id="report-form" action="post">
    <div class="row">

        <div class="col-6 col-md-4">
            <label>Select Date</label>
            <input type="date" class="form-control" name="date-input" id="date-input" value="<?= date('Y-m-d') ?>" readonly>
        </div>
        <div class="col-6 col-md-4">
            <label>Select Branch</label>
            <select class="form-control" name="location_id" id="location_id" required autocomplete="off">
                <option value="">Select Branch</option>
                <?php
                if (!empty($Locations)) {
                    foreach ($Locations as $SelectedArray) {
                        if ($SelectedArray['is_active'] != 1) {
                            continue;
                        }
                ?>

                        <option <?= ($SelectedArray['location_id'] == $location_id) ? 'selected' : '' ?> value="<?= $SelectedArray['location_id'] ?>"><?= $SelectedArray['location_name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>


        <div class="col-md-4">
            <label for="section_id">Section</label>
            <select class="form-select form-control" name="section_id" id="section_id" required>
                <option value="All">All</option>
                <?php
                if (!empty($Sections)) {
                    foreach ($Sections as $selected_array) {
                        if ($selected_array['is_active'] != 1) {
                            continue;
                        }
                ?>
                        ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['section_name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-4 mt-2">
            <label for="department_id">Department</label>
            <select class="form-select form-control" name="department_id" id="department_id" required autocomplete="off">
                <option value="All">All</option>
                <?php
                if (!empty($Departments)) {
                    foreach ($Departments as $selected_array) {
                        if ($selected_array['is_active'] != 1) {
                            continue;
                        }
                ?>
                        ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['department_name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-4 mt-2">
            <label for="category_id">Category</label>
            <select class="form-select form-control" name="category_id" id="category_id" required autocomplete="off">
                <option value="All">All</option>
                <?php
                if (!empty($Categories)) {
                    foreach ($Categories as $selected_array) {
                        if ($selected_array['is_active'] != 1) {
                            continue;
                        }
                ?>
                        ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['category_name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>



        <div class="col-12 col-md-4 text-end mt-2">
            <p class="mb-0">Action</p>
            <button class="mb-0 btn action-button btn-success view-button" type="button" onclick="GetStockBalanceReport()"><i class="fa-solid fa-eye"></i> Get</button>
            <button class="mb-0 btn action-button btn-dark view-button" type="button" onclick="PrintStockBalanceReport()"><i class="fa-solid fa-print"></i> Print</button>
        </div>

    </div>
</form>


<div class="row mt-3">
    <div class="col-12">
        <div class="border-top mb-3"></div>
        <div id="report-view"></div>
    </div>
</div>