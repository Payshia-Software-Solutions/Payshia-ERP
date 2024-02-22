<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = $totalSettled =  0;

$Locations = GetLocations($link);
$receipts = GetReceipts($link);

$fromDate = $_POST['from-date-input'];
$toDate = $_POST['to-date-input'];
$location_id = $_POST['location_id'];
$invoiceSales = getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];

// var_dump($invoiceSales);
?>

<div class="table-responsive">

    <h4 class="text-end">Detail Report</h4>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="report-table">
            <thead>
                <tr>
                    <th scope="col">Invoice #</th>
                    <th scope="col">Location</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Settled</th>
                    <th scope="col">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($invoiceSales)) {
                    $RowNumber = 0;
                    foreach ($invoiceSales as $selectedArray) {
                        $active_status = "Deleted";
                        $color = "warning";

                        if ($selectedArray['invoice_status'] != 2) {
                            continue;
                        }

                        if ($selectedArray['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        } else {
                            continue;
                        }

                        $locationId = $selectedArray['location_id'];
                        $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                        $invoice_date = $selectedArray['invoice_date'];

                        $invoice_number = $selectedArray['invoice_number'];
                        $paymentValue = GetReceiptsValueByInvoice($link, $invoice_number);

                        $returnSettlement =  GetInvoiceSettlement($selectedArray['invoice_number']);

                        $CustomerID = $selectedArray['customer_code'];
                        $invoiceValue = $selectedArray['grand_total'];

                        $settlementAmount = $paymentValue + $returnSettlement;
                        $balanceAmount = $invoiceValue - $settlementAmount;

                        if ($paymentValue >= $invoiceValue) {
                            continue;
                        }

                        if ($balanceAmount <= 0) {
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
                            <td class="text-end"><?= number_format($settlementAmount, 2) ?></td>
                            <th class="text-end"><?= number_format($balanceAmount, 2) ?></th>

                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>

        </table>
    </div>


</div>

<script>
    $(document).ready(function() {
        $('#report-table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            ordering: false,
            searching: false, // Disable search input   
        });
    });
</script>