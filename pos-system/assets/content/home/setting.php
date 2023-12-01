<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

include '../../../../include/settings_functions.php';

$LocationID = $_POST['LocationID'];
$CustomerList = GetLocationCustomers($link, $LocationID);
$Cities = GetCities($link);
?>
<style>
    /* .x-button {
        display: none;
    } */
</style>
<div class="row mt-3">
    <div class="col-12">
        <h4>Settings</h4>
        <hr>
    </div>

    <div class="col-6">
        <!-- Item Image setting -->
        <?php
        $IconMode = GetSetting($link, $LocationID, 'itemImage');
        ?>
        <div class="form-check form-switch">
            <input class="form-check-input" <?= ($IconMode == 1) ? 'checked' : '' ?> type="checkbox" role="switch" id="itemImageSetting">
            <label class="form-check-label" for="itemImageSetting">Show Item Images</label>
        </div>
    </div>

    <div class="col-6">
        <!-- Item Image setting -->
        <?php
        $brandFilter = GetSetting($link, $LocationID, 'brandFilter');
        ?>
        <div class="form-check form-switch">
            <input class="form-check-input" <?= ($brandFilter == 1) ? 'checked' : '' ?> type="checkbox" role="switch" id="brandFilterSetting">
            <label class="form-check-label" for="brandFilterSetting">Show Brand Filter</label>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-12 text-end">
        <button type="button" onclick="OpenIndex()" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate"></i></button>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Attach a change event handler to the checkbox
        $('#itemImageSetting').change(function() {
            // Get the current state of the switch (checked or unchecked)
            var settingValue = $(this).prop('checked');
            UpdateSetting('itemImage', settingValue)
        });

        $('#brandFilterSetting').change(function() {
            // Get the current state of the switch (checked or unchecked)
            var settingValue = $(this).prop('checked');
            UpdateSetting('brandFilter', settingValue)
        });
    });
</script>