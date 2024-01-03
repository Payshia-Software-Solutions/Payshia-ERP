<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);

$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];
$TempOrder =  GetTempPO($link, $LoggedUser);
$totalOrderValue = 0;
if (!empty($TempOrder)) {
    foreach ($TempOrder as $item) {
        // Convert quantity and order rate to float for accurate calculations
        $quantity = floatval($item['quantity']);
        $orderRate = floatval($item['order_rate']);

        // Calculate the order value for each item
        $orderValue = $quantity * $orderRate;

        // Add the order value to the total
        $totalOrderValue += $orderValue;
    }
}


$defaultTaxRatio = 0.08;
$TaxValue = $totalOrderValue * $defaultTaxRatio;
$TotalPayment = $totalOrderValue + $TaxValue;
?>

<div class="row mb-3 mt-3">
    <div class="col-4 col-md-2 offset-md-8 mt-3 mt-md-0">
        <label class="form-label text-md-end">Sub Total</label>
    </div>
    <div class="col-8 col-md-2 mt-3 mt-md-0">
        <input type="hidden" name="order_value" id="order_value" class="form-control text-end" value="<?= $totalOrderValue ?>" readonly>
        <h4 class="text-end"><?= number_format($totalOrderValue, 2) ?></h4>
    </div>
</div>

<input type="number" name="no_of_items" id="no_of_items" value="<?= count($TempOrder) ?>">