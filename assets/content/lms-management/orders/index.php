<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$studentBatch = $_POST['studentBatch'];
$orderType = $_POST['orderType'];

$accountDetails = GetAccounts($link);

$ActiveStatus = 0;
$Locations = GetLocations($link);
$Deliveries = GetDeliverySetting();
$CourseBatches = getLmsBatches();
$deliveryOrders = GetOrders();
$ArrayCount = count($deliveryOrders);

$LoggedUser = $_POST['LoggedUser'];
$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Orders</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Pending</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Delivered</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3 text-end">
        <button type="button" onclick="GetUploadExcel()" class="btn btn-dark btn-sm">
            Download
        </button>
        <button type="button" onclick="GetUploadExcelNew()" class="btn btn-dark btn-sm">
            Download New
        </button>
    </div>

</div>


<div class="row mt-5">
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Deliverable Orders</div>
        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 offset-4">
                                <p class="text-secondary mb-0">Batch</p>
                                <select class="form-control" name="studentBatch" id="studentBatch" onchange="OpenIndex(this.value, document.getElementById('orderType').value)">
                                    <option value="0">All</option>
                                    <?php
                                    if (!empty($CourseBatches)) {
                                        foreach ($CourseBatches as $selectedArray) {

                                    ?>
                                            <option <?= ($studentBatch ==  $selectedArray['course_code']) ? 'selected' : '' ?> value="<?= $selectedArray['course_code'] ?>"><?= $selectedArray['course_code'] ?> - <?= $selectedArray['course_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <p class="text-secondary mb-0">Order Type</p>
                                <select class="form-control" name="orderType" id="orderType" onchange="OpenIndex(document.getElementById('studentBatch').value, this.value)">
                                    <option value="0">All</option>
                                    <?php
                                    if (!empty($Deliveries)) {
                                        foreach ($Deliveries as $selectedArray) {
                                    ?>
                                            <option <?= ($orderType ==  $selectedArray['id']) ? 'selected' : '' ?> value=" <?= $selectedArray['id'] ?>"><?= $selectedArray['delivery_title'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped table-hover table-fixed" id="deliverable-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">REF</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Student</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Order date</th>
                                        <th scope="col">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($deliveryOrders)) {
                                        $RowNumber = 0;
                                        foreach ($deliveryOrders as $selectedArray) {

                                            $active_status = "Initial";
                                            $color = "warning";
                                            $ref_id = $selectedArray['id'];
                                            $trackingNumber = $selectedArray['tracking_number'];
                                            $indexNumber = $selectedArray['index_number'];
                                            $orderDate = date("Y-m-d H:i", strtotime($selectedArray['order_date']));
                                            $packed_date = date("Y-m-d H:i", strtotime($selectedArray['packed_date']));
                                            $send_date = date("Y-m-d H:i", strtotime($selectedArray['send_date']));
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
                                            $delivery_id = $selectedArray['delivery_id'];

                                            $deliveryItem = $Deliveries[$delivery_id]['delivery_title'];

                                            if ($studentBatch != $course_code && $studentBatch != '0') {
                                                continue;
                                            }

                                            if ($orderType != $delivery_id && $orderType != '0') {
                                                continue;
                                            }


                                            if ($current_status == 1) {
                                                $active_status = "Processing";
                                                $color = "danger";
                                            } else if ($current_status == 2) {
                                                $active_status = "Packed";
                                                $color = "success";
                                            } else if ($current_status == 3) {
                                                continue;
                                                $active_status = "Delivered";
                                                $color = "dark";
                                            } else if ($current_status == 4) {
                                                continue;
                                                $active_status = "Removed";
                                                $color = "danger";
                                            }

                                            $getStatusData = GetProductLinkStatus($delivery_id);
                                            if (count($getStatusData) == 0) {
                                                $productErpStatus = false;
                                            } else {
                                                $productErpStatus = true;
                                            }

                                            if ($trackingNumber == "") {
                                                $trackingNumber = "Not Set";
                                            }


                                    ?>
                                            <tr>
                                                <th><?= $ref_id ?></th>
                                                <td>
                                                    <span class="badge bg-<?= $color ?>"><?= $active_status ?></span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <?php
                                                        if ($productErpStatus) { ?>
                                                            <button class="btn btn-sm btn-dark view-button" type="button" onclick="OpenOrder('<?= $ref_id ?>', '<?= $studentBatch ?>', '<?= $orderType ?>')">
                                                                <i class="fa-solid fa-eye"></i> Open
                                                            </button>

                                                        <?php } else { ?>
                                                            <button class="btn btn-secondary btn-sm" type="button" onclick="ProductLinkERP('<?= $delivery_id ?>')"><i class="fa-solid fa-plus"></i> Product Link with ERP</button>
                                                        <?php  } ?>

                                                    </div>
                                                </td>
                                                <td><?= $indexNumber ?></td>
                                                <td>
                                                    <p class="mb-0"><?= $full_name ?></p>
                                                    <p class="mb-0"><?= $street_address ?></p>
                                                    <p class="mb-0"><?= $city ?>, <?= $district ?></p>
                                                    <p class="mb-0"><?= $phone_1 ?>, <?= $phone_2 ?></p>
                                                </td>
                                                <td><?= $orderDate ?></td>
                                                <th><?= $deliveryItem ?></th>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-title font-weight-bold mb-4 mt-0">Delivered Orders</div>
        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive  text-nowrap">
                            <table class="table table-striped table-hover table-fixed" id="delivered-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tracking</th>
                                        <th scope="col">Student Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($deliveryOrders)) {
                                        $RowNumber = 0;
                                        foreach ($deliveryOrders as $selectedArray) {
                                            $active_status = "Initial";
                                            $color = "warning";
                                            $ref_id = $selectedArray['id'];
                                            $trackingNumber = $selectedArray['tracking_number'];
                                            $indexNumber = $selectedArray['index_number'];
                                            $orderDate = date("Y-m-d H:i", strtotime($selectedArray['order_date']));
                                            $packed_date = date("Y-m-d H:i", strtotime($selectedArray['packed_date']));
                                            $send_date = date("Y-m-d H:i", strtotime($selectedArray['send_date']));
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


                                            if ($studentBatch != $course_code && $studentBatch != '0') {
                                                continue;
                                            }

                                            if ($orderType != $delivery_id && $orderType != '0') {
                                                continue;
                                            }


                                            if ($current_status == 3) {
                                                $active_status = "Delivered";
                                                $color = "success";
                                            } else {
                                                continue;
                                            }

                                    ?>
                                            <tr>
                                                <th>
                                                    <?= $trackingNumber ?>
                                                    <p class="m-0"><?= $orderDate ?></p>
                                                    <p class="m-0"><span class="badge bg-<?= $color ?>"><?= $active_status ?></span></p>
                                                    <div class="text-start mt-1">
                                                        <button class="btn btn-sm btn-dark view-button" type="button" onclick="OpenOrder('<?= $ref_id ?>', '<?= $studentBatch ?>', '<?= $orderType ?>')">
                                                            <i class="fa-solid fa-eye"></i> Open
                                                        </button>
                                                    </div>
                                                </th>
                                                <td><?= $indexNumber ?>
                                                    <div>
                                                        <p class="mb-0"><?= $full_name ?></p>
                                                        <p class="mb-0"><?= $street_address ?></p>
                                                        <p class="mb-0"><?= $city ?>, <?= $district ?></p>
                                                        <p class="mb-0"><?= $phone_1 ?>, <?= $phone_2 ?></p>
                                                    </div>
                                                </td>

                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#deliverable-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [1, 'desc'],
                [0, 'asc']
            ]
        });

        $('#delivered-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [0, 'desc']
            ]
        });
    });
</script>