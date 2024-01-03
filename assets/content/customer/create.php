<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$UpdateKey = $_POST['updateKey'];
$isActive = $_POST['isActive'];
$default_location = $_POST['default_location'];
$ActiveStatus = $opening_balance = $credit_limit = $credit_days = 0;
$customerFirstName = $customerLastName = $phoneNumber = $address_line1 = $address_line2 = $city_id = $email_address =  $location_id =  "";
$customerList = GetCustomers($link);
if ($UpdateKey != 0) {
    $selectedCustomer = $customerList[$UpdateKey];
    $customerFirstName = $selectedCustomer['customer_first_name'];
    $customerLastName = $selectedCustomer['customer_last_name'];
    $phoneNumber = $selectedCustomer['phone_number'];
    $address_line1 = $selectedCustomer['address_line1'];
    $address_line2 = $selectedCustomer['address_line2'];
    $city_id = $selectedCustomer['city_id'];
    $email_address = $selectedCustomer['email_address'];
    $opening_balance = $selectedCustomer['opening_balance'];
    $location_id = $selectedCustomer['location_id'];
    $credit_limit = $selectedCustomer['credit_limit'];
    $credit_days = $selectedCustomer['credit_days'];
    $region_id = $selectedCustomer['region_id'];
    $route_id = $selectedCustomer['route_id'];
    $area_id = $selectedCustomer['area_id'];
}


$Locations = GetLocations($link);
$Cities = GetCities($link);
$regionList = GetRegions($link);
$routeList = GetRoutes($link);
$areaList = GetAreas($link);
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light  rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>

    <h2 class="site-title mb-0">New Customer</h2>
    <h6 class="mb-4 border-bottom pb-2">Customer Information</h6>
    <form id="customer-form" method="post">
        <div class="row mt-3">
            <div class="customer-container">
                <div class="row mb-2">
                    <div class="col-2">
                        <label for="customer_first_name">ID*</label>
                        <input type="text" name="customer_id" value="<?= $UpdateKey ?>" id="customer_id" class="form-control" placeholder="Thilina" readonly>
                    </div>
                    <div class="col-5">
                        <label for="customer_first_name">First Name*</label>
                        <input type="text" name="customer_first_name" value="<?= $customerFirstName ?>" id="customer_first_name" class="form-control" placeholder="Thilina">
                    </div>
                    <div class="col-5">
                        <label for="customer_last_name">Last Name*</label>
                        <input type="text" name="customer_last_name" value="<?= $customerLastName ?>" id="customer_last_name" class="form-control" placeholder="Ruwan">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-3">
                        <label for="customer_first_name">Credit Limit*</label>
                        <input type="number" step="0.01" name="credit_limit" value="<?= $credit_limit ?>" id="credit_limit" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-3">
                        <label for="customer_last_name">Credit Days*</label>
                        <input type="number" step="0.01" name="credit_days" value="<?= $credit_days ?>" id="credit_days" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-3">
                        <label for="customer_last_name">Opening Balance*</label>
                        <input type="number" step="0.01" name="opening_balance" value="<?= $opening_balance ?>" id="opening_balance" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-3">
                        <label for="phone_number">Branch*</label>
                        <select class="form-control form-control-sm" name="location_id" id="location_id" required>
                            <option value="">Select Branch</option>
                            <?php
                            if (!empty($Locations)) {
                                foreach ($Locations as $selected_array) {
                                    if ($selected_array['is_active'] != 1) {
                                        continue;
                                    }
                            ?>
                                    ?>
                                    <option <?= ($selected_array['location_id'] == $default_location) ? 'selected' : '' ?> value="<?= $selected_array['location_id'] ?>"><?= $selected_array['location_name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="phone_number">Phone Number*</label>
                        <input type="text" name="phone_number" value="<?= $phoneNumber ?>" id="phone_number" class="form-control" placeholder="077 0 481 363">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="email_address">Email Address (Optional)</label>
                        <input type="text" name="email_address" id="email_address" class="form-control" value="<?= $email_address ?>" placeholder="thilinaruwan112@gmail.com">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="phone_number">Address Line 1 (Optional)</label>
                        <input type="text" name="address_line1" id="address_line1" value="<?= $address_line1 ?>" class="form-control" placeholder="533A3">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="phone_number">Address Line 2 (Optional)</label>
                        <input type="text" name="address_line2" id="address_line2" value="<?= $address_line2 ?>" class="form-control" placeholder="Hospital Rd">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="customer_lname">City*</label>
                        <select name="city_id" id="city_id" class="form-control">
                            <option value="">Select City</option>

                            <?php
                            if (!empty($Cities)) {
                                foreach ($Cities as $SelectArray) {
                            ?>
                                    <option <?= ($city_id == $SelectArray['id']) ? "selected" : "" ?> value="<?= $SelectArray['id']  ?>"><?= $SelectArray['name_en']  ?> - <?= $SelectArray['name_si']  ?></option>
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
                    <div class="col-12 mt-3 text-end">
                        <button type="button" onclick=" SaveCustomer('<?= $isActive ?>', '<?= $UpdateKey ?>') " class="text-white btn btn-dark">
                            <i class="fa-solid fa-plus btn-icon"></i> Save Customer</button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>