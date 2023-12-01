<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$SectionName = $pos_display = "";
if ($UpdateKey > 0) {
    $Section = GetSections($link)[$UpdateKey];
    $SectionName = $Section['section_name'];
    $pos_display = $Section['pos_display'];
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
            <h3 class="mb-0">Section Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="location-form" method="post">
                <div class="row">
                    <div class="col-8 mb-2">
                        <h6 class="taxi-label">Section Name</h6>
                        <input type="text" class="form-control" value="<?= $SectionName ?>" placeholder="Enter Section Name" id="section_name" name="section_name" required>
                    </div>
                    <div class="col-4 mb-2">
                        <h6 class="taxi-label">POS Display</h6>
                        <select class="form-control" name="pos_display" id="pos_display">
                            <option <?= ($pos_display == 1) ? 'selected' : '' ?> value="1">Display</option>
                            <option <?= ($pos_display == 0) ? 'selected' : '' ?> value="0">Not Display</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="SaveSection (1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>