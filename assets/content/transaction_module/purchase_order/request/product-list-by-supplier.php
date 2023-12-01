<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
$selectedSupplierId = $_POST['SupplierID'];

$products = GetRawProducts($link);
$filteredProducts = array_filter($products, function ($product) use ($selectedSupplierId) {
    $supplierList = explode(',', $product['supplier_list']);
    return in_array($selectedSupplierId, $supplierList);
});
?>

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