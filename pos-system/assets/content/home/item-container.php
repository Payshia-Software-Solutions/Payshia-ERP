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
$barcodeDisplay = GetSetting($link, $LocationID, 'barcodeDisplay');
?>
<div class="row">
    <div class="col-12">
        <div class="mt-1">
            <input type="text" class="form-control mb-2 p-2 border-2" oninput="ChangeFilterProducts(this.value)" placeholder="Search Product" id="search-key-2" onclick="this.select()">
        </div>
    </div>
</div>

<div class="row g-2" id="product-container">

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

            // if ($Product['item_location'] != $LocationID) {
            //     continue;
            // }

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

            <div class="col-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3 d-flex product-column-2" data-department="<?= $department_id ?>" data-category="<?= $category_id ?>" data-section="<?= $section_id ?>">
                <div class="card product-card rounded-4 shadow-sm flex-fill" onclick="OpenQtySelector('<?= $Product['product_id'] ?>', '<?= $Product['selling_price'] ?>' , '<?= 0 ?>', '<?= $CurrentStockBalance ?>')">
                    <div class="card-body p-1 p-md-2">
                        <?php
                        if ($IconMode == 1) { ?>
                            <div class="card-image  rounded-4" style="background-image:url('<?= $file_path ?>')"></div>
                        <?php } ?>

                        <h4 class="card-title my-0 text-center item-name">
                            <?= $display_name ?>
                            <p id="productBarcode" class="d-none"><?= $barcode ?></p>
                            <p class="d-none"><?= $selling_price ?></p>
                        </h4>
                        <?php
                        if ($barcodeDisplay == 1 && $barcode != "") {
                            $imgCodeBarcode = GenerateNormalBarcode($barcode);
                        ?>
                            <div class="text-center">
                                <img class="logo-image p-0 text-center" src="data:image/png;base64,<?= $imgCodeBarcode; ?>" alt="Barcode" style="height:20px; width:50%;">
                                <p class="m-0"><?= $barcode ?></p>
                            </div>
                        <?php
                        }
                        ?>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the input element by its ID
        var inputBox = document.getElementById("search-key-2");
        var programmaticFocus = true; // Flag to track programmatic focus

        // Set up a focus event listener
        inputBox.addEventListener("focus", function() {
            if (programmaticFocus) {
                this.select(); // Select the text when the input box is focused programmatically
            }
        });

        // Set focus on the input box
        inputBox.focus();

        // Set the flag to false when the input box is clicked
        inputBox.addEventListener("click", function() {
            programmaticFocus = false;
        });
    });
</script>

<script>
    function ChangeFilterProducts(searchText) {
        searchText = searchText.toLowerCase();
        const productColumns = document.querySelectorAll(".product-column-2");
        let visibleProductCount = 0;
        let lastVisibleProduct = null;

        productColumns.forEach(function(productColumn) {
            const productName = productColumn.querySelector(".card-title").textContent.toLowerCase();
            const productBarcodeElement = productColumn.querySelector("#productBarcode");
            const barcode = productBarcodeElement.textContent.trim();

            if (productName.includes(searchText)) {
                productColumn.classList.remove("d-none");
                productColumn.classList.add("d-block");
                if (barcode == searchText) {
                    visibleProductCount++;
                    lastVisibleProduct = productColumn; // Update the last visible product
                }
            } else {
                productColumn.classList.remove("d-block");
                productColumn.classList.add("d-none");
            }
        });

        if (visibleProductCount === 1) {
            // Run the onclick function for the last visible product
            const loadingPopup = document.getElementById("loading-popup");
            if (lastVisibleProduct && loadingPopup.style.display !== "flex") {
                lastVisibleProduct.querySelector('.card').click(); // Adjust this line based on your actual onclick function
            }
        }

        if (visibleProductCount == 0) {
            showNotification("No Matching Results")
        }
    }
</script>