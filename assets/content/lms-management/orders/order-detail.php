<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$ref_id = $_POST['ref_id'];

$studentBatch = $_POST['studentBatch'];
$orderType = $_POST['orderType'];

$selectedArray = GetOrders()[$ref_id];
$Deliveries = GetDeliverySetting();

$delivery_id = $selectedArray['delivery_id'];
$trackingNumber = $selectedArray['tracking_number'];
$indexNumber = $selectedArray['index_number'];
$orderDate = ($selectedArray['order_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['order_date'])) : 'Not Set';
$packed_date = ($selectedArray['packed_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['packed_date'])) : 'Not Set';
$send_date = ($selectedArray['send_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['send_date'])) : 'Not Set';
$current_status = $selectedArray['current_status'];
$delivery_partner = $selectedArray['delivery_partner'];
$course_code = $selectedArray['course_code'];
$estimate_delivery = $selectedArray['estimate_delivery'];
$full_name = $selectedArray['full_name'];
$street_address = $selectedArray['street_address'];
$city = $selectedArray['city'];
$district = $selectedArray['district'];
$phone_1 = $selectedArray['phone_1'];
$phone_2 = $selectedArray['phone_2'];
$is_active = $selectedArray['is_active'];
$received_date = $selectedArray['received_date'];
$package_weight =  $selectedArray['package_weight'];
$cod_amount =  $selectedArray['cod_amount'];

$deliveryItemValue = $Deliveries[$delivery_id]['value'];
$active_status = "Initial";
$color = "warning";
$readOnlyStatus = '';
if ($current_status == 1) {
    $active_status = "Processing";
    $color = "danger";
} else if ($current_status == 2) {
    $active_status = "Packed";
    $color = "success";
    $deliveryItemValue = $cod_amount;
} else if ($current_status == 3) {
    $active_status = "Delivered";
    $color = "dark";
    $readOnlyStatus = 'readonly = "readonly"';
    $deliveryItemValue = $cod_amount;
} else if ($current_status == 4) {
    $active_status = "Removed";
    $color = "danger";
    $readOnlyStatus = 'readonly = "readonly"';
    $deliveryItemValue = $cod_amount;
}


if ($trackingNumber == "") {
    $trackingNumber = 'DEF' . count(GetOrders()) + 1;
}

if ($package_weight == "") {
    $package_weight = 1;
}


$qrText = "https://pharmacollege.lk/track-order?trackingNumber=" . $trackingNumber;
$deliveryItem = $Deliveries[$delivery_id]['delivery_title'];

$courseDetails = getLmsBatchByCourse($course_code);
?>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">Order Details</h5>
            <span class="badge bg-<?= $color ?>"><?= $active_status ?></span>
            <p class="border-bottom pb-2"></p>

            <div class="row mt-3">
                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Index Number</p>
                    <h5 class="mb-0"><?= $indexNumber ?></h5>
                </div>

                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Order Date</p>
                    <h5 class="mb-0"><?= $orderDate ?></h5>
                </div>

                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Packed Date</p>
                    <h5 class="mb-0"><?= $packed_date ?></h5>
                </div>

                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Delivered Date</p>
                    <h5 class="mb-0"><?= $send_date ?></h5>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Student Balance</p>
                    <h5 class="mb-0"><?= number_format(GetStudentBalance($indexNumber)['studentBalance'], 2) ?></h5>
                    <p class="text-secondary mb-0">(<span class="text-secondary mb-0">Registration Fees</span> <?= number_format(GetStudentBalance($indexNumber)['TotalRegistrationFee'], 2) ?> )</p>
                </div>

                <div class="col-6 col-md-5">
                    <p class="mb-0 text-secondary">Course</p>
                    <h5 class="mb-0"><?= $courseDetails['course_name'] ?></h5>
                    <p class="text-secondary mb-0"><?= $course_code ?></p>
                </div>
            </div>



            <div class="row mt-4">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <p class="mb-0 text-secondary">Ref Number</p>
                            <h4 class="mb-0"><?= $ref_id ?></h4>
                            <?php
                            if ($trackingNumber != "") {
                            ?>

                                <button onclick="PrintShippingLabel('<?= $ref_id ?>')" class="btn btn-light mt-2" type="button"><i class="fa-solid fa-print"></i> Print Delivery Label</button>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="col-12 col-md-4">
                            <p class="mb-0 text-secondary">Order Item</p>
                            <h4 class="mb-0"><?= $deliveryItem ?></h4>
                            <button class="btn btn-danger view-button mt-2" type="button" onclick="UpdateStatusOrder('<?= $ref_id ?>', 4, '<?= $studentBatch ?>', '<?= $orderType ?>')">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </div>

                        <div class="col-12 col-md-4">
                            <h6 class="mb-0">Delivery Address</h6>
                            <p class="mb-0"><?= $full_name ?></p>
                            <p class="mb-0"><?= $street_address ?></p>
                            <p class="mb-0"><?= $city ?>, <?= $district ?></p>
                            <p class="mb-0"><?= $phone_1 ?>, <?= $phone_2 ?></p>
                        </div>
                    </div>


                </div>
                <div class="col-md-2 text-end">
                    <?php if ($trackingNumber != "") {
                    ?>
                        <p class="mb-0 text-secondary">QR Code</p>
                    <?php
                    }
                    ?>

                </div>


                <div class="col-12">
                    <div class="border-bottom my-2"></div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-8">
                    <div class="row mt-3">
                        <div class="col-6 col-md-4">
                            <p class="mb-0 text-secondary">Tracking Number</p>
                            <input <?= $readOnlyStatus ?> type="text" class="form-control" name="trackingNumber" id="trackingNumber" value="<?= $trackingNumber ?>" placeholder="Enter Courier Tracking Number">
                        </div>

                        <div class="col-6 col-md-4">
                            <p class="mb-0 text-secondary">COD Amount</p>
                            <input <?= $readOnlyStatus ?> type="text" class="form-control text-center" onclick="this.select()" name="codAmount" id="codAmount" value="<?= $deliveryItemValue ?>" placeholder="Enter Courier Tracking Number">
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="mb-0 text-secondary">Package Weight(KG)</p>
                            <input <?= $readOnlyStatus ?> type="text" class="form-control text-center" onclick="this.select()" name="packageWeight" id="packageWeight" value="<?= $package_weight ?>" placeholder="Enter Courier Tracking Number">
                        </div>

                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row mt-3">
                        <div class="col-12 col-md-12">
                            <?php
                            if ($current_status == 1) {
                            ?>
                                <p class="mb-0 text-secondary">Status Update</p>
                                <button onclick="UpdateStatusOrder('<?= $ref_id ?>', 2, '<?= $studentBatch ?>', '<?= $orderType ?>')" class="btn btn-success w-100 form-control" type="button"><i class="fa-solid fa-gift"></i> Mark as Packed</button>
                            <?php
                            }
                            ?>

                            <?php
                            if ($current_status == 2) {
                            ?>

                                <p class="mb-0 text-secondary">Status Update</p>
                                <button onclick="UpdateStatusOrder('<?= $ref_id ?>', 3, '<?= $studentBatch ?>', '<?= $orderType ?>')" class="btn btn-primary w-100 form-control" type="button"><i class="fa-solid fa-truck"></i> Mark as Delivered</button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>
</div>