<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$Products = GetProducts($link);
$ProductsCount = count($Products);

$ActiveProductsCount = $ProductsCount;
$InactiveProductsCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-brands fa-product-hunt icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Products</p>
                <h1><?= $ProductsCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-dna icon-card"></i>
            </div>
            <div class="card-body">
                <p>Active Products</p>
                <h1><?= $ActiveProductsCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-star-of-life icon-card"></i>
            </div>
            <div class="card-body">
                <p>In-Active Products</p>
                <h1><?= $InactiveProductsCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3 text-end mt-4 mt-md-0">
        <button class="btn btn-dark" type="button" onclick="AddProduct(1,0)"><i class="fa-solid fa-plus"></i> Add New Products</button>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="table-title font-weight-bold mb-4 mt-0">Products</div>
    </div>
    <?php
    if (!empty($Products)) {
        foreach ($Products as $Product) {
            $product_name = $Product['product_name'];
            $display_name = $Product['display_name'];
            $print_name = $Product['print_name'];
            $active_status = "Deleted";
            $color = "warning";
            if ($Product['active_status'] == 1) {
                $active_status = "Active";
                $color = "primary";
            }

    ?>
            <div class="col-6 col-md-3 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-2 pb-2">
                        <div class="card-back-image" style="background-image: url('./assets/images/products/<?= $Product['image_path'] ?>');"></div>
                        <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                        <h1 class="tutor-name mt-2"><?= $product_name ?></h1>
                        <div class="text-end mt-3">
                            <button class="mt-0 mb-1 btn btn-sm btn-success view-button" type="button" onclick="OpenDestinationGallery('<?= $Product['product_id'] ?>')"><i class="fa-solid fa-images"></i> Gallery</button>
                            <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="AddNewDestination (1, '<?= $Product['product_id'] ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>
                            <button class="mt-0 mb-1 btn btn-sm btn-danger view-button" type="button" onclick="ChangeDestinationStatus(0, '<?= $Product['product_id'] ?>')"><i class="fa-solid fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">No Entires</p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>