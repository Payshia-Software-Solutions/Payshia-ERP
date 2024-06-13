<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include './methods/functions.php';

$LoggedUser = $_POST['LoggedUser'];

$deliveryPartners = GetDeliveryPartners();
$courierServiceCount = count($deliveryPartners);
?>


<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-list-ol icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Partners</p>
                <h1><?= $courierServiceCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-9 text-end mt-4 mt-md-0">
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewPartner()"><i class="fa-solid fa-plus"></i> New Partner</button>
    </div>
</div>

<div class="border-bottom my-3"></div>