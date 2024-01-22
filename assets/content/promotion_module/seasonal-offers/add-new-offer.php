<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/promotion-functions.php';

// Get
$bankList = GetBankList($link);
$startDate = $endDate = date('Y-m-d');
$startTime = $endTime = date('H:i');
$bankId = $minValue = $maxDiscount = $offerName = $offerDescription = $discountPercentage = "";

?>

<style>
    .weekday {
        width: 20px !important;
        height: 20px !important;
    }
</style>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Offer Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form action="#" method="post">
                <div class="row g-2">
                    <div class="col-md-4">
                        <p class="mb-0 text-secondary">Offer Name</p>
                        <input class="form-control" type="text" name="offerName" id="offerName" placeholder="Enter Offer Name" value="<?= $offerName ?>" required>
                    </div>
                    <div class="col-md-2">
                        <p class="mb-0 text-secondary">Discount(%)</p>
                        <input class="form-control text-center" type="number" min="0" max="100" name="discountPercentage" id="discountPercentage" placeholder="Percentage" value="<?= $discountPercentage ?>" required>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">Min Value</p>
                        <input class="form-control text-center" type="number" min="0" name="minValue" id="minValue" placeholder="Minimum Bill Value" value="<?= $minValue ?>" required>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">Max Discount</p>
                        <input class="form-control text-center" type="number" min="0" name="maxDiscount" id="maxDiscount" placeholder="Maximum Discount" value="<?= $maxDiscount ?>" required>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">Start Date</p>
                        <input type="date" class="form-control" value="<?= $startDate ?>" name="startDate" id="startDate" required>
                    </div>

                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">Start Time</p>
                        <input type="time" class="form-control" value="<?= $startTime ?>" name="startDate" id="startDate" required>
                    </div>

                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">End Date</p>
                        <input type="date" class="form-control" value="<?= $endDate ?>" name="endDate" id="endDate" required>
                    </div>

                    <div class="col-md-3">
                        <p class="mb-0 text-secondary">End Time</p>
                        <input type="time" class="form-control" value="<?= $endTime ?>" name="startDate" id="startDate" required>
                    </div>
                </div>

                <div class="row mt-2 g-2">
                    <div class="col-12">
                        <p class="mb-0 text-secondary">Offer Available Days</p>
                    </div>
                    <?php
                    $SavedDaysArray = ["Monday"];
                    $daysArray = ["Monday",  "Tuesday",  "Wednesday",  "Thursday",  "Friday",  "Saturday",  "Sunday"];
                    if (!empty($daysArray)) {
                        foreach ($daysArray as $weekDay) {

                            $isChecked = in_array($weekDay, $SavedDaysArray) ? 'checked' : '';
                    ?>
                            <div class="col-4 col-md-3">
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="weekDays[]" id="weekDays" class="form-check-input weekday" value="<?= $weekDay; ?>" <?= $isChecked ?>>
                                        <?= $weekDay; ?>
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="row mt-2 g-2">
                    <div class="col-md-4">
                        <p class="mb-0 text-secondary">Bank</p>
                        <select class="form-control form-select" name="bank_id" id="bank_id" required>
                            <option value="">Select Bank</option>
                            <option value="All">All Banks</option>
                            <?php
                            if (!empty($bankList)) {
                                foreach ($bankList as $selected_array) {
                                    if ($selected_array['is_active'] != 1) {
                                        continue;
                                    }
                            ?>
                                    ?>
                                    <option <?= ($selected_array['bank_id'] == $bankId) ? 'selected' : '' ?> value="<?= $selected_array['bank_id'] ?>"><?= $selected_array['name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0 text-secondary">Card Type</p>
                        <select class="form-control form-select" name="card_type" id="card_type" required>
                            <option value="All">All Cards</option>
                            <option value="Visa">Visa Card</option>
                            <option value="Master">Master Card</option>
                            <option value="Amex">Amex Card</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0 text-secondary">Loyalty Type</p>
                        <select class="form-control form-select" name="card_type" id="card_type" required>
                            <option value="All">All</option>
                            <option value="Silver">Silver</option>
                            <option value="Gold">Gold</option>
                            <option value="Platinum">Platinum</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-12">
                        <p class="mb-0 text-secondary">Offer Description</p>
                        <textarea required cols="30" rows="3" class="form-control" type="text" name="offerDescription" id="offerDescription" placeholder="Enter Offer Description"><?= $offerDescription ?></textarea>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-dark btn-sm rounded-2" type="button"><i class="fa-regular fa-floppy-disk"></i> Save Changes</button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>