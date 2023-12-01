<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Units = GetUnit($link);
$selectedSupplierId = $_POST['SupplierID'];

$products = GetRawProducts($link);
$filteredProducts = array_filter($products, function ($product) use ($selectedSupplierId) {
    $supplierList = explode(',', $product['supplier_list']);
    return in_array($selectedSupplierId, $supplierList);
});
?>

<div class="row">
    <div class="col-md-4">
        <label class="form-label">Select Product</label>
        <select class="form-control" name="select_product" id="select_product" required autocomplete="off" onchange="GetProductInfo(this.value)">
            <option value="">Select Product</option>
            <?php
            if (!empty($filteredProducts)) {
                foreach ($filteredProducts as $SelectedArray) {
                    if ($SelectedArray['active_status'] != 1) {
                        continue;
                    }
            ?>
                    <option value="<?= $SelectedArray['product_id'] ?>"><?= $SelectedArray['product_name'] ?> - <?= $SelectedArray['cost_price'] ?> - <?= $SelectedArray['product_code'] ?></option>
            <?php
                }
            }
            ?>
        </select>
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label">Quantity</label>
        <input type="number" oninput="validateInput(this)" step="0.001" min='0' class="form-control text-end" name="new_quantity" onclick="this.select()" id="new_quantity" placeholder="0.0">
    </div>
    <div class="col-6 col-md-2">
        <label class="form-label">Unit</label>
        <input type="text" class="form-control text-center" name="order_Unit" id="order_Unit" readonly placeholder="Nos">
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label">Rate</label>
        <input type="number" step="0.01" min='0' class="form-control text-end" name="new_rate" id="new_rate" onclick="this.select()" placeholder="0.0">
    </div>

    <div class="col-md-2">
        <label class="form-label">Action</label>
        <button type="button" onclick="AddToPO()" class="btn btn-dark w-100" style="height: 44px;"><i class="fa-solid fa-plus"></i></button>
    </div>


</div>
<script>
    $(document).ready(function() {
        $('#select_product').select2({
            width: 'resolve'
        });

        $('#location_id').select2({
            width: 'resolve'
        });

        $('#supplier_id').select2({
            width: 'resolve'
        });
    });
</script>