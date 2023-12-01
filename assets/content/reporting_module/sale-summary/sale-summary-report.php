<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;

$Locations = GetLocations($link);

$fromDate = $_POST['from-date-input'];
$toDate = $_POST['to-date-input'];
$location_id = $_POST['location_id'];
$invoiceSales = getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];

// var_dump($invoiceSales);
?>

<div class="table-responsive">
    <h4 class="text-end">Total</h4>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">Sub Total</th>
                <th scope="col">Discount</th>
                <th scope="col">Tax</th>
                <th scope="col">Grand Total</th>
            </tr>
        </thead>
        <?php
        if (!empty($invoiceSales)) {
            foreach ($invoiceSales as $selectedArray) {
                $invoice_date = date("Y-m-d", strtotime($selectedArray['invoice_date']));
                $subTotal += $selectedArray['inv_amount'];
                $discountAmount += $selectedArray['discount_amount'];
                $serviceCharge += $selectedArray['service_charge'];
                $grandTotal += $selectedArray['grand_total'];
            }
        }
        ?>

        <thead>
            <tr>
                <th scope="col" class="text-end"><?= formatAccountBalance($subTotal) ?></th>
                <th scope="col" class="text-end"><?= formatAccountBalance($discountAmount) ?></th>
                <th scope="col" class="text-end"><?= formatAccountBalance($serviceCharge) ?></th>
                <th scope="col" class="text-end"><?= formatAccountBalance($grandTotal) ?></th>
            </tr>
        </thead>
    </table>

    <h4 class="text-end">Detail Report</h4>
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Invoice #</th>
                <th scope="col">Sub Total</th>
                <th scope="col">Discount</th>
                <th scope="col">Tax</th>
                <th scope="col">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($invoiceSales)) {
                foreach ($invoiceSales as $selectedArray) {
                    $invoice_date = date("Y-m-d", strtotime($selectedArray['invoice_date']));
            ?>
                    <tr>
                        <td><?= $invoice_date ?></td>
                        <td><?= $selectedArray['invoice_number'] ?></td>
                        <td class="text-end"><?= formatAccountBalance($selectedArray['inv_amount']) ?></td>
                        <td class="text-end"><?= formatAccountBalance($selectedArray['discount_amount']) ?></td>
                        <td class="text-end"><?= formatAccountBalance($selectedArray['service_charge']) ?></td>
                        <th class="text-end"><?= formatAccountBalance($selectedArray['grand_total']) ?></td>

                    </tr>

            <?php
                }
            }
            ?>
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