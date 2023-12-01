<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$productID = $_POST['productID'];
$recipeType = $_POST['recipeType'];

if ($recipeType == 0) {
    $recipeDisplay = "None";
    $color = "success";
} else if ($recipeType == 1) {
    $recipeDisplay = "A La Carte";
    $color = "primary";
} else if ($recipeType == 2) {
    $recipeDisplay = "Item Recipe";
    $color = "danger";
}


$Units = GetUnit($link);
$AllProducts = GetProducts($link);
$Product = GetProducts($link)[$productID];

$ProductRecipe = GetItemRecipe($link, $productID);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0 text-secondary">Product</p>
                        <h5 class="mb-0"><?= $Product['product_name'] ?></h5>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0 text-secondary">Recipe Type</p>
                        <h5><?= $recipeDisplay ?></h5>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="card mt-2">
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Select Product</label>
                <select class="form-control" name="select_product" id="select_product" required autocomplete="off" onchange="GetProductInfo(this.value)">
                    <option value="">Select Product</option>
                    <?php
                    if (!empty($AllProducts)) {
                        foreach ($AllProducts as $SelectedArray) {
                            if ($SelectedArray['active_status'] == 1 && ($SelectedArray['item_type'] == "Raw" || $SelectedArray['item_type'] == "RawnSell")) {
                            } else {
                                continue;
                            }
                    ?>
                            <option value="<?= $SelectedArray['product_id'] ?>"><?= $SelectedArray['product_name'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" onchange="validateInput(this)" step="0.001" min='0' class="form-control text-end" name="new_quantity" onclick="this.select()" id="new_quantity" placeholder="0.0">
            </div>
            <div class="col-md-2 col-md-2">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control text-center" name="order_Unit" id="order_Unit" readonly placeholder="Nos">
            </div>

            <input type="hidden" step="0.001" min='0' class="form-control text-end" name="new_rate" id="new_rate" onclick="this.select()" placeholder="0.0">

            <div class="col-md-2">
                <label class="form-label">Action</label>
                <button type="button" onclick="AddToRecipe()" class="btn btn-dark w-100" style="height: 44px;"><i class="fa-solid fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="card sh mt-2">
    <div class="card-body">

        <h4 class="mb-0">Ingredients</h4>
        <div class="table-responsive">
            <table class="table table-hover mt-0" id="recipe-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th class='text-center'>Qty</th>
                        <th class='text-center'>Unit</th>
                        <th class='text-end'>Amount</th>
                        <th class='text-center'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($ProductRecipe)) {
                        foreach ($ProductRecipe as $SelectedArray) {
                            $product_id = $SelectedArray['recipe_product'];
                            $product_name = $AllProducts[$product_id]['product_name'];
                            $quantity = $SelectedArray['qty'];
                            $unit = $Units[$AllProducts[$product_id]['measurement']]['unit_name'];
                            $cost_price = $AllProducts[$product_id]['cost_price'];
                            $amount = number_format($quantity * $cost_price, 2);
                    ?>
                            <tr>
                                <td><?= $product_id ?></td>
                                <td><?= $product_name ?></td>
                                <td class='text-center'><?= $quantity ?></td>
                                <td class='text-center'><?= $unit ?></td>
                                <td class='text-end'><?= $amount ?></td>
                                <td class='text-center'><i onclick='RemoveRow(this)' class='fa-solid fa-trash text-danger clickable'></i></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-12 text-end">
                <button class="btn btn-sm btn-dark" onclick="SaveRecipe('<?= $productID ?>', '<?= $recipeType ?>')">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#select_product').select2({
            width: 'resolve'
        });

    });
</script>