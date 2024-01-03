<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/reporting-functions.php';
include '../../../include/finance-functions.php';

$userName = $_POST['userName'];
$Locations = GetLocations($link);
?>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">Choice Default Location</h5>
            <p class="border-bottom pb-2"></p>
        </div>

    </div>
    <div class="row">
        <?php
        if (!empty($Locations)) {
            foreach ($Locations as $Location) {
                $location_id = $Location['location_id'];
                $location_name = $Location['location_name'];
                $active_status = "Deleted";
                $color = "warning";
                if ($Location['is_active'] == 1) {
                    $active_status = "Active";
                    $color = "primary";
                } else {
                    continue;
                }


        ?>
                <div class="col-6 col-md-6 col-lg-4 mt-2 d-flex">
                    <div class="card flex-fill clickable table-card bg-light" onclick="UpdateUserDefaultLocation('<?= $userName ?>', <?= $location_id ?>, 'defaultLocation')">
                        <div class="card-body p-2 pb-2">
                            <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                            <h1 class="tutor-name mt-2"><?= $location_name ?></h1>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-0">No Entires</p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>