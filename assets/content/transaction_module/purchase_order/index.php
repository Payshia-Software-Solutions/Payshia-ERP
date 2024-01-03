<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveStatus = 0;
$Locations = GetLocations($link);
$PurchaseOrders = GetPurchaseOrders($link);
$ArrayCount = count($PurchaseOrders);

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
    <?php
    $pageID = 15;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-9 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="NewPurchaseOrder()"><i class="fa-solid fa-plus"></i> New Purchase Order</button>
            </div>
    <?php
        }
    }
    ?>
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
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Purchase Orders</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <table class="table table-striped table-hover" id="purchase-order-table">
                            <thead>
                                <tr>
                                    <th scope="col">PO #</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Supplier</th>
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
                                        }
                                        $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                        $OrderDate = $selectedArray['created_at'];
                                        $POValue = $selectedArray['sub_total'];

                                        $PONUmber = $selectedArray['po_number'];

                                        $VendorID = $selectedArray['supplier_id'];
                                        $Supplier = GetSupplier($link)[$VendorID];
                                        $RowNumber++;
                                ?>
                                        <tr>
                                            <th><?= $PONUmber ?></th>
                                            <td><?= $LocationName ?></td>
                                            <td><?= $Supplier['supplier_name'] ?></td>
                                            <td><?= $OrderDate ?></td>
                                            <th class="text-end"><?= number_format($POValue, 2) ?></th>
                                            <td class="text-end"><span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span></td>
                                            <td class="text-end">
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

    <div class="col-md-4">
        <div class="row">

            <div class="col-12">
                <div class="table-title font-weight-bold mb-4 mt-0">Recent Saved</div>
            </div>

            <div class="recent-po-container">
                <?php
                if (!empty($RecentSavedPO)) {
                    foreach ($RecentSavedPO as $Location) {
                        $location_name = $Location['location_name'];
                        $active_status = "Deleted";
                        $color = "warning";
                        if ($Location['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        }

                ?>
                        <div class="col-12 mb-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body p-2 pb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 class="tutor-name my-0"><?= $location_name ?></h1>
                                            <span class="badge mt-1 bg-<?= $color ?>"><?= $active_status ?></span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-end mt-3">
                                                <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="AddNewLocation (1, '<?= $Location['location_id'] ?>')"><i class="fa-solid fa-pen-to-square"></i> Continue</button>
                                                <button class="mt-0 mb-1 btn btn-sm btn-danger view-button" type="button" onclick="ChangeStatus(0, '<?= $Location['location_id'] ?>')"><i class="fa-solid fa-trash"></i> Delete</button>
                                            </div>
                                        </div>
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
                                <p class="mb-0">No Entires</p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });
    });
</script>