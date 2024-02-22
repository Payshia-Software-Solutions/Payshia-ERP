<?php

use libphonenumber\NumberFormat;

require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Define Variables
$studentBalance = 0;

$LoggedUser = $_POST['LoggedUser'];
$phoneNumber = $_POST['phoneNumber'];
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 pb-2 border-bottom fw-bold">Send SMS</h5>
        </div>

        <div class="col-12 text-center">
            <p class="mb-0">Phone Number</p>
            <h2 class="mb-0 fw-bold"><?= $phoneNumber ?></h2>
        </div>

        <div class="col-12">
            <p class="mb-0">Message</p>
            <textarea rows="10" class="form-control" placeholder="Message Content Here.."></textarea>
        </div>


        <div class="col-12 text-end">
            <button type="button" class="btn btn-dark">Send SMS</button>
        </div>
    </div>


</div>