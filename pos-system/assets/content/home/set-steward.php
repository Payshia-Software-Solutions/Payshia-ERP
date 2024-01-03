<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$MainDataArray = GetStewards($link);
?>

<style>
    .x-button {
        display: none;
    }
</style>
<div class="row mt-3">
    <div class="col-12 col-md-4 col-xl-4 mb-3 d-flex">
        <div class="card table-card flex-fill shadow-sm clickable" onclick="SetStewardValue ('0', 'Default')">
            <div class="card-body p-0">
                <h5 class="tutor-name mt-2">Default</h5>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <h5>Set Steward</h5>
</div>

<div class="row mt-3">
    <?php
    if (!empty($MainDataArray)) {
        foreach ($MainDataArray as $SelectArray) {

            if ($SelectArray['user_status'] == 0) {
                continue;
            }

    ?>
            <div class="col-12 col-md-4 mb-3 d-flex">
                <div class="card table-card flex-fill shadow-sm clickable" onclick="SetStewardValue ('<?= $SelectArray['user_name'] ?>', '<?= $SelectArray['first_name'] ?> <?= $SelectArray['last_name'] ?>')">
                    <div class="card-body p-0">
                        <span class="badge text-light mt-2 bg-dark"><?= $SelectArray['user_name'] ?></span>
                        <h4 class="tutor-name mt-2 mb-0"><?= $SelectArray['first_name'] ?> <?= $SelectArray['last_name'] ?></h4>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <p class="mb-0 text-secondary">No Stewards in this Location</p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<div class="row">
    <div class="col-12 text-end">
        <button type="button" onclick="OpenIndex()" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate"></i></button>
        <button type="button" onclick="PromptCloseApp()" class="btn refresh-button mr-2"><i class="fa-solid fa-power-off"></i> Exit</button>
    </div>
</div>