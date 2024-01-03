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
$invoiceSales = getChargeInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];

$userAccounts = GetAccounts($link);
// var_dump($invoiceSales);
?>

<div class="table-responsive">


    <h4 class="text-end">Detail Report</h4>
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Steward ID</th>
                <th scope="col">Steward Name</th>
                <th scope="col">Total Invoice</th>
                <th scope="col">Bill Count</th>
                <th scope="col">Charge Amount</th>
                <th scope="col">Reserved(20%)</th>
                <th scope="col">Receivable Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($invoiceSales)) {
                foreach ($invoiceSales as $selectedArray) {

                    $stewardId = ($selectedArray['steward_id'] != '0') ? $selectedArray['steward_id'] : 'Direct';

                    if ($stewardId != "Direct") {
                        $LoggedStudent = $userAccounts[$selectedArray['steward_id']];
                        $stewardName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
                    } else {
                        $stewardName = "Direct";
                    }

                    $chargeAmount = $selectedArray['chargeAmount'];
                    $reservedPotion = $chargeAmount * 0.2;
                    $receivableAmount = $chargeAmount - $reservedPotion;
            ?>
                    <tr>
                        <td><?= $stewardId ?></td>
                        <td><?= $stewardName ?></td>
                        <td class="text-end"><?= formatAccountBalance($selectedArray['TotalInvoice']) ?></td>
                        <td class="text-end"><?= $selectedArray['BillCount'] ?></td>
                        <td class="text-end"><?= formatAccountBalance($chargeAmount) ?></td>
                        <td class="text-end"><?= formatAccountBalance($reservedPotion) ?></td>
                        <td class="text-end"><?= formatAccountBalance($receivableAmount) ?></td>
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