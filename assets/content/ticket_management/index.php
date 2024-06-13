<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods

$LoggedUser = $_POST['LoggedUser'];
$studentBatch = $_POST['studentBatch'];

$ticketList = GetTickets();
$CourseBatches = getLmsBatches();
$Locations = GetLocations($link);
$accountDetails = GetAccounts($link);

$ticketCount = count($ticketList);
$ActiveStatus = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-location-dot icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Tickets</p>
                <h1><?= $ticketCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-check icon-card"></i>
            </div>
            <div class="card-body">
                <p>Active</p>
                <h1><?= $ticketCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-xmark icon-card"></i>
            </div>
            <div class="card-body">
                <p>Closed</p>
                <h1><?= $ticketCount ?></h1>
            </div>
        </div>
    </div>
</div>



<div class="row mt-5">
    <div class="col-md-12">
        <div class="table-title font-weight-bold mb-4 mt-0">Tickets</div>
        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 offset-8">
                                <label for="TicketAssignments">Filter By</label>
                                <select onchange="GetMailBox(this.value)" class="form-control" name="TicketAssignments" id="TicketAssignments">
                                    <option value="All">All Tickets</option>
                                    <option value="MyTickets">Assign to Me</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap" id="ticketBox">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>