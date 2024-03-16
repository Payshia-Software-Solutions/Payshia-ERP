<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$LoggedUser = $_POST['LoggedUser'];

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$studentCount = count($accountDetails);
?>

<div class="row mt-5">

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Students</p>
                <h1><?= $studentCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-9 text-end">
        <button type="button" onclick="GetSearchPopUp()" class="btn btn-dark">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
    </div>


</div>

<div class="row g-3">
    <div class="col-12 mb-4">
        <h5 class="table-title">Master Operations</h5>
    </div>

    <div class="col-md-3 col-6 col-xxl-2">
        <div class="card clickable">
            <div class="card-body text-center">
                <i class="fa-solid fa-certificate icon-card"></i>
                <h5 class="mb-0">Certificate Print</h5>
            </div>
        </div>
    </div>
</div>