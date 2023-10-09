<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$UserLevel = $_POST['UserLevel'];
$StudentNumber = $_POST['LoggedUser'];


$ClassesCount = $TutorCount = $UsersCount = $ClassesCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-chalkboard icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Orders</p>
                <h1><?= $ClassesCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-chalkboard-teacher icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Packages</p>
                <h1><?= $TutorCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-person icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Live</p>
                <h1><?= $UsersCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-sack-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Payments</p>
                <h1></h1>
            </div>
        </div>
    </div>
</div>