<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';


$Products = GetProducts($link);
$FilterKey = $_POST['FilterKey'];
$FilterType = $_POST['FilterType'];
$LocationID = $_POST['LocationID'];
$IconMode = GetSetting($link, $LocationID, 'itemImage');
$brandFilter = GetSetting($link, $LocationID, 'brandFilter');
?>

<div class="row " id="product-container">

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

            if ($Product['item_type'] == "Raw") {
                continue;
            }

            if ($FilterType != 'not-set') {
                if ($FilterType == 'section_id') {
                    if ($FilterKey != $section_id) {
                        continue;
                    }
                } else if ($FilterType == 'department_id') {
                    if ($FilterKey != $department_id) {
                        continue;
                    }
                } elseif ($FilterType == 'category_id') {
                    if ($FilterKey != $category_id) {
                        continue;
                    }
                }
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

    ?>

            <div class="col-4 col-md-4 col-lg-4 col-xl-4 col-xxl-3 mb-2 d-flex p-0 px-1 product-column" data-department="<?= $department_id ?>" data-category="<?= $category_id ?>" data-section="<?= $section_id ?>">
                <div class="card product-card shadow-sm flex-fill" onclick="OpenQtySelector('<?= $Product['product_id'] ?>', '<?= $Product['selling_price'] ?>' , '<?= 0 ?>', '<?= $CurrentStockBalance ?>')">
                    <div class="card-body">
                        <?php
                        if ($IconMode == 1) { ?>
                            <div class="card-image" style="background-image:url('<?= $file_path ?>')"></div>
                        <?php } ?>

                        <h4 class="card-title my-0 text-center item-name"><?= $display_name ?></h4>
                        <h4 class="card-price text-center bold-text-600">LKR <?= $selling_price ?></h4>
                    </div>
                </div>
            </div>
        <?php
        }
    }

    if ($ItemCount == 0) { ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">No Items</p>
                </div>
            </div>
        </div>
    <?php }


    ?>
</div>