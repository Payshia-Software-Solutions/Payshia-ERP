<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include './methods/functions.php';

$LoggedUser = $_POST['LoggedUser'];
$color = 'secondary';
$active_status = 'Initial';

$phoneCodes = GetPhoneCodes();
?>




<div class="loading-popup-content-right">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">Partner Information</h5>
            <span class="badge bg-<?= $color ?>"><?= $active_status ?></span>
            <p class="border-bottom pb-2"></p>
        </div>

        <div class="col-12">
            <div class="row g-2">
                <div class="col-12">
                    <label for="partner_name">Partner Name</label>
                    <input type="text" class="form-control" name="partner_name" id="partner_name" placeholder="Enter Partner Name">
                </div>

                <div class="col-3 col-md-2">
                    <label for="phone_1_country_code">Phone 1 Country Code</label>
                    <select class="form-control" name="phone_1_country_code" id="phone_1_country_code" required autocomplete="off">
                        <option value="">Select Country Code</option>
                        <?php
                        if (!empty($phoneCodes)) {
                            foreach ($phoneCodes as $SelectedArray) {
                        ?>
                                <option value="<?= $SelectedArray['phonecode'] ?>"><?= $SelectedArray['name'] ?> (+<?= $SelectedArray['phonecode'] ?>)</option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-9 col-md-4">
                    <label for="phone_1_suffix">Phone 1 Suffix</label>
                    <input type="text" class="form-control" name="phone_1_suffix" id="phone_1_suffix" placeholder="Phone Number">
                </div>

                <div class="col-3 col-md-2">
                    <label for="phone_2_country_code">Phone 2 Country Code</label>
                    <select class="form-control" name="phone_2_country_code" id="phone_2_country_code" autocomplete="off">
                        <option value="">Select Country Code</option>
                        <?php
                        if (!empty($phoneCodes)) {
                            foreach ($phoneCodes as $SelectedArray) {
                        ?>
                                <option value="<?= $SelectedArray['phonecode'] ?>"><?= $SelectedArray['name'] ?> (+<?= $SelectedArray['phonecode'] ?>)</option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-9 col-md-4">
                    <label for="phone_2_suffix">Phone 2 Suffix</label>
                    <input type="text" class="form-control" name="phone_2_suffix" id="phone_2_suffix" placeholder="Phone Number">
                </div>
            </div>


            <div class="row g-2 mt-2">
                <div class="col-6 col-md-3">
                    <label for="address_l1">Address Line 1</label>
                    <input type="text" class="form-control" name="address_l1" id="address_l1" placeholder="Street Address, P.O. Box">
                </div>
                <div class="col-6 col-md-3">
                    <label for="address_l2">Address Line 2</label>
                    <input type="text" class="form-control" name="address_l2" id="address_l2" placeholder="Apartment, Suite, Unit, Building">
                </div>
                <div class="col-6 col-md-3">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city" placeholder="City">
                </div>
                <div class="col-6 col-md-3">
                    <label for="district">District</label>
                    <input type="text" class="form-control" name="district" id="district" placeholder="District">
                </div>

                <div class="col-6">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Enter Contact Person's Name">
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 text-end">
            <button type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#phone_1_country_code').select2({
            width: 'resolve'
        });

        $('#phone_2_country_code').select2({
            width: 'resolve'
        });
    });
</script>