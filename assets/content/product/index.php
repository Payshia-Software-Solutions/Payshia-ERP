<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$Products = GetProducts($link);
$ProductsCount = count($Products);
$LoggedUser = $_POST['LoggedUser'];
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
                <p>Active</p>
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
                <p>Disabled</p>
                <h1><?= $InactiveProductsCount ?></h1>
            </div>
        </div>
    </div>
    <?php
    $pageID = 1;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-3 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="AddProduct(1,0)"><i class="fa-solid fa-plus"></i> Add New Products/Service</button>
            </div>
    <?php
        }
    }
    ?>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="table-title font-weight-bold mb-4 mt-0">Products</div>
    </div>
    <div class="col-12 col-md-6 offset-md-6 col-lg-4 offset-lg-8 mb-2">
        <input type="text" class="form-control mb-2 p-2 border-2" placeholder="Search Product" id="search-key" onclick="this.select()">
    </div>
    <?php
    if (!empty($Products)) {
        foreach ($Products as $Product) {
            $product_name = $Product['product_name'];
            $display_name = $Product['display_name'];
            $print_name = $Product['print_name'];
            $active_status = "Disabled";
            $color = "secondary";
            if ($Product['active_status'] == 1) {
                $active_status = "Active";
                $color = "primary";
            }

            if ($Product['image_path'] == 'no-image.png') {
                $file_path = "./assets/images/products/no-image.png";
            } else {
                $file_path = "./pos-system/assets/images/products/" . $Product['product_id'] . "/" . $Product['image_path'];
            }

    ?>
            <div class="col-6 col-md-3 col-lg-2 mb-3 d-flex product-column">
                <div class="card flex-fill">
                    <div class="card-body p-2 pb-2">
                        <div class="card-back-image" style="background-image: url('<?= $file_path ?>');"></div>
                        <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                        <span class="badge mt-2 bg-secondary"><?= MakeFormatProductCode($Product['product_id']) ?></span>
                        <h1 class="tutor-name mt-2"><?= $product_name ?></h1>
                        <?php
                        $pageID = 1;
                        $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                        if (!empty($userPrivilege)) {
                            $readAccess = $userPrivilege[$LoggedUser]['read'];
                            $writeAccess = $userPrivilege[$LoggedUser]['write'];
                            $AllAccess = $userPrivilege[$LoggedUser]['all'];

                            if ($writeAccess == 1) {
                        ?>
                                <div class="text-end mt-3">
                                    <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="AddProduct (1, '<?= $Product['product_id'] ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>

                                    <?php
                                    if ($Product['active_status'] == 1) {
                                        $active_status = "Active";
                                        $color = "primary";
                                    ?>
                                        <button class="mt-0 mb-1 btn btn-sm btn-secondary view-button" type="button" onclick="ChangeStatus(0, '<?= $Product['product_id'] ?>')"><i class="fa-solid fa-ban"></i> Disable</button>
                                    <?php
                                    } else {
                                    ?>
                                        <button class="mt-0 mb-1 btn btn-sm btn-primary view-button" type="button" onclick="ChangeStatus(1, '<?= $Product['product_id'] ?>')"><i class="fa-solid fa-check"></i> Active</button>
                                    <?php
                                    }
                                    ?>
                                </div>
                        <?php
                            }
                        }
                        ?>
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

<script>
    document.getElementById("search-key").addEventListener("input", function() {
        const searchText = this.value.toLowerCase();
        const productColumns = document.querySelectorAll(".product-column");

        productColumns.forEach(function(productColumn) {
            const productName = productColumn.querySelector(".tutor-name").textContent.toLowerCase();

            if (productName.includes(searchText)) {
                productColumn.classList.remove("d-none");
                productColumn.classList.add("d-block");
            } else {
                productColumn.classList.remove("d-block");
                productColumn.classList.add("d-none");
            }
        });
    });
</script>