<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';

$LoggedUser = $_POST['LoggedUser'];
$ChartOfAccounts = ChartOfAccounts();
$ArrayCount = count($ChartOfAccounts);
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-chart-bar icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Accounts</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-money-bill icon-card"></i>
            </div>
            <div class="card-body">
                <p>Cash</p>
                <h1 class="<?= (getAccountBalance($cashAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($cashAccountId)) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-hand-holding-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Accounts Receivable</p>
                <h1 class="<?= (getAccountBalance($accountsReceivableAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($accountsReceivableAccountId)) ?></h1>
            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-money-bill-trend-up icon-card"></i>
            </div>
            <div class="card-body">
                <p>Accounts Payable</p>
                <h1 class="<?= (getAccountBalance($accountsPayableAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($accountsPayableAccountId)) ?></h1>
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
    <div class="col-md-4">
        <div class="table-title font-weight-bold mb-4 mt-0">Reports</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>

                                    <tr>
                                        <th scope="col">Report</th>
                                        <th scope="col">Type</th>
                                        <th scope="col" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $pageID = 22;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>
                                            <tr>
                                                <td>Day End Sale Report</td>
                                                <td>Sale</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="DayEndSaleReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    $pageID = 23;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>

                                            <tr>
                                                <td>Sale Summary Report</td>
                                                <td>Sale</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="SaleSummaryReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <?php
                                    $pageID = 24;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>
                                            <tr>
                                                <td>Receipt Report</td>
                                                <td>Sale</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="ReceiptReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <?php
                                    $pageID = 25;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>
                                            <tr>
                                                <td>Item Wise Sale Report</td>
                                                <td>Sale</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="ItemWiseSale()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    $pageID = 26;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>
                                            <tr>
                                                <td>Stock Balance Report</td>
                                                <td>Stock</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="StockBalanceReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <?php
                                    $pageID = 27;
                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                    if (!empty($userPrivilege)) {
                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                        if ($readAccess == 1) {
                                    ?>
                                            <tr>
                                                <td>Bin Card Report</td>
                                                <td>Stock</td>
                                                <td class="text-end">
                                                    <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="BinCardReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <tr>
                                        <td>Charge Report</td>
                                        <td>Sale</td>
                                        <td class="text-end">
                                            <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="ChargeReport()"><i class="fa-solid fa-eye"></i> Open</button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Report Index</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body" id="report-index">
                        <p class="mb-0">Open a Report</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>


<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            ordering: false,
            lengthChange: false,
            info: false, // Disable information display
            // searching: false, // Disable search input
        });
    });
</script>