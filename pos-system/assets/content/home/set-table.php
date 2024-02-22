<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];

$Locations = GetLocations($link);
$MainDataArray = GetTablesByLocation($link, $LocationID);
$locationType = $Locations[$LocationID]['location_type'];
?>

<style>
    .x-button {
        display: none;
    }
</style>

<div class="row mt-3">
    <div class="col-12 text-end">
        <button onclick="OpenReturnBox(0)" type="button" class="btn btn-warning text-white"><i class="fa-solid fa-right-left m-0"></i> Return</button>
        <button onclick="OpenRefund(0)" type="button" class="btn btn-danger"><i class="fa-solid fa-money-bill-trend-up"></i> Refund</button>
        <button type="button" id="hold-list" class="btn refresh-button mr-2" onclick="GetHoldInvoices ('<?= $LocationID ?>', 0)"> <i class="fa-solid fa-bars"></i> Hold Bills </button>
        <button type="button" onclick="toggleFullscreen()" class="btn refresh-button mr-2"><i class="fa-solid fa-expand"></i></button>
    </div>
</div>

<div class="row mt-4">
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
<?php

if (!empty($MainDataArray)) {
?>
    <div class="row mt-3">
        <h5>Set Table</h5>
    </div>

    <div class="row mt-3">
        <?php
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
        ?>

    </div>
<?php
} else {
?>
    <div class="col-12">
        <div class="card bg-light mb-3">
            <div class="card-body">
                <p class="mb-0 text-secondary">No Tables in this Location</p>
            </div>
        </div>
    </div>
<?php
}
?>

<div class="row">
    <div class="col-12 text-end">
        <a href="./choice-location" onclick="handleLinkClick(this)" type="button" id="changeLocationLink" class="btn btn-secondary mr-2">
            <i class="fa-solid fa-location-dot"></i> Change Location </a>
    </div>
</div>

<script>
    // Define the function to handle the link click event
    function handleLinkClick(event) {
        showOverlay()
        // Prevent the default behavior of following the link
        event.preventDefault();

        // Retrieve the href attribute of the clicked link
        var href = event.currentTarget.getAttribute('href');

        // Perform any additional actions here, for example, logging the href
        console.log('Link clicked:', href);

        // You can also redirect to the href if needed
        window.location.href = href;
    }
</script>

<script>
    function openURL(url) {
        showOverlay()
        // Open the URL in a new window
        window.location.href = url;
    }
</script>