<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
$ActiveStatus = 0;
$Locations = GetLocations($link);
$customers = GetCustomers($link);
$ArrayCount = count($customers);

$ActiveCount = $ArrayCount;
$InactiveCount = 0;
$LoggedUser = $_POST['LoggedUser'];
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-person-military-pointing icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Customers</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <?php
    $pageID = 2;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-9 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="NewCustomer(1,0)"><i class="fa-solid fa-plus"></i> New Customer</button>
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
        <div class="table-title font-weight-bold mb-4 mt-0">Customers List</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <table class="table table-striped table-hover" id="purchase-order-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Credit Limit</th>
                                    <th scope="col">Due Balance</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($customers)) {
                                    $RowNumber = 0;
                                    foreach ($customers as $selectedArray) {
                                        $active_status = "Disabled";
                                        $color = "secondary";
                                        if ($selectedArray['is_active'] == 1) {
                                            $active_status = "Active";
                                            $color = "primary";
                                        }
                                        $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                        $customer_id = $selectedArray['customer_id'];
                                        $creditLimit = $selectedArray['credit_limit'];
                                        $customerName =  GetCustomerName($link, $customer_id);
                                        $dueBalance = 0;
                                        $RowNumber++;
                                ?>
                                        <tr>
                                            <th><?= $customer_id ?></th>
                                            <td><?= $LocationName ?></td>
                                            <td><?= $customerName ?></td>
                                            <td class="text-end"><?= number_format($creditLimit, 2) ?></td>
                                            <th class="text-end"><?= number_format($dueBalance, 2) ?></th>
                                            <td class="text-end"><span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span></td>
                                            <td class="text-end">
                                                <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="NewCustomer(1, '<?= $customer_id ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>
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