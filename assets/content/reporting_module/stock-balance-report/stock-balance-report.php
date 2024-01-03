<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = 0;

$Locations = GetLocations($link);

$Products = GetProducts($link);
$location_id = $_POST['location_id'];

$dateInput = $_POST['date-input'];
$section_id = $_POST['section_id'];
$department_id = $_POST['department_id'];
$category_id = $_POST['category_id'];
?>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Balance</th>
                <th scope="col">Avg. Cost</th>
                <th scope="col">Selling Price</th>
                <th scope="col">Cost Value</th>
                <th scope="col">Sell Value</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($Products)) {
                $totalStockValue = $totalSellingValue = 0;
                foreach ($Products as $selectedArray) {
                    $product_id = $selectedArray['product_id'];
                    $stockBalance = GetStockBalanceByProductByLocationToDate($link, $product_id, $location_id, $dateInput);
                    $costPrice = GetCostPrice($link, $product_id);
                    $sellingPrice = GetSellingPrice($link, $product_id);
                    $stockValue = $stockBalance * $costPrice;
                    $stockSellingValue = $sellingPrice * $stockBalance;
                    $totalStockValue += $stockValue;
                    $totalSellingValue += $stockSellingValue;

                    if ($section_id != $selectedArray['section_id'] && $section_id != 'All') {
                        continue;
                    }
                    if ($department_id != $selectedArray['department_id'] && $department_id != 'All') {
                        continue;
                    }
                    if ($category_id != $selectedArray['category_id'] && $category_id != 'All') {
                        continue;
                    }
            ?>
                    <tr>
                        <td class="">#00<?= $selectedArray['product_id'] ?></td>
                        <td class=""><?= $selectedArray['product_name'] ?></td>
                        <td class="text-end"><?= number_format($stockBalance, 3) ?></td>
                        <td class="text-end"><?= formatAccountBalance($costPrice) ?></td>
                        <td class="text-end"><?= formatAccountBalance($sellingPrice) ?></td>
                        <td class="text-end"><?= formatAccountBalance($stockValue) ?></td>
                        <td class="text-end"><?= formatAccountBalance($stockSellingValue) ?></td>
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