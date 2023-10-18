<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$UnitName = "";
if ($UpdateKey > 0) {
    $Unit = GetUnit($link)[$UpdateKey];
    $UnitName = $Unit['unit_name'];
}
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Unit Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="unit-form" method="post">
                <div class="row">
                    <div class="col-12 mb-2">
                        <h6 class="taxi-label">Unit</h6>
                        <input type="text" class="form-control" value="<?= $UnitName ?>" placeholder="Enter Unit" id="unit_name" name="unit_name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="SaveUnit(1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>