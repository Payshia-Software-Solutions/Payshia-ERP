<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveStatus = 0;
$Locations = GetLocations($link);
$PurchaseOrders = GetPurchaseOrders($link);
$ArrayCount = count($PurchaseOrders);

$LoggedUser = $_POST['LoggedUser'];
$GRNList = GetGRNList($link);

$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-arrow-down icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Orders</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>
</div>
<style>
    #order-table tr {
        height: auto !important
    }

    .recent-po-container {
        max-height: 70vh;
        overflow: auto;
    }
</style>

<div class="row mt-5">
    <div class="col-md-7">
        <div class="table-title font-weight-bold mb-4 mt-0">Receivable Orders</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">PO #</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($PurchaseOrders)) {
                                        $RowNumber = 0;
                                        foreach ($PurchaseOrders as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";
                                            if ($selectedArray['is_active'] == 1) {
                                                $active_status = "Active";
                                                $color = "primary";
                                            } else {
                                                continue;
                                            }
                                            $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                            $OrderDate = $selectedArray['created_at'];
                                            $POValue = $selectedArray['sub_total'];

                                            $dateTime = new DateTime($OrderDate);
                                            $OrderDate = $dateTime->format('Y-m-d');

                                            $PONUmber = $selectedArray['po_number'];

                                            $VendorID = $selectedArray['supplier_id'];
                                            $Supplier = GetSupplier($link)[$VendorID];
                                            $RowNumber++;
                                            $poStatus = 0;
                                            $PurchaseOrderItems = GetPurchaseOrderItems($link, $PONUmber);

                                            $subTotal = 0;
                                            if (!empty($PurchaseOrderItems)) {
                                                foreach ($PurchaseOrderItems as $selectedArray) {
                                                    $OrderQuantity = $selectedArray['quantity'];
                                                    $PerRate = $selectedArray['order_rate'];
                                                    $OrderUnit = $selectedArray['order_unit'];
                                                    $ProductID = $selectedArray['product_id'];

                                                    $grn_qty = GetGRNItemCountByPO($link, $PONUmber, $ProductID);
                                                    if ($grn_qty < $OrderQuantity) {
                                                        $poStatus = 1;
                                                    }

                                                    $subTotal += (($OrderQuantity - $grn_qty) * $PerRate);
                                                }
                                            }

                                            if ($poStatus == 0) {
                                                continue;
                                            }

                                    ?>
                                            <tr>
                                                <th><?= $PONUmber ?></th>
                                                <td><?= $LocationName ?></td>
                                                <td><?= $Supplier['supplier_name'] ?></td>
                                                <td><?= $OrderDate ?></td>
                                                <th class="text-end"><?= number_format($subTotal, 2) ?></th>
                                                <td class="text-end"><span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span></td>
                                                <td class="text-end">
                                                    <?php
                                                    $pageID = 11;
                                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                                    if (!empty($userPrivilege)) {
                                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                                        if ($writeAccess == 1) {
                                                    ?>
                                                            <button class="mt-0 btn btn-sm btn-success view-button" type="button" onclick="OpenGRN ('<?= $PONUmber ?>')"><i class="fa-solid fa-cubes"></i> GRN</button>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="OpenPOPrint ('<?= $PONUmber ?>')"><i class="fa-solid fa-print"></i> Print</button>
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

    <div class="col-md-5">
        <div class="table-title font-weight-bold mb-4 mt-0">Recent GRN</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="grn-table">
                                <thead>
                                    <tr>
                                        <th scope="col">GRN #</th>
                                        <th scope="col">PO #</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($GRNList)) {
                                        $RowNumber = 0;
                                        foreach ($GRNList as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";
                                            if ($selectedArray['is_active'] == 1) {
                                                $active_status = "Active";
                                                $color = "primary";
                                            } else {
                                                continue;
                                            }
                                            $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                            $OrderDate = $selectedArray['created_at'];
                                            $POValue = $selectedArray['sub_total'];

                                            $grn_number = $selectedArray['grn_number'];
                                            $po_number = $selectedArray['po_number'];

                                            $VendorID = $selectedArray['supplier_id'];
                                            $Supplier = GetSupplier($link)[$VendorID];
                                            $RowNumber++;
                                    ?>
                                            <tr>
                                                <th><?= $grn_number ?></th>
                                                <th><?= $po_number ?></th>
                                                <td><?= $LocationName ?></td>
                                                <td><?= $Supplier['supplier_name'] ?></td>
                                                <th class="text-end"><?= number_format($POValue, 2) ?></th>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="OpenGRNPrint ('<?= $grn_number ?>')"><i class="fa-solid fa-print"></i> Print</button>
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
        $('#purchase-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });

        $('#grn-table').DataTable({
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