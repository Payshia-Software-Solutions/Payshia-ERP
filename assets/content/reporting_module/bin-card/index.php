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
$Products = GetProducts($link);
?>
<style>
    .action-button {
        height: 45px;
    }
</style>
<h4 class="border-bottom pb-2">Bin Card Report</h4>
<form id="report-form" action="post">
    <div class="row">

        <div class="col-6 col-md-4">
            <label>From Date</label>
            <input type="date" class="form-control" name="from-date-input" id="from-date-input" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label>To Date</label>
            <input type="date" class="form-control" name="to-date-input" id="to-date-input" value="<?= date('Y-m-d') ?>">
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

        <div class="col-12 col-md-8 mt-2">
            <label class="form-label">Select Product</label>
            <select class="form-control" name="select_product" id="select_product" required autocomplete="off">
                <?php
                if (!empty($Products)) {
                    foreach ($Products as $SelectedArray) {
                        if ($SelectedArray['active_status'] != 1) {
                            continue;
                        }
                ?>
                        <option value="<?= $SelectedArray['product_id'] ?>"><?= $SelectedArray['product_name'] ?> - <?= $SelectedArray['cost_price'] ?> - <?= $SelectedArray['product_code'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-12 col-md-4 text-end mt-2">
            <p class="mb-0">Action</p>
            <button class="mb-0 btn action-button btn-success view-button" type="button" onclick="GetBinCardReport()"><i class="fa-solid fa-eye"></i> Get</button>
            <button class="mb-0 btn action-button btn-dark view-button" type="button" onclick="PrintBinCardReport()"><i class="fa-solid fa-print"></i> Print</button>
        </div>

    </div>
</form>


<div class="row mt-3">
    <div class="col-12">
        <div class="border-top mb-3"></div>
        <div id="report-view"></div>
    </div>
</div>

<script>
    $('#select_product').select2()
</script>