<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$Products = GetProducts($link);
?>

<div class="row mt-2">

    <?php
    if (!empty($Products)) {
        foreach ($Products as $Product) {
            $product_name = $Product['product_name'];
            $display_name = $Product['display_name'];
            $print_name = $Product['print_name'];
            $selling_price = $Product['selling_price'];

            if ($Product['active_status'] != 1) {
                continue;
            }

            if ($Product['image_path'] == 'no-image.png') {
                $file_path = "../assets/images/products/no-image.png";
            } else {
                $file_path = "./assets/images/products/" . $Product['product_id'] . "/" . $Product['image_path'];
            }

            $CurrentStockBalance = 100;

    ?>

            <div class="col-6 col-md-4 col-lg-3 mb-3 d-flex">
                <div class="card product-card shadow-sm flex-fill" onclick="OpenQtySelector('<?= $Product['product_id'] ?>', '<?= $Product['selling_price'] ?>' , '<?= 0 ?>', '<?= $CurrentStockBalance ?>')">
                    <div class="card-body">
                        <div class="card-image" style="background-image:url('<?= $file_path ?>')"></div>
                        <h4 class="card-title my-0 text-center"><?= $display_name ?></h4>
                        <h4 class="card-price text-center">LKR <?= $selling_price ?></h4>
                    </div>
                </div>
            </div>
    <?php
        }
    } ?>
</div>