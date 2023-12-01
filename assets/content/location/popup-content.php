<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$Cities = GetCities($link);
$LocationName = "";
if ($UpdateKey > 0) {
    $Location = GetLocations($link)[$UpdateKey];
    $LocationName = $Location['location_name'];
}
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Location Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="location-form" method="post">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <h6 class="taxi-label">Location Name</h6>
                        <input type="text" class="form-control" value="<?= $LocationName ?>" placeholder="Enter Location Name" id="location_name" name="location_name" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <h6 class="taxi-label">Logo</h6>
                        <input type="file" class="form-control" id="location_image" name="location_image" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-2">
                        <?php
                        $labelText = "Address Line 1";
                        ?>
                        <h6 class="taxi-label"><?= $labelText ?></h6>
                        <input type="text" class="form-control" placeholder="Enter <?= $labelText ?>" id="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" name="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" required>

                    </div>

                    <div class="col-md-4 mb-2">
                        <?php
                        $labelText = "Address Line 2";
                        ?>
                        <h6 class="taxi-label"><?= $labelText ?></h6>
                        <input type="text" class="form-control" placeholder="Enter <?= $labelText ?>" id="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" name="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" required>

                    </div>
                    <div class="col-md-4 mb-2">
                        <?php
                        $labelText = "City Name";
                        ?>
                        <h6 class="taxi-label"><?= $labelText ?></h6>
                        <select class="form-control" id="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" name="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" required>
                            <?php

                            if (!empty($Cities)) {
                                foreach ($Cities as $SelectedArray) {
                                    $city_id = $SelectedArray['id'];
                                    $city_name = $SelectedArray['name_en'];
                                    $sub_name_en = $SelectedArray['sub_name_en'];
                                    $sub_name_si = $SelectedArray['sub_name_si'];
                                    $postcode = $SelectedArray['postcode'];
                                    $name_si = $SelectedArray['name_si'];
                            ?>
                                    <option value="<?= $city_id ?>"><?= $city_name ?> - <?= $postcode ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <?php
                        $labelText = "Phone Number 1";
                        ?>
                        <h6 class="taxi-label"><?= $labelText ?></h6>
                        <input type="text" class="form-control" placeholder="Enter <?= $labelText ?>" id="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" name="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" required>

                    </div>

                    <div class="col-md-6 mb-2">
                        <?php
                        $labelText = "Phone Number 2";
                        ?>
                        <h6 class="taxi-label"><?= $labelText ?></h6>
                        <input type="text" class="form-control" placeholder="Enter <?= $labelText ?>" id="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" name="<?= strtolower(str_replace(' ', '_', $labelText)) ?>" required>

                    </div>


                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="SaveLocation (1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>