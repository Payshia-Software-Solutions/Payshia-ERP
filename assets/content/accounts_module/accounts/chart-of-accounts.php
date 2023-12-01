<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';

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
    <div class="col-md-7">
        <div class="table-title font-weight-bold mb-4 mt-0">Chart of Accounts</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Account #</th>
                                        <th scope="col">Account Name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($ChartOfAccounts)) {
                                        $RowNumber = 0;
                                        foreach ($ChartOfAccounts as $selectedArray) {

                                            $account_id = $selectedArray['account_id'];
                                            $account_name = $selectedArray['account_name'];
                                            $account_type = $selectedArray['account_type'];

                                            $accountBalance = getAccountBalance($account_id);
                                            if ($accountBalance <= 0) {
                                                continue;
                                            }
                                            $account_balance = formatAccountBalance($accountBalance);
                                    ?>
                                            <tr>
                                                <th><?= $account_id ?></th>
                                                <td><?= $account_name ?></td>
                                                <td><?= $account_type ?></td>
                                                <th class="text-end"><?= $account_balance ?></th>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-success view-button" type="button" onclick=""><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <?php
                                    if (!empty($ChartOfAccounts)) {
                                        $RowNumber = 0;
                                        foreach ($ChartOfAccounts as $selectedArray) {

                                            $account_id = $selectedArray['account_id'];
                                            $account_name = $selectedArray['account_name'];
                                            $account_type = $selectedArray['account_type'];

                                            $accountBalance = getAccountBalance($account_id);
                                            if ($accountBalance >= 0) {
                                                continue;
                                            }
                                            $account_balance = formatAccountBalance($accountBalance);
                                    ?>
                                            <tr>
                                                <th><?= $account_id ?></th>
                                                <td><?= $account_name ?></td>
                                                <td><?= $account_type ?></td>
                                                <th class="text-end"><?= $account_balance ?></th>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-success view-button" type="button" onclick=""><i class="fa-solid fa-eye"></i> Open</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>


                                    <?php
                                    if (!empty($ChartOfAccounts)) {
                                        $RowNumber = 0;
                                        foreach ($ChartOfAccounts as $selectedArray) {

                                            $account_id = $selectedArray['account_id'];
                                            $account_name = $selectedArray['account_name'];
                                            $account_type = $selectedArray['account_type'];

                                            $accountBalance = getAccountBalance($account_id);
                                            if ($accountBalance != 0) {
                                                continue;
                                            }
                                            $account_balance = formatAccountBalance($accountBalance);
                                    ?>
                                            <tr>
                                                <th><?= $account_id ?></th>
                                                <td><?= $account_name ?></td>
                                                <td><?= $account_type ?></td>
                                                <th class="text-end"><?= $account_balance ?></th>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-success view-button" type="button" onclick=""><i class="fa-solid fa-eye"></i> Open</button>
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
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            ordering: false,
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