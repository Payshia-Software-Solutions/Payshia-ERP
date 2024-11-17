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
    <div class="col-12">
        <h5 class="table-title">Control Center</h5>
    </div>

    <div class="col-md-3 col-6 col-xxl-2">
        <a href="#" rel="noopener noreferrer">
            <div class="card clickable">
                <div class="card-body text-center">
                    <i class="fa-solid fa-certificate icon-card"></i>
                    <h5 class="mb-0">Certificate Print</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6 col-xxl-2">
        <a href="./lms-winpharma-grading" rel="noopener noreferrer">
            <div class="card clickable">
                <div class="card-body text-center">
                    <i class="fa-solid fa-prescription-bottle-medical icon-card"></i>
                    <h5 class="mb-0">Winpharma Grading</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6 col-xxl-2">
        <a href="./lms-pharma-hunter" rel="noopener noreferrer">
            <div class="card clickable">
                <div class="card-body text-center">

                    <i class="fa-solid fa-boxes-packing icon-card"></i>
                    <h5 class="mb-0">Pharma Hunter</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6 col-xxl-2">
        <a href="./lms-assignments" rel="noopener noreferrer">
            <div class="card clickable">
                <div class="card-body text-center">

                    <i class="fa-solid fa-book-open-reader icon-card"></i>
                    <h5 class="mb-0">Assignments</h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-6 col-xxl-2">
        <a href="#" rel="noopener noreferrer">
            <div class="card clickable">
                <div class="card-body text-center">

                    <i class="fa-solid fa-calendar-check icon-card"></i>
                    <h5 class="mb-0">Setup Appointments</h5>
                </div>
            </div>
        </a>
    </div>
</div>