<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/settings_functions.php';

$LocationID = $_POST['location_id'];
$Products = GetProducts($link);

$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');
?>

<div class="row g-2">
    <div class="col-md-4">
        <label class="form-label">Select Product</label>
        <select class="form-control" name="select_product" id="select_product" required autocomplete="off" onchange="GetProductInfo(this.value)">
            <option value="">Select Product</option>
            <?php
            $ItemCount = 0;
            if (!empty($Products)) {
                foreach ($Products as $Product) {
                    $product_name = $Product['product_name'];
                    $display_name = $Product['display_name'];
                    $print_name = $Product['print_name'];
                    $selling_price = $Product['selling_price'];

                    $section_id = $Product['section_id'];
                    $department_id = $Product['department_id'];
                    $category_id = $Product['category_id'];
                    $brand_id = $Product['brand_id'];
                    $generic_id = $Product['generic_id'];
                    $size_id = $Product['size_id'];
                    $color_id = $Product['color_id'];
                    $barcode = $Product['barcode'];

                    if ($Product['item_type'] == "Raw") {
                        continue;
                    }
                    $ItemCount++;
                    if ($Product['active_status'] != 1) {
                        continue;
                    }
                    if ($Product['image_path'] == 'no-image.png') {
                        $file_path = "../assets/images/products/no-image.png";
                    } else {
                        $file_path = "./assets/images/products/" . $Product['product_id'] . "/" . $Product['image_path'];
                    }
                    $CurrentStockBalance = 100;
                    $location_list = $Product['location_list'];
                    $locationArray = explode(",", $location_list);
                    $isChecked = in_array($LocationID, $locationArray) ? true : false;

                    if ($Product['item_location'] == $LocationID) {
                        $isChecked = true;
                    }
                    if (!$isChecked) {
                        continue;
                    }
            ?>
                    <option value="<?= $Product['product_id'] ?>"><?= $Product['product_name'] ?> - <?= $Product['selling_price'] ?> - <?= $Product['product_code'] ?></option>
            <?php
                }
            }
            ?>


        </select>
    </div>
    <div class="col-6 col-md-1">
        <input type="hidden" step="0.01" min='0' class="form-control text-end" readonly name="stockBalance" id="stockBalance" placeholder="0.0">
        <label class="form-label">Unit</label>
        <input type="text" style="height: 44px;" class="form-control text-center" name="order_Unit" id="order_Unit" readonly placeholder="Nos">
    </div>

    <div class="col-6 col-md-3">
        <label class="form-label">Rate</label>
        <input type="number" style="height: 44px;" step="0.01" min='0' class="form-control text-end" name="new_rate" id="new_rate" onclick="this.select()" placeholder="0.0">
    </div>


    <div class="col-12 col-md-2">
        <label class="form-label">Quantity</label>
        <input type="number" style="height: 44px;" oninput="validateInput(this)" step="0.001" min='0' class="form-control text-end" name="new_quantity" onclick="this.select()" id="new_quantity" placeholder="0.0">
    </div>


    <div class="col-md-2">
        <label class="form-label">Action</label>
        <button type="button" onclick="AddToTable()" class="btn btn-dark w-100" style="height: 44px;"><i class="fa-solid fa-plus"></i></button>
    </div>
</div>

<div class="table-responsive mt-3" id="return-items">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total</th>
                <th class='text-end' id="totalAmount">0.00</th>
                <th class='text-end'></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="row">
    <div class="col-12 text-end">
        <button onclick="SaveReturn('<?= $receiptPrinterStatus ?>', '<?= $receiptPrintMethod ?>')" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Save Return</button>
        <!-- <button class="btn btn-warning"><i class="fa-solid fa-money-bill-trend-up"></i> Return & Refund</button> -->
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#select_product').select2({
            width: 'resolve'
        });
        $('#customer_select').select2({
            width: 'resolve'
        });

        $('#select_invoice').select2({
            width: 'resolve'
        });

    });
</script>