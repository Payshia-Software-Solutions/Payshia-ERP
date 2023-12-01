<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/reporting-functions.php';

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;

$Locations = GetLocations($link);
$Products = GetProducts($link);

$fromDate = $_POST['from-date-input'];
$toDate = $_POST['to-date-input'];
$location_id = $_POST['location_id'];


$itemWiseSale = GetItemWiseSale($link, $fromDate, $toDate, $location_id);
// var_dump($itemWiseSale);

$invoiceSales = getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);

?>
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
<div class="table-responsive">

    <table class="table table-striped table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Item Price</th>
                <th scope="col">Item Discounts</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if (!empty($itemWiseSale)) {
                foreach ($itemWiseSale as $selectedArray) {
                    $product_name = $Products[$selectedArray['product_id']]['product_name'];

                    $quantity = $selectedArray['total_quantity'];
                    $costPrice = $selectedArray['cost_price'];
                    $itemPrice = $selectedArray['item_price'];
                    $itemDiscount = $selectedArray['total_discounts'];
                    $totalValue = $quantity * $itemPrice;
            ?>
                    <tr>
                        <td class=""><?= $selectedArray['product_id'] ?></td>
                        <td class=""><?= $product_name ?></td>
                        <td class="" style="max-width: 200px;"><?= $quantity ?></td>
                        <td class="text-end"><?= number_format($itemPrice, 3) ?></td>
                        <td class="text-end"><?= number_format($itemDiscount, 3) ?></td>
                        <th class="text-end"><?= number_format($totalValue, 3) ?></th>
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
            // searching: false, // Disable search input   
        });
    });
</script>