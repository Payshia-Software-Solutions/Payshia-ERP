<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';


$Locations = GetLocations($link);
$Products = GetProducts($link);

$fromDate = $_POST['from-date-input'];
$toDate = $_POST['to-date-input'];
$location_id = $_POST['location_id'];
$select_product = $_POST['select_product'];
$binCard = stockBinCard($link, $fromDate, $toDate, $location_id, $select_product);
$product_name = $Products[$select_product]['product_name'];

$forwardBalances = getCumulativeBinCardTotals($link, $fromDate, $select_product, $location_id);
$bfDebitBalance = $bfCreditBalance = 0;
if (!empty($forwardBalances)) {
    $bfDebitBalance = $forwardBalances['DEBIT']['cumulative_total'];
    $bfCreditBalance = $forwardBalances['CREDIT']['cumulative_total'];
}
// var_dump($forwardBalances);

$balanceForward = $bfDebitBalance - $bfCreditBalance;
?>
<p><?= $product_name ?></p>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th scope="col" colspan="2">Description</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <th scope="col">Balance</th>
        </tr>
        <tr>
            <td class="" colspan="2">B/F</td>
            <td class="text-end"><?= number_format($bfDebitBalance, 3) ?></td>
            <td class="text-end"><?= number_format($bfCreditBalance, 3) ?></td>
            <td class="text-end"><?= number_format($balanceForward, 3) ?></td>
        </tr>
    </table>
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Description</th>
                <th scope="col">Debit</th>
                <th scope="col">Credit</th>
                <th scope="col">Balance</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if (!empty($binCard)) {
                $totalStockValue = $totalSellingValue = 0;
                foreach ($binCard as $selectedArray) {
                    $debitQuantity = ($selectedArray['type'] == 'DEBIT') ? $selectedArray['quantity'] : 0;
                    $creditQuantity = ($selectedArray['type'] == 'CREDIT') ? $selectedArray['quantity'] : 0;
                    $balanceForward = $balanceForward + ($debitQuantity - $creditQuantity);

                    $date_time = date("Y-m-d", strtotime($selectedArray['created_at']));
            ?>
                    <tr>
                        <td class=""><?= $date_time ?></td>
                        <td class="" style="max-width: 200px;"><?= $selectedArray['reference'] ?></td>
                        <td class="text-end"><?= number_format($debitQuantity, 3) ?></td>
                        <td class="text-end"><?= number_format($creditQuantity, 3) ?></td>
                        <td class="text-end"><?= number_format($balanceForward, 3) ?></td>
                    </tr>

            <?php
                    $debitQuantity = $creditQuantity = 0;
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
            // searching: false, // Disable search input   
        });
    });
</script>