<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 1;
$updateKey = 0;
$ButtonText = "Save";
$location_list = "";
$product_code = $product_name = $print_name = $display_name = $productBarcode = $name_si = $name_ti = $product_section = $product_department = $product_category = $product_unit = $product_description = $ImgURL =  $supplier_list = $recipe_type = $item_type = $openingStock = "";
$ImgURL = "no-image.png";

$cost_price = $min_price = $selling_price = $wholesale_price = $price_2 = 0;
if ($_POST['updateKey'] != 0) {
    $Product = GetProducts($link)[$_POST['updateKey']];
    $updateKey = $_POST['updateKey'];

    $product_name = $Product['product_name'];
    $print_name = $Product['print_name'];
    $display_name = $Product['display_name'];
    $name_si = $Product['name_si'];
    $name_ti = $Product['name_ti'];
    $product_section = $Product['section_id'];
    $product_department = $Product['department_id'];
    $product_category = $Product['category_id'];
    $product_unit = $Product['measurement'];
    $product_description = $Product['product_description'];
    $cost_price = $Product['cost_price'];
    $min_price = $Product['minimum_price'];
    $selling_price = $Product['selling_price'];
    $wholesale_price = $Product['wholesale_price'];
    $price_2 = $Product['price_2'];
    $ImgURL = $Product['image_path'];
    $supplier_list = $Product['supplier_list'];
    $location_list = $Product['location_list'];
    $recipe_type = $Product['recipe_type'];
    $item_type = $Product['item_type'];
    $productBarcode = $Product['barcode'];
    $item_location = $Product['item_location'];
    $openingStock = $Product['opening_stock'];
}
$SupplierArray = explode(",", $supplier_list);
$locationArray = explode(",", $location_list);

if (isset($_POST['is_active'])) {
    $ActiveStatus = $_POST['is_active'];
}

if ($ActiveStatus == 0) {
    $ButtonText = "Delete";
}

$Locations = GetLocations($link);
$Cities = GetCityList($link);
$Suppliers =  GetSupplier($link);
$Units = GetUnit($link);
$Sections = GetSections($link);
$Departments = GetDepartments($link);
$Categories = GetCategories($link);
?>
<!DOCTYPE html>
<style>
    #map {
        height: 500px;
    }
