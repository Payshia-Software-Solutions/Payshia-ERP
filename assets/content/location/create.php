<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 1;
$updateKey = 0;
$ButtonText = "Save";
$DestinationName = $DestinationCategory = $City = $ImgURL = $LocationTag = $DestinationDescription = "";
if ($_POST['updateKey'] != 0) {
    $Destination = GetProducts($link)[$_POST['updateKey']];
    $updateKey = $_POST['updateKey'];

    $DestinationName = $Destination['destination_name'];
    $DestinationCategory = $Destination['category'];
    $City = $Destination['cityID'];
    $ImgURL = $Destination['image_path'];
    $LocationTag = $Destination['location'];
    $DestinationDescription = $Destination['description'];
}

if (isset($_POST['is_active'])) {
    $ActiveStatus = $_POST['is_active'];
}


if ($ActiveStatus == 0) {
    $ButtonText = "Delete";
}
$Cities = GetCityList($link);
?>
<!DOCTYPE html>
<style>
    #map {
        height: 500px;
    }
</style>
<div class="row my-4">
    <div class="col-12">
        <form class="add-class-form" id="add-form" action="" method="POST">

            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn-dark btn" onclick="AddProduct(1,0)">
                        <i class="clickable fa-solid fa-rotate-right"></i>
                    </button>
                    <button class="btn-success btn" onclick="AddProduct(1,0)">
                        <i class="clickable fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <h1 class="site-title">New Product</h1>
            <h4 class="mb-4 border-bottom pb-2">Product Information</h4>

            <div class="mb-3">
                <div class="row ">
                    <div class="col-md-12">
                        <label for="DestinationName" class="form-label">Product Name</label>
                        <input type="text" name="ProductName" id="ProductName" class="form-control" value="<?= $DestinationName ?>" placeholder="Product Name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="DestinationName" class="form-label">Print Name</label>
                        <input type="text" name="ProductName" id="ProductName" class="form-control" value="<?= $DestinationName ?>" placeholder="Print Name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="DestinationName" class="form-label">Display Name</label>
                        <input type="text" name="ProductName" id="ProductName" class="form-control" value="<?= $DestinationName ?>" placeholder="Display Name" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="CategoryID" class="form-label">Section</label>
                        <select class="form-select" name="CategoryID" id="CategoryID" required>
                            <option value="">Select Section</option>
                            <?php
                            if (!empty($Categories)) {
                                foreach ($Categories as $Category) {
                            ?>
                                    <option <?= ($Category['id'] == $DestinationCategory) ? 'selected' : '' ?> value="<?= $Category['id'] ?>"><?= $Category['category_name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="CityID" class="form-label">Department</label>
                        <select class="form-select" name="CityID" id="CityID" required autocomplete="off">
                            <option value="">Select Department</option>
                            <?php
                            if (!empty($Cities)) {
                                foreach ($Cities as $city) {
                                    $latLng = $city['latitude'] . "," . $city['longitude'];

                            ?>
                                    <option <?= ($city['id'] == $City) ? 'selected' : '' ?> value="<?= $city['id'] ?>" data-latlng="<?= $latLng ?>"><?= $city['name_en'] ?> - <?= $city['name_si'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="CityID" class="form-label">Category</label>
                        <select class="form-select" name="CityID" id="CityID" required autocomplete="off">
                            <option value="">Select Category</option>
                            <?php
                            if (!empty($Cities)) {
                                foreach ($Cities as $city) {
                                    $latLng = $city['latitude'] . "," . $city['longitude'];

                            ?>
                                    <option <?= ($city['id'] == $City) ? 'selected' : '' ?> value="<?= $city['id'] ?>" data-latlng="<?= $latLng ?>"><?= $city['name_en'] ?> - <?= $city['name_si'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3  mb-4">
                <div class="col-md-4">
                    <label class="">UOM</label>
                    <select class="form-control" name="UOM" id="UOM">
                        <option value="">Select Unit</option>
                        <option value="NOS">NOS</option>
                        <option value="g">Gram(g)</option>
                        <option value="kg">kiloGram(kg)</option>
                        <option value="ml">milliLiters(ml)</option>
                        <option value="l">Liters(l)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="">Reorder Level (Qty)</label>
                    <input type="text" class="form-control text-end" name="ReOderLevel" id="ReOderLevel" value="0.00" placeholder="0.00">

                </div>
                <div class="col-md-4">
                    <label class="">Lead Time (Days)</label>
                    <input type="text" class="form-control text-end" name="LeadDays" id="LeadDays" value="0.00" placeholder="0.00">
                </div>
            </div>

            <hr>
            <p class="card-description">Pricing Details</p>
            <div class="row">
                <div class="col-md-3">
                    <label class="">Cost Price</label>
                    <input type="text" class="form-control form-control-sm text-end" name="CostPrice" id="CostPrice" value="0.00" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="">Selling Price</label>
                    <input type="text" class="form-control form-control-sm text-end" name="SellingPrice" id="SellingPrice" value="0.00" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="">Minimum Price</label>
                    <input type="text" class="form-control form-control-sm text-end" name="MinimumPrice" id="MinimumPrice" value="0.00" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="">Wholesale Price</label>
                    <input type="text" class="form-control form-control-sm text-end" name="WholesalePrice" id="WholesalePrice" value="0.00" placeholder="0.00">
                </div>
            </div>

            <p class="card-description mt-4">Supplier Details</p>
            <div class="row mt-3">
                <?php
                $sql = "SELECT `supplier_id`, `supplier_name`, `opening_balance`, `active_status`, `created_by`, `created_at` FROM `master_supplier` ";
                $result = $link->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $supplier_id = $row['supplier_id'];
                        $supplier_name = $row['supplier_name'];
                ?>
                        <div class="col-4 col-md-3">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" name="supplier" id="supplier" class="form-check-input" value="<?php echo $supplier_id; ?>">
                                    <?php echo $supplier_name; ?>
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
                    <select class="form-control form-control-sm" name="ItemType" id="ItemType">
                        <option value="">Select Item Type</option>
                        <option value="Raw">Raw</option>
                        <option value="RawnSell">Raw & Sell</option>
                        <option value="SItem" selected>Selling Item</option>
                        <option value="SService">Selling Service</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="">Item Location</label>
                    <select class="form-control form-control-sm" name="ItemLocation" id="ItemLocation">
                        <option value="">Select Location</option>
                        <?php
                        $sql = "SELECT `location_id`, `location`, `active_status`, `created_by`, `created_at` FROM `master_location` WHERE `active_status` LIKE 1";
                        $result = $link->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $location_id = $row['location_id'];
                                $location = $row['location'];
                        ?>
                                <option value="<?php echo $location_id; ?>"><?php echo $location; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="FeaturedImage" class="form-label">Featured Image</label>
                    <input type="file" name="FeaturedImage" id="FeaturedImage" class="form-control">
                    <input type="hidden" name="item_image_tmp" value="<?= $ImgURL ?>">
                </div>

            </div>

            <div class="mt-4">
                <label for="DestinationDescription" class="form-label">Product Description</label>
                <textarea name="DestinationDescription" id="DestinationDescription" class="form-control" spellcheck="false" placeholder="Product Description"><?= $DestinationDescription ?></textarea>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-primary" id="SubmitButton" onclick="SaveDestination('<?= $ActiveStatus ?>', '<?= $updateKey ?>') "><?= $ButtonText ?></button>
                <button type="button" class="btn btn-secondary cancel" id="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>