<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = 0;

$Locations = GetLocations($link);

$date = $_POST['date-input'];
$location_id = $_POST['location_id'];
$invoiceSales = getInvoicesByDate($link, $date, $location_id);
$receipts =  getReceiptsByDate($link, $date, $location_id);


if (isset($receipts[0])) {
    $cashReceipts = $receipts[0];
}

if (isset($receipts[1])) {
    $creditCardReceipts = $receipts[1];
}

$creditSales = $invoiceSales - $cashReceipts - $creditCardReceipts;
$cashInHand = $cashReceipts - $refundAmount;
$location_name = $Locations[$location_id]['location_name'];


?>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Location</th>
                <th scope="col">Total Sale</th>
                <th scope="col">Cash</th>
                <th scope="col">Credit Card</th>
                <th scope="col">Credit</th>
                <th scope="col">Refund</th>
                <th scope="col">Cash In Hand</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $date ?></td>
                <td><?= $location_name ?></td>
                <td class="text-end"><?= formatAccountBalance($invoiceSales) ?></td>
                <td class="text-end"><?= formatAccountBalance($cashReceipts) ?></td>
                <td class="text-end"><?= formatAccountBalance($creditCardReceipts) ?></td>
                <td class="text-end"><?= formatAccountBalance($creditSales) ?></td>
                <td class="text-end"><?= formatAccountBalance($refundAmount) ?></td>
                <td class="text-end"><?= formatAccountBalance($cashInHand) ?></td>

            </tr>


        </tbody>
    </table>
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