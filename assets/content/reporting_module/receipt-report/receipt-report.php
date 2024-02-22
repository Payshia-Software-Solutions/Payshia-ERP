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
$receipts = GetReceiptsByLocation($link, $fromDate, $toDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];
$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"],
    ["id" => "4", "text" => "Bank Transfer"]
];
?>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Type</th>
                <th scope="col">REC #</th>
                <th scope="col">Ref</th>
                <th scope="col">Customer</th>
                <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($receipts)) {
                $totalRecValue = 0;
                foreach ($receipts as $selectedArray) {
                    $rec_date = date("Y-m-d H:i", strtotime($selectedArray['current_time']));

                    $ref_id = $selectedArray['ref_id'];
                    $rec_number = $selectedArray['rec_number'];
                    $customer_id = $selectedArray['customer_id'];
                    $rec_amount = $selectedArray['amount'];
                    $recType = $selectedArray['type'];

                    $totalRecValue += $rec_amount;
                    $customerName = GetCustomerName($link, $customer_id);
            ?>
                    <tr>
                        <td><?= $rec_date ?></td>
                        <td><?= $PaymentTypes[$recType]['text'] ?></td>
                        <td><?= $rec_number ?></td>
                        <td><?= $ref_id ?></td>
                        <td><?= $customer_id ?> - <?= $customerName ?></td>
                        <td class="text-end"><?= formatAccountBalance($rec_amount) ?></td>

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