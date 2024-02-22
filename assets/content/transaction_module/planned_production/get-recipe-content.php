<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$productID = $_POST['productID'];
$targetQty = $_POST['targetQty'];

if ($productID != "" && $productID > 0 && $targetQty > 0) {

    $Units = GetUnit($link);
    $AllProducts = GetProducts($link);
    $Product = GetProducts($link)[$productID];
    $ProductRecipe = GetItemRecipe($link, $productID);
?>
    <h5 class="pb-2 mt-3 mb-2">Enter Materials Quantity Details</h5>
    <table class="material-table table table-bordered table-hover">
        <thead>
            <th>#</th>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Amount</th>

        </thead>
        <tbody>
            <?php
            $rowCount = 1;
            $totalAmount = 0;
            if (!empty($ProductRecipe)) {
                foreach ($ProductRecipe as $SelectedArray) {
                    $product_id = $SelectedArray['recipe_product'];
                    $product_name = $AllProducts[$product_id]['product_name'];
                    $quantity = $SelectedArray['qty'] * $targetQty;
                    $unit = $Units[$AllProducts[$product_id]['measurement']]['unit_name'];
                    $cost_price = $AllProducts[$product_id]['cost_price'];
                    $amount = $quantity * $cost_price;
                    $totalAmount += $amount;
                    $rowCount++;
            ?>
                    <tr>
                        <td><?= $rowCount ?></td>
                        <td><?= $product_id ?></td>
                        <td><?= $product_name ?></td>
                        <td><input type="number" value="<?= $quantity ?>" class="form-control text-end" name="productQty" id="productQty"></td>
                        <td><?= $unit ?></td>
                        <td class="text-end"><?= number_format($cost_price, 2) ?>/<?= $unit ?></td>
                        <td class="text-end"><?= number_format($amount, 2) ?></td>
                    </tr>

            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="6">Total</th>
                <th class="text-end" id="ProductionTotalCost"><?= number_format($totalAmount, 2) ?></th>
            </tr>
        </tfoot>
    </table>
<?php
} else {
?>
    <div class="alert alert-warning mt-3">
        <h2 class="mb-0">Invalid!</h2>
        <h5 class="mb-0 bg-light p-2 rounded-4">Please select Product and Quantity!</h5>
    </div>

<?php
}
?>