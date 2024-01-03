<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';


$refCode = $_POST['refCode'];
$Deliveries = GetDeliverySetting();
$Products = GetProducts($link);
$deliveryItem = $Deliveries[$refCode]['delivery_title'];
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0 pb-2 border-bottom">Product Link Details with ERP System</h5>
            <div class="row mt-3">
                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Addon Product</p>
                    <h5 class="mb-0"><?= $deliveryItem ?></h5>
                </div>

                <div class="col-6 col-md-6">
                    <p class="mb-0 text-secondary">ERP Linked Product</p>
                    <select class="form-control" name="select_product" id="select_product" required autocomplete="off" onchange="GetProductInfo(this.value)">
                        <option value="">Select Product</option>
                        <?php
                        if (!empty($Products)) {
                            foreach ($Products as $SelectedArray) {
                                if ($SelectedArray['active_status'] != 1) {
                                    continue;
                                }

                                if ($SelectedArray['recipe_type'] == "1") {
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
                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">Action</p><button onclick="SaveERPLink('<?= $refCode ?>', 1)" class="btn btn-success w-100 form-control" type="button"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                </div>
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