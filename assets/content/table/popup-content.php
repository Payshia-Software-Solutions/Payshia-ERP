<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';


$Locations = GetLocations($link);
$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$TableName = $table_location = "";
if ($UpdateKey > 0) {
    $Table = GetTables($link)[$UpdateKey];
    $TableName = $Table['table_name'];
    $table_location = $Table['location_id'];
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
            <h3 class="mb-0">Table Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="location-form" method="post">
                <div class="row">


                    <div class="col-6 mb-2">
                        <label class="">Select Branch</label>
                        <select class="form-control form-control-sm" name="location_id" id="location_id" required>
                            <option value="">Select Branch</option>
                            <?php
                            if (!empty($Locations)) {
                                foreach ($Locations as $selected_array) {
                                    if ($selected_array['is_active'] != 1) {
                                        continue;
                                    }
                            ?>
                                    ?>
                                    <option <?= ($selected_array['location_id'] == $table_location) ? 'selected' : '' ?> value="<?= $selected_array['location_id'] ?>"><?= $selected_array['location_name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Table Name</h6>
                        <input type="text" class="form-control" value="<?= $TableName ?>" placeholder="Enter Table Name" id="table_name" name="table_name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="Save (1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>