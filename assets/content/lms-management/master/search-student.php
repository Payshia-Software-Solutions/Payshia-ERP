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

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 pb-2 border-bottom fw-bold">Student Information</h5>
        </div>

        <div class="col-md-6 offset-md-3">
            <input type="text" class="form-control p-3 text-center border-2" placeholder="Enter Student Number" name="studentNumber" id="studentNumber">
        </div>

        <div class="col-md-6 offset-md-3 text-center">
            <button onclick="GetStudentInformation($('#studentNumber').val())" type="button" class="btn btn-dark btn-lg">Get Information</button>
        </div>

    </div>
</div>