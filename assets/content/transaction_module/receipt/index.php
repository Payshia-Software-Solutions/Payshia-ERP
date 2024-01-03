<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$ActiveStatus = 0;
$ActiveCount = $ArrayCount = 0;
$InactiveCount = 0;

$LoggedUser = $_POST['LoggedUser'];
$default_location = $_POST['default_location'];

$Invoices = GetInvoices($link);
$Locations = GetLocations($link);
$receipts = GetReceipts($link);

$ArrayCount = count($receipts);

$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Receipts</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>
    <?php
    $pageID = 28;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        // if ($writeAccess == 1) {
        if ($writeAccess == -1) {
    ?>
            <div class="col-md-9 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="NewProductionNote()"><i class="fa-solid fa-plus"></i> New Receipt</button>
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
        <div class="table-title font-weight-bold mb-4 mt-0">Pending Invoices</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Invoice #</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($Invoices)) {
                                        $RowNumber = 0;
                                        foreach ($Invoices as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";

                                            if ($selectedArray['invoice_status'] != 2) {
                                                continue;
                                            }

                                            if ($selectedArray['is_active'] == 1) {
                                                $active_status = "Active";
                                                $color = "primary";
                                            }
                                            $locationId = $selectedArray['location_id'];
                                            $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                            $invoice_date = $selectedArray['invoice_date'];

                                            $invoice_number = $selectedArray['invoice_number'];
                                            $paymentValue = GetReceiptsValueByInvoice($link, $invoice_number);

                                            $CustomerID = $selectedArray['customer_code'];
                                            $invoiceValue = $selectedArray['grand_total'];

                                            $balanceAmount = $invoiceValue - $paymentValue;

                                            if ($paymentValue >= $invoiceValue) {
                                                continue;
                                            }

                                            $customerName =  GetCustomerName($link, $CustomerID);
                                            $RowNumber++;
                                    ?>
                                            <tr>
                                                <th><?= $invoice_number ?></th>
                                                <td><?= $LocationName ?></td>
                                                <td><?= $customerName ?></td>
                                                <td class="text-end"><?= number_format($invoiceValue, 2) ?></td>
                                                <th class="text-end"><?= number_format($balanceAmount, 2) ?></th>
                                                <td class="text-end">

                                                    <button class="mt-0 btn btn-sm btn-success view-button" type="button" onclick="CreateReceipt('<?= $invoice_number ?>', 1, '<?= $balanceAmount ?>', '<?= $CustomerID ?>',  '<?= $locationId ?>') "><i class="fa-solid fa-receipt" style="padding-right:5px"></i> Payment</button>

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

    <div class="col-md-4">
        <div class="row">

            <div class="col-12">
                <div class="table-title font-weight-bold mb-4 mt-0">Recent Receipts</div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="receipt-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">REC #</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($receipts)) {
                                        $totalRecValue = 0;
                                        foreach ($receipts as $selectedArray) {
                                            $rec_date = date("Y-m-d", strtotime($selectedArray['date']));

                                            $ref_id = $selectedArray['ref_id'];
                                            $rec_number = $selectedArray['rec_number'];
                                            $customer_id = $selectedArray['customer_id'];
                                            $rec_amount = $selectedArray['amount'];
                                            $recType = $selectedArray['type'];

                                            $totalRecValue += $rec_amount;
                                            $customerName = GetCustomerName($link, $customer_id);

                                            if ($recType == 0) {
                                                $color = "primary";
                                            } else {
                                                $color = "info";
                                            }
                                    ?>
                                            <tr>
                                                <td>
                                                    <?= $rec_date ?>
                                                    <p class="mb-0">
                                                        <span class="badge bg-<?= $color ?>"><?= $PaymentTypes[$recType]['text'] ?></span>
                                                    </p>
                                                </td>
                                                <td>
                                                    <b><?= $rec_number ?></b>
                                                    <p class="mb-0"><?= $customerName ?></p>
                                                </td>
                                                <td class="text-end"><?= formatAccountBalance($rec_amount) ?></td>
                                                <td><button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="PrintPN('<?= $rec_number ?>')"><i class="fa-solid fa-print"></i> Print</button></td>
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
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });

        $('#receipt-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [1, 'desc']
            ]
        });

    });
</script>