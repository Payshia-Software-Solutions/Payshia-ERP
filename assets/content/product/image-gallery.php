<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$DestinationID = $_POST['DestinationID'];
$ActiveStatus = 0;
$DestinationImages = GetDestinationGallery($link, $DestinationID);
$Destination = GetDestinations($link, $ActiveStatus)[$DestinationID];
$SnapCount = count($DestinationImages);
?>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-map-location icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Snaps</p>
                <h1><?= $SnapCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-8 text-end mt-4 mt-md-0">
        <button class="btn btn-dark" type="button" onclick="AddImage(1,0)">+ Add New Image</button>
    </div>
</div>


<div class="row my-4">
    <?php
    if (!empty($DestinationImages)) {
        foreach ($DestinationImages as $DestinationImage) {
            $destination_name = $Destination['destination_name'];

            $active_status = "Deleted";
            $color = "warning";
            if ($DestinationImage['is_active'] == 1) {
                $active_status = "Active";
                $color = "info";
            } else if ($DestinationImage['is_active'] == 2) {
                $active_status = "Closed";
                $color = "success";
            }

    ?>

            <div class="col-6 col-md-3 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-2 pb-2">
                        <div class="card-back-image" style="background-image: url('./assets/images/destination/<?= $DestinationImage['destination_id'] ?>/<?= $DestinationImage['image_path'] ?>');"></div>
                        <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                        <div class="text-end mt-2">
                            <?php if ($Destination['is_active'] != 2) { ?>
                                <button class="mt-0 mb-1 btn btn-sm btn-danger view-button" type="button" onclick="ChangeDestinationImageStatus(0, '<?= $Destination['destination_id'] ?>')"><i class="fa-solid fa-trash"></i> Remove</button>
                            <?php } ?>
                        </div>
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
                    <p class="mb-0">No Snaps</p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<div class="loading-popup" id="loading-popup">
    <div class="loading-popup-content">
        <div class="row">
            <div class="col-12 w-100 text-end">
                <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-0">Snap Information</h3>
                <p class="border-bottom pb-2">Please fill the all required fields.</p>

                <form id="img-form" method="post">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h6 class="taxi-label">Image Description</h6>
                            <input type="text" class="form-control" placeholder="Enter Image Description" id="img_description" name="img_description" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-6 mb-2">
                            <h6 class="taxi-label">Alt Texts</h6>
                            <input type="text" class="form-control" placeholder="Enter Alt Text" id="alt_text" name="alt_text" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <h6 class="taxi-label">Image File</h6>
                            <input type="file" class="form-control" id="destination_img" name="destination_img" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button class="btn btn-light" type="button" name="BookPackageButton" id="BookPackageButton">Clear</button>
                            <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick=" SaveImage (1, 0, '<?= $DestinationID ?>')">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>