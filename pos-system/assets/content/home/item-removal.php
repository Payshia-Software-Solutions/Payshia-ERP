<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$MainDataArray = GetStewards($link);

$LoggedUser = $_POST['LoggedUser'];
$stewardID = $_POST['stewardID'];

$productID = $_POST['productID'];
$invoiceNumber = $_POST['invoiceNumber'];

$Cashier = GetAccounts($link)[$LoggedUser];
if ($stewardID != 0) {
    $Steward = GetAccounts($link)[$stewardID];
    $stewardName =  $Steward['first_name'] . " " . $Steward['last_name'];
} else {
    $stewardName = "Default";
}
$LoggedName =  $Cashier['first_name'] . " " . $Cashier['last_name'];
?>

<div class="row mt-3">
    <div class="col-6">
        <p class="mb-0 text-secondary">Steward</p>
        <h5><?= $stewardID ?> - <?= $stewardName ?></h5>
    </div>
    <div class="col-6">
        <p class="mb-0 text-secondary">Cashier</p>
        <h5><?= $LoggedUser ?> - <?= $LoggedName ?></h5>
    </div>
    <div class="col-12">
        <div class="border-bottom  mb-4"></div>
    </div>
    <div class="col-12 col-md-6 col-xl-6 mb-3 d-flex">
        <?php $reason = "Change of Mind" ?>
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', '<?= $reason ?>', '<?= $stewardID ?>', '<?= $productID ?>')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2"><?= $reason ?></h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-6 mb-3 d-flex">
        <?php $reason = "Wrong Bill" ?>
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', '<?= $reason ?>', '<?= $stewardID ?>', '<?= $productID ?>')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2"><?= $reason ?></h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-6 mb-3 d-flex">
        <?php $reason = "Wrong Item" ?>
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', '<?= $reason ?>', '<?= $stewardID ?>', '<?= $productID ?>')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2"><?= $reason ?></h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-6 mb-3 d-flex">
        <?php $reason = "Cashier Entry Error" ?>
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', '<?= $reason ?>', '<?= $stewardID ?>', '<?= $productID ?>')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2"><?= $reason ?></h5>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-6 mb-3 d-flex">
        <?php $reason = "Miscommunication" ?>
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', '<?= $reason ?>', '<?= $stewardID ?>', '<?= $productID ?>')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2"><?= $reason ?></h5>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <p class="text-secondary mb-0">If not</p>
    </div>
    <div class="col-9">
        <input class="form-control" type=" text" name="reasonText" id="reasonText" placeholder="specify the reason to remove">
    </div>
    <div class="col-3">
        <button class="w-100 btn btn-dark" onclick="SetRemovalNotice('<?= $invoiceNumber ?>', document.getElementById('reasonText').value, '<?= $stewardID ?>', '<?= $productID ?>')">Remove</button>
    </div>
</div>