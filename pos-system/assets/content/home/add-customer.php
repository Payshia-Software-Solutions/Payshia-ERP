<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$CustomerList = GetLocationCustomers($link, $LocationID);
$Cities = GetCities($link);

$region_id = $route_id = $area_id = "";

$regionList = GetRegions($link);
$routeList = GetRoutes($link);
$areaList = GetAreas($link);
?>

<style>
    .customer-container {
        max-height: 100vh;
    }
</style>

<div class="row mt-3">
    <div class="col-12">
        <h4>Fill the following Fields</h4>
        <hr>
    </div>

    <div class="customer-container">
        <div class="row mb-2">
            <div class="col-md-6">
                <label for="customer_first_name">First Name*</label>
                <input type="text" name="customer_first_name" id="customer_first_name" class="form-control" placeholder="Thilina">
            </div>
            <div class="col-md-6">
                <label for="customer_last_name">Last Name*</label>
                <input type="text" name="customer_last_name" id="customer_last_name" class="form-control" placeholder="Ruwan">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="phone_number">Phone Number*</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="077 0 481 363">
            </div>
            <div class="col-md-6 mb-3">
                <label for="email_address">Email Address (Optional)</label>
                <input type="text" name="email_address" id="email_address" class="form-control" placeholder="thilinaruwan112@gmail.com">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="phone_number">Address Line 1 (Optional)</label>
                <input type="text" name="address_line1" id="address_line1" class="form-control" placeholder="533A3">
            </div>
            <div class="col-md-4 mb-3">
                <label for="phone_number">Address Line 2 (Optional)</label>
                <input type="text" name="address_line2" id="address_line2" class="form-control" placeholder="Hospital Rd">
            </div>
            <div class="col-md-4 mb-3">
                <label for="customer_lname">City*</label>
                <select name="city_id" id="city_id" class="form-control">
                    <option value="">Select City</option>

                    <?php
                    if (!empty($Cities)) {
                        foreach ($Cities as $SelectArray) {
                    ?>
                            <option value="<?= $SelectArray['id']  ?>"><?= $SelectArray['name_en']  ?> - <?= $SelectArray['name_si']  ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-4 mb-3">
                <label for="region_id">Region</label>
                <select name="region_id" id="region_id" class="form-control">
                    <option value="">Select Region</option>

                    <?php
                    if (!empty($regionList)) {
                        foreach ($regionList as $SelectArray) {

                            if ($SelectArray['is_active'] == 0) {
                                continue;
                            }
                    ?>
                            <option <?= ($region_id == $SelectArray['id']) ? "selected" : "" ?> value="<?= $SelectArray['id']  ?>"><?= $SelectArray['region_name'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-4 mb-3">
                <label for="phone_number">Route*</label>
                <select name="route_id" id="route_id" class="form-control">
                    <option value="">Select Route</option>

                    <?php
                    if (!empty($routeList)) {
                        foreach ($routeList as $SelectArray) {
                            if ($SelectArray['is_active'] == 0) {
                                continue;
                            }

                    ?>
                            <option <?= ($route_id == $SelectArray['id']) ? "selected" : "" ?> value="<?= $SelectArray['id']  ?>"><?= $SelectArray['route_title']  ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-4 mb-3">
                <label for="customer_lname">Area*</label>
                <select name="area_id" id="area_id" class="form-control">
                    <option value="">Select Area</option>

                    <?php
                    if (!empty($areaList)) {
                        foreach ($areaList as $SelectArray) {
                            if ($SelectArray['is_active'] == 0) {
                                continue;
                            }
                    ?>
                            <option <?= ($area_id == $SelectArray['id']) ? "selected" : "" ?> value="<?= $SelectArray['id']  ?>"><?= $SelectArray['area_title']  ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-3">
                <button onclick="SaveCustomer()" class="text-white w-100 btn btn-dark add-discount-button btn-lg p-3">
                    <i class="fa-solid fa-plus btn-icon"></i> Save Customer</button>
            </div>
        </div>

    </div>

</div>