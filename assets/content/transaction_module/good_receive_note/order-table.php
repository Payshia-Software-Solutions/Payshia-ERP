<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];
$po_number = $_POST['po_number'];
$updateStatus = $_POST['updateStatus'];

$PurchaseOrders = GetPurchaseOrders($link)[$po_number];
$PurchaseOrder = GetPurchaseOrderItems($link, $po_number);

// var_dump($AddResult);
$TempOrder =  GetTempGRNItems($link, $LoggedUser);


$location_id = $PurchaseOrders['location_id'];
$taxType = "Non-VAT";
$taxRate = 0.08;
$subTotal = $GrandTotal = $taxAmount = 0;
?>
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Item/Service</th>
        <th class="text-center" scope="col">Unit</th>
        <th class="text-center" scope="col">Stock</th>
        <th class="text-center" scope="col">Receivable</th>
        <th class="text-center" scope="col">Received Quantity</th>
        <th class="text-center" scope="col">Mf. Date</th>
        <th class="text-center" scope="col">Exp. Date</th>
        <th class="text-end" scope="col">Unit Rate</th>
        <th class="text-end" scope="col">Amount</th>
        <th class="text-center" scope="col">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if (!empty($TempOrder)) {
        $rawNumber = 0;
        $subTotal = $lineTotal = 0;
        foreach ($TempOrder as $selectedArray) {
            $OrderDate = $selectedArray['order_rate'];
            $OrderQuantity = $selectedArray['quantity'];
            $PerRate = $selectedArray['order_rate'];
            $OrderUnit = $selectedArray['order_unit'];
            $ProductID = $selectedArray['product_id'];
            $productName = $Products[$ProductID]['product_name'];
            $received_qty = $selectedArray['received_qty'];

            $expiryGoodStatus = $Products[$ProductID]['expiry_good'];

            $currentStock = GetStockBalanceByProductByLocation($link, $ProductID, $location_id);
            $rawNumber++;

            $pendingQty = $OrderQuantity;
            $lineTotal = $PerRate * $received_qty;
            $subTotal += $lineTotal;


    ?>
            <tr>
                <td><?= $rawNumber ?></td>
                <th><?= $productName ?></th>
                <td class="text-center"><?= $OrderUnit ?></td>
                <td class="text-center"><?= number_format($currentStock, 3) ?></td>
                <th class="text-center"><?= number_format($pendingQty, 3) ?></th>
                <th class="text-center">
                    <input type="number" oninput="validateInput(this)" step="0.001" max="<?= $pendingQty ?>" class="form-control text-center" onchange="UpdateGRNQty('<?= $po_number ?>', this.value, '<?= $ProductID ?>')" onclick="this.select()" value="<?= $received_qty ?>">
                </th>
                <td class="text-end">
                    <?php if ($expiryGoodStatus == 1) { ?>
                        <input name="manufactureDate" id="manufactureDate" type="date" class="" value="<?= date('Y-m-d') ?>">
                    <?php } ?>
                </td>
                <td class="text-end">
                    <?php if ($expiryGoodStatus == 1) { ?>
                        <input name="expireDate" id="expireDate" type="date" class="form-control" value="<?= date('Y-m-d') ?>">
                    <?php } ?>
                </td>
                <td class="text-end"><?= number_format($PerRate, 2) ?></td>
                <th class=" text-end"><?= number_format($lineTotal, 2) ?></th>
                <td class="text-center"><i class="fa-solid fa-trash clickable text-danger" onclick=" RemoveFromOrder('<?= $ProductID ?>', '<?= $po_number ?>') "></i>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="11" class="text-center">No Entires</td>
        </tr>
    <?php
    }
    ?>
</tbody>
<?php
if ($taxType != "Non-VAT") {
    $taxAmount = $subTotal * $taxRate;
    $GrandTotal = $subTotal + $taxAmount;
} else {
    $taxAmount = 0;
    $GrandTotal = $subTotal + $taxAmount;
}
?>
<tfoot>
    <input type="hidden" name="itemCount" id="itemCount" value="<?= $rawNumber ?>">
    <input type="hidden" name="subTotal" id="subTotal" value="<?= $subTotal ?>">
    <input type="hidden" name="taxAmount" id="taxAmount" value="<?= $taxAmount ?>">
    <input type="hidden" name="grandTotal" id="grandTotal" value="<?= $GrandTotal ?>">
    <tr>
        <td colspan="9" class="text-end border-0">Sub Total</td>
        <td colspan="2" class="text-end border-0"><?= number_format($subTotal, 2) ?></td>
    </tr>

    <tr>
        <td colspan="9" class="text-end border-0">Tax</td>
        <td colspan="2" class="text-end border-0"><?= number_format($taxAmount, 2) ?></td>
    </tr>

    <tr>
        <th colspan="9" class="text-end border-1">Grand Total</th>
        <th colspan="2" class="text-end border-1">
            <h3><?= number_format($subTotal, 2) ?></h3>
        </th>
    </tr>
</tfoot>