<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$studentBatch = $_POST['studentBatch'];
$orderType = $_POST['orderType'];

$accountDetails = GetLmsStudents($link);
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
<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">

            <div class="row">
                <div class="col-4 offset-4">
                    <p class="text-secondary mb-0">Batch</p>
                    <select class="form-control" name="studentBatch" id="studentBatch" onchange="GetUploadExcel(this.value, document.getElementById('orderType').value)">
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
                    <select class="form-control" name="orderType" id="orderType" onchange="GetUploadExcel(document.getElementById('studentBatch').value, this.value)">
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
                <table class="table table-striped table-hover table-fixed" id="excel-order-table">
                    <thead>
                        <tr>
                            <th scope="col">waybill_number</th>
                            <th scope="col">order_no</th>
                            <th scope="col">customer_name</th>
                            <th scope="col">customer_phone</th>
                            <th scope="col">customer_secondary_phone</th>
                            <th scope="col">customer_address</th>
                            <th scope="col">customer_email</th>
                            <th scope="col">cod</th>
                            <th scope="col">destination_city</th>
                            <th scope="col">weight</th>
                            <th scope="col">description</th>
                            <th scope="col">remark</th>
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
                                $received_date = $selectedArray['received_date'];
                                $package_weight =  $selectedArray['package_weight'];
                                $cod_amount =  $selectedArray['cod_amount'];
                                $userEmail = $accountDetails[$indexNumber]['e_mail'];

                                $description = $remark = "";
                                $deliveryItem = $Deliveries[$delivery_id]['delivery_title'];

                                if ($studentBatch != $course_code && $studentBatch != '0') {
                                    continue;
                                }

                                if ($orderType != $delivery_id && $orderType != '0') {
                                    continue;
                                }


                                if ($current_status == 1) {
                                    continue;
                                    $active_status = "Processing";
                                    $color = "danger";
                                } else if ($current_status == 2) {
                                    $active_status = "Packed";
                                    $color = "success";
                                } else if ($current_status == 3) {
                                    $active_status = "Delivered";
                                    $color = "dark";
                                }

                                $getStatusData = GetProductLinkStatus($delivery_id);
                                if (count($getStatusData) == 0) {
                                    $productErpStatus = false;
                                } else {
                                    $productErpStatus = true;
                                }

                                if ($trackingNumber == "") {
                                    continue;
                                }


                        ?>
                                <tr>
                                    <td><?= $trackingNumber ?></td>
                                    <td><?= $ref_id ?></td>
                                    <td><?= $full_name ?></td>
                                    <td><?= $phone_1 ?></td>
                                    <td><?= $phone_2 ?></td>
                                    <td><?= $street_address ?>, <?= $city ?>, <?= $district ?></td>
                                    <td><?= $userEmail ?></td>
                                    <td><?= $cod_amount ?></td>
                                    <td><?= $city ?></td>
                                    <td><?= $package_weight ?></td>
                                    <td><?= $description ?></td>
                                    <td><?= $remark ?></td>
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



<script>
    $(document).ready(function() {
        $('#excel-order-table').DataTable({
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