</style>
<div class="row my-4">
    <div class="col-12">
        <form class="add-class-form" id="add-form" action="" method="post">

            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn-dark btn" onclick="AddProduct(1,'<?= $updateKey ?>')">
                        <i class="clickable fa-solid fa-rotate-right"></i>
                    </button>
                    <button class="btn-success btn" onclick="OpenIndex()">
                        <i class="clickable fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <h1 class="site-title">New Product</h1>
            <h4 class="mb-4 border-bottom pb-2">Product Information</h4>

            <div class="mb-3">
                <div class="row ">
                    <div class="col-md-3">
                        <label for="DestinationName" class="form-label">Product Code</label>
                        <input type="text" name="product_code" id="product_code" class="form-control" value="<?= $product_code ?>" placeholder="Product Code" readonly>
                    </div>
                    <div class="col-md-9">
                        <label for="DestinationName" class="form-label">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="<?= $product_name ?>" placeholder="Product Name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="DestinationName" class="form-label">Print Name</label>
                        <input type="text" name="print_name" id="print_name" class="form-control" value="<?= $print_name ?>" placeholder="Print Name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="DestinationName" class="form-label">Display Name</label>
                        <input type="text" name="display_name" id="display_name" class="form-control" value="<?= $display_name ?>" placeholder="Display Name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="DestinationName" class="form-label">Sinhala Name</label>
                        <input type="text" name="name_si" id="name_si" class="form-control" value="<?= $name_si ?>" placeholder="Print Name" required>
                    </div>

                    <div class="col-md-4">
                        <label for="DestinationName" class="form-label">Tamil Name</label>
                        <input type="text" name="name_ti" id="name_ti" class="form-control" value="<?= $name_ti ?>" placeholder="Display Name" required>
                    </div>

                    <div class="col-md-4">
                        <label for="DestinationName" class="form-label">Barcode</label>
                        <input type="number" name="productBarcode" id="productBarcode" class="form-control" value="<?= $productBarcode ?>" placeholder="Barcode">
                    </div>

                </div>


                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="section_id" class="form-label">Section</label>
                        <select onchange="SelectDepartments(this.value, '<?= $product_department ?>', '<?= $product_category ?>')" class="form-select" name="section_id" id="section_id" required>
                            <option value="">Select Section</option>
                            <?php
                            if (!empty($Sections)) {
                                foreach ($Sections as $selected_array) {
                                    if ($selected_array['is_active'] != 1) {
                                        continue;
                                    }
                            ?>
                                    ?>
                                    <option <?= ($selected_array['id'] == $product_section) ? 'selected' : '' ?> value="<?= $selected_array['id'] ?>"><?= $selected_array['section_name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select onchange="SelectCategory(document.getElementById('section_id').value, this.value, '<?= $product_category ?>')" class="form-select" name="department_id" id="department_id" required autocomplete="off">
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" name="category_id" id="category_id" required autocomplete="off">
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="">Opening Stock</label>
                        <input type="text" class="form-control text-end" name="openingStock" id="openingStock" value="<?= $openingStock ?>" placeholder="0.00">

                    </div>
                </div>
            </div>


            <p class="card-description border-top pt-2">Other Details</p>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="brand_id" class="form-label">Brand</label>
                    <select class="form-select" name="brand_id" id="brand_id">
                        <option value="">Select Brand</option>

                    </select>
                </div>

                <div class="col-md-3">
                    <label for="generic_id" class="form-label">Generic</label>
                    <select class="form-select" name="generic_id" id="generic_id" autocomplete="off">
                        <option value="">Select Generic Name</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="size_id" class="form-label">Size</label>
                    <select class="form-select" name="size_id" id="size_id" autocomplete="off">
                        <option value="">Select Size</option>

                    </select>
                </div>

                <div class="col-md-3">
                    <label for="color_id" class="form-label">Color</label>
                    <select class="form-select" name="color_id" id="color_id" autocomplete="off">
                        <option value="">Select Color</option>

                    </select>
                </div>
            </div>

            <div class="row mt-3 mb-4">
                <div class="col-md-4">
                    <label class="">UOM</label>
                    <select class="form-control" name="product_unit" id="product_unit" required>
                        <option value="">Select Unit</option>
                        <?php
                        if (!empty($Units)) {
                            foreach ($Units as $selected_array) {
                                if ($selected_array['is_active'] != 1) {
                                    continue;
                                }
                        ?>
                                <option <?= ($selected_array['unit_id'] == $product_unit) ? 'selected' : '' ?> value="<?= $selected_array['unit_id'] ?>"><?= $selected_array['unit_name'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="">Reorder Level (Qty)</label>
                    <input type="text" class="form-control text-end" name="reorder_level" id="reorder_level" value="0.00" placeholder="0.00">

                </div>
                <div class="col-md-4">
                    <label class="">Lead Time (Days)</label>
                    <input type="text" class="form-control text-end" name="lead_time" id="lead_time" value="0.00" placeholder="0.00">
                </div>
            </div>

            <hr>
            <p class="card-description">Pricing Details</p>
            <div class="row">
                <div class="col-md-3">
                    <label class="">Cost Price</label>
                    <input onclick="this.select()" type="text" class="form-control form-control-sm text-end" name="cost_price" id="cost_price" value="<?= $cost_price ?>" placeholder="0.00" required>
                </div>
                <div class="col-md-3">
                    <label class="">Selling Price</label>
                    <input onclick="this.select()" type="text" class="form-control form-control-sm text-end" name="selling_price" id="selling_price" value="<?= $selling_price ?>" placeholder="0.00" required>
                </div>
                <div class="col-md-3">
                    <label class="">Minimum Price</label>
                    <input onclick="this.select()" type="text" class="form-control form-control-sm text-end" name="min_price" id="min_price" value="<?= $min_price ?>" placeholder="0.00" required>
                </div>
                <div class="col-md-3">
                    <label class="">Wholesale Price</label>
                    <input onclick="this.select()" type="text" class="form-control form-control-sm text-end" name="wholesale_price" id="wholesale_price" value="<?= $wholesale_price ?>" placeholder="0.00" required>
                </div>

                <div class="col-md-3">
                    <label class="">Price 2</label>
                    <input onclick="this.select()" type="text" class="form-control form-control-sm text-end" name="price_2" id="price_2" value="<?= $price_2 ?>" placeholder="0.00" required>
                </div>
            </div>

            <p class="card-description mt-4">Supplier Details</p>
            <div class="row mt-3">
                <?php
                if (!empty($Suppliers)) {
                    foreach ($Suppliers as $Supplier) {
                        $supplier_name = $Supplier['supplier_name'];
                        $supplier_id = $Supplier['supplier_id'];
                        $active_status = "Deleted";
                        $color = "warning";
                        if ($Supplier['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        } else {
                            continue;
                        }

                        $isChecked = in_array($supplier_id, $SupplierArray) ? 'checked' : '';
                ?>
                        <div class="col-4 col-md-3">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" name="supplier_id[]" id="supplier_id" class="form-check-input supplier" value="<?= $supplier_id; ?>" <?= $isChecked ?>>
                                    <?= $supplier_name; ?>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <hr>
            <p class="card-description">Item Configuration</p>
            <div class="row">
                <div class="col-md-3">
                    <label class="">Item Type</label>
                    <select class="form-control form-control-sm" name="item_type" id="item_type" required>
                        <option value="">Select Item Type</option>
                        <option <?= ($item_type == 'Raw') ? 'selected' : '' ?> value="Raw">Raw</option>
                        <option <?= ($item_type == 'Package') ? 'selected' : '' ?> value="Package">Package</option>
                        <option <?= ($item_type == 'RawnSell') ? 'selected' : '' ?> value="RawnSell">Raw & Sell</option>
                        <option <?= ($item_type == 'SItem') ? 'selected' : '' ?> value="SItem">Selling Item</option>
                        <option <?= ($item_type == 'SService') ? 'selected' : '' ?> value="SService">Selling Service</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="">Base Location</label>
                    <select class="form-control form-control-sm" name="item_location" id="ItemLocation" required>
                        <option value="">Select Location</option>
                        <?php
                        if (!empty($Locations)) {
                            foreach ($Locations as $selected_array) {
                                if ($selected_array['is_active'] != 1) {
                                    continue;
                                }
                        ?>
                                ?>
                                <option <?= ($selected_array['location_id'] == $item_location) ? 'selected' : '' ?> value="<?= $selected_array['location_id'] ?>"><?= $selected_array['location_name'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>


                <div class="col-md-3">
                    <label for="FeaturedImage" class="form-label">Product Image</label>
                    <input type="file" name="item_image" id="item_image" class="form-control">
                    <input type="hidden" name="item_image_tmp" value="<?= $ImgURL ?>">
                </div>

                <div class="col-md-3">
                    <label class="">Recipe Type</label>
                    <select class="form-control form-control-sm" name="recipe_type" id="recipe_type" required>
                        <option value="">Recipe Item Type</option>
                        <option <?= ($recipe_type == 0) ? 'selected' : '' ?> value="0">None</option>
                        <option <?= ($recipe_type == 1) ? 'selected' : '' ?> value="1">A La Carte Recipe</option>
                        <option <?= ($recipe_type == 2) ? 'selected' : '' ?> value="2">Item Recipe</option>
                    </select>
                </div>

            </div>


            <p class="card-description my-4">Available Locations</p>
            <div class="row mt-3">
                <?php
                if (!empty($Locations)) {
                    foreach ($Locations as $location) {
                        if ($location['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        } else {
                            continue;
                        }

                        $isChecked = in_array($location['location_id'], $locationArray) ? 'checked' : '';
                ?>
                        <div class="col-4 col-md-3">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" name="availableLocation[]" id="availableLocation" class="form-check-input supplier" value="<?= $location['location_id']; ?>" <?= $isChecked ?>>
                                    <?= $location['location_name']; ?>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <hr>
            <div class="mt-4">
                <label for="product_description" class="form-label">Product Description</label>
                <textarea name="product_description" id="product_description" class="form-control" spellcheck="false" placeholder="Product Description"><?= $product_description ?></textarea>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-primary" id="SubmitButton" onclick="SaveProduct('<?= $ActiveStatus ?>', '<?= $updateKey ?>') "><?= $ButtonText ?></button>
                <button type="button" class="btn btn-secondary cancel" id="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea',
        plugins: 'fullscreen anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'fullscreen undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>

<!-- JavaScript to trigger onchange event on page load -->
<script>
    var SelectedSection = document.getElementById('section_id').value;
    SelectDepartments(SelectedSection, '<?= $product_department ?>', '<?= $product_category ?>')
</script>