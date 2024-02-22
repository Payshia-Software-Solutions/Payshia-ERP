<?php

use libphonenumber\NumberFormat;

require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Define Variables
$studentBalance = 0;

$LoggedUser = $_POST['LoggedUser'];
$toEmailAddress = $_POST['toEmailAddress'];
$fromAddress = $_POST['fromAddress'];
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 pb-2 border-bottom fw-bold">Send Email</h5>
        </div>

        <div class="col-6">
            <p class="mb-0">From Address</p>
            <h5 class="mb-0 fw-bold"><?= $fromAddress ?></h5>
        </div>

        <div class="col-6">
            <p class="mb-0">To Address</p>
            <h5 class="mb-0 fw-bold"><?= $toEmailAddress ?></h5>
        </div>

        <div class="col-12">
            <p class="mb-0">Subject</p>
            <input class="form-control" type="text" name="mailSubject" id="mailSubject" placeholder="Enter Email Subject here">
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

<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea',
        plugins: 'fullscreen anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'fullscreen undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>