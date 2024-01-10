<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-clock icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Offers</p>
                <h1><?= $ActiveCount ?></h1>
            </div>
        </div>
    </div>
</div>