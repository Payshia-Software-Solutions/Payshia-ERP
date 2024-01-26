<?php

require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/reporting-functions.php';
include '../../../include/finance-functions.php';
include '../../../include/settings_functions.php';

$UserLevel = $_POST['UserLevel'];
$StudentNumber = $_POST['LoggedUser'];

// Get today's date
$today = date('Y-m-d');
$ClassesCount = $TutorCount = $UsersCount = $ClassesCount = 0;

$Locations = GetLocations($link);
$defaultLocation = GetUserDefaultValue($link, $StudentNumber, 'defaultLocation');
$default_location_name = $Locations[$defaultLocation]['location_name'];
?>
<style>
    .location-title {
        font-weight: 700;
    }

    #date-time {
        font-size: 20px;
    }
</style>

<div class="row mt-5">

    <div class="col-md-6 col-lg-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-chart-line icon-card"></i>
            </div>
            <div class="card-body">
                <p>Total Sales</p>
                <h1><?= formatAccountBalance(getInvoicesByDateAll($link, $today)) ?></h1>
                <div class="badge bg-success"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-money-bill icon-card"></i>
            </div>
            <div class="card-body">
                <p>Total Receipts</p>
                <h1><?= formatAccountBalance(getReceiptsByDateAll($link, $today)) ?></h1>
                <div class="badge bg-success"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-warehouse icon-card"></i>
            </div>
            <div class="card-body">
                <p>Inventory Value</p>
                <h1 class="<?= (getAccountBalance($inventoryAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($inventoryAccountId)) ?></h1>
                <div class="badge bg-success">Up to <?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-hand-holding-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Accounts Receivable</p>
                <h1 class="<?= (getAccountBalance($accountsReceivableAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($accountsReceivableAccountId)) ?></h1>
                <div class="badge bg-success">Up to <?= $today ?></div>
            </div>
        </div>
    </div>
    
</div>

<div class="row mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Hourly Sales - <?= $default_location_name ?> | <?= $today ?></div>
                <?php include './component/hourly-sales.php' ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Sale Analysis - <?= $default_location_name ?> | <?= $today ?></div>
                <?php include './component/sales-analysis.php' ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <p class="text-secondary mb-0">Default Location</p>
                <h3 class="location-title mb-0 border-bottom pb-2"><?= $default_location_name ?></h3>
                <div id="date-time"></div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body text-center">

                <div class="table-title font-weight-bold mb-4 mt-0">Login to POS</div>
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="./pos-system" target="_blank">
                            <button class="btn btn-light p-3 rounded-4 shadow-sm">
                                <img src="./pos-system/assets/images/pos-logo.png" class="w-25 pb-2">
                            </button>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6 col-lg-8">
        <div class="card mb-3">
            <div class="card-body">

                <?php include './component/day-by-day-sale.php' ?>
            </div>
        </div>
    </div>
</div>




<script>
    // Function to update the date and time element
    function updateDateTime() {
        const dateTimeElement = document.getElementById('date-time');
        const currentDate = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);

        dateTimeElement.textContent = formattedDate;
    }

    // Call the function to update the date and time immediately
    updateDateTime();

    // Set an interval to update the date and time every second (1000 milliseconds)
    setInterval(updateDateTime, 1000);
</script>