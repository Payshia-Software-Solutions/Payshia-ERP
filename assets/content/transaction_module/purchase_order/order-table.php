<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);

$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];
$TempOrder =  GetTempPO($link, $LoggedUser);
?>
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Item/Service</th>
        <th class="text-center" scope="col">Unit</th>
        <th class="text-center" scope="col">Stock</th>
        <th class="text-center" scope="col">Quantity</th>
        <th class="text-end" scope="col">Per Unit Rate</th>
        <th class="text-end" scope="col">Amount</th>
        <th class="text-center" scope="col">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if (!empty($TempOrder)) {
        $rawNumber = 0;
        foreach ($TempOrder as $selectedArray) {
            $OrderDate = $selectedArray['order_rate'];
            $OrderQuantity = $selectedArray['quantity'];
            $PerRate = $selectedArray['order_rate'];
            $OrderUnit = $selectedArray['order_unit'];
            $ProductID = $selectedArray['product_id'];
            $productName = $Products[$ProductID]['product_name'];

            $currentStock = 0;
            $lineTotal = $PerRate * $OrderQuantity;
            $rawNumber++
    ?>
            <tr>
                <td><?= $rawNumber ?></td>
                <th><?= $productName ?></th>
                <td class="text-center"><?= $OrderUnit ?></td>
                <td class="text-center"><?= number_format($currentStock, 2) ?></td>
                <th class="text-center"><?= number_format($OrderQuantity, 2) ?></th>
                <td class="text-end"><?= number_format($PerRate, 2) ?></td>
                <th class="text-end"><?= number_format($lineTotal, 2) ?></th>
                <td class="text-end">
                    <button class="mt-0 btn btn-sm btn-danger view-button" type="button" onclick="RemoveFromOrder('<?= $selectedArray['id'] ?>')"><i class="fa-solid fa-trash"></i> Remove</button>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="8" class="text-center">No Entires</td>
        </tr>
    <?php
    }
    ?>
</tbody>