<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$MainDataArray = GetTablesByLocation($link, $LocationID);
?>

<style>
    .x-button {
        display: none;
    }
</style>
<div class="row mt-3">
    <div class="col-12 col-md-4 col-xl-4 mb-3 d-flex">
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetTableValue ('0', 'Take Away')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2">Take Away</h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 col-xl-4 mb-3 d-flex">
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetTableValue ('-1', 'Retail')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2">Retail</h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 col-xl-4 mb-3 d-flex">
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetTableValue ('-2', 'Delivery')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2">Delivery</h5>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <h5>Set Table</h5>
</div>

<div class="row mt-3">
    <?php
    if (!empty($MainDataArray)) {
        foreach ($MainDataArray as $SelectArray) {
            $active_status = "Deleted";
            $color = "warning";
            if ($SelectArray['location_id'] != $LocationID) {
                continue;
            }

            if ($SelectArray['is_active'] != 1) {
                continue;
            }

            $card_color = "";

            $LocationName = GetLocations($link)[$SelectArray['location_id']]['location_name'];
            if (isInvoiceNumberExistsForTable($link, $SelectArray['id']) > 0) {
                $availability = "N/A";
                $availability_color = "danger";
                $card_color = "secondary";
            } else {
                $availability = "Available";
                $availability_color = "success";
            }

    ?>
            <div class="col-6 col-md-3 mb-3 d-flex">
                <div class="card bg-<?= $card_color ?> table-card flex-fill shadow-sm clickable" onclick="SetTableValue ('<?= $SelectArray['id'] ?>', '<?= $SelectArray['table_name'] ?>')">
                    <div class="card-body p-0">

                        <span class="badge text-light mt-2 bg-primary">Dine-In</span> <span class="badge text-light mt-2 bg-<?= $availability_color ?>"><?= $availability ?></span>
                        <h4 class="tutor-name mt-2 mb-0"><?= $SelectArray['table_name'] ?></h4>


                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h4 class="mb-0">No Tables in this Location</h4>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<div class="row">
    <div class="col-12 text-end">
        <button type="button" onclick="OpenIndex()" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate"></i></button>
        <button type="button" onclick="PromptCloseApp()" class="btn refresh-button mr-2"><i class="fa-solid fa-power-off"></i> Exit</button>
    </div>
</div>