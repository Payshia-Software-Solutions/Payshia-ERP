<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

//Global  Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];
$error = $file_name = "";
$item_image_tmp = "no-image.png";
$today = date("Y-m-d H:i:s");

// Identical Parameters
$active_status = $_POST['is_active'];
$UpdateKey = $_POST['UpdateKey'];

// Variable Parameters
$product_code = "EDP" . time();
$product_name = $_POST['product_name'];
$print_name = $_POST['print_name'];
$display_name = $_POST['display_name'];
$section_id = $_POST['section_id'];
$department_id = $_POST['department_id'];
$category_id = $_POST['category_id'];
$brand_id = ($_POST['brand_id'] !== '') ? $_POST['brand_id'] : null;
$generic_id = ($_POST['generic_id'] !== '') ? $_POST['generic_id'] : null;
$size_id = ($_POST['size_id'] !== '') ? $_POST['size_id'] : null;
$color_id = ($_POST['color_id'] !== '') ? $_POST['color_id'] : null;
$measurement = $_POST['product_unit'];
$reorder_level = $_POST['reorder_level'];
$lead_days = $_POST['lead_time'];
$cost_price = $_POST['cost_price'];
$selling_price = $_POST['selling_price'];
$minimum_price = $_POST['min_price'];
$wholesale_price = $_POST['wholesale_price'];
$supplier_ids = $_POST['supplier_id']; // This is an array, so you'll need to handle it accordingly
$availableLocations = $_POST['availableLocation']; // This is an array, so you'll need to handle it accordingly
$item_type = $_POST['item_type'];
$item_location = $_POST['item_location'];
$barcode = $_POST['productBarcode'];


$name_si = $_POST['name_si'];
$name_ti = $_POST['name_ti']; // This is an array, so you'll need to handle it accordingly
$price_2 = $_POST['price_2'];
$recipe_type = $_POST['recipe_type'];

// Construct Supplier Array
$supplier_list = implode(',', $supplier_ids);
$location_list = implode(',', $availableLocations);

// The 'item_image' is a file upload, so you need to handle it as needed
$product_description = $_POST['product_description'];
$item_image_tmp = $_POST['item_image_tmp'];
if ($item_image_tmp == "") {
    $item_image_tmp = "no-image.png";
}

if (isset($_FILES['item_image'])) {
    $file_name = $_FILES['item_image']['name'];
}

if ($file_name == "") {
    $file_name = $item_image_tmp;
}

$QueryResult = SaveProduct($link, $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $file_name, $created_by, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $UpdateKey, $name_si, $name_ti, $price_2, $recipe_type, $barcode, $location_list);

// Decode the JSON response
$response = json_decode($QueryResult);
$UpdateKey = $lastInsertedId = $response->last_inserted_id;

// Image Upload
$dir = '../../../pos-system/assets/images/products/' . $UpdateKey;

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

if (isset($_FILES['item_image'])) {
    $file_name = $_FILES['item_image']['name'];
    $file_size = $_FILES['item_image']['size'];
    $file_tmp = $_FILES['item_image']['tmp_name'];
    $file_type = $_FILES['item_image']['type'];

    $imagePath = "./pos-system/assets/images/products/" . $UpdateKey . "/" . $file_name;
    $file_parts = explode('.', $file_name);
    $file_ext = strtolower(end($file_parts));
    $expensions = array("jpeg", "jpg", "png", "webp");
    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size must be exactly 2 MB';
    }
}

if ($file_name == "") {
    $file_name = $item_image_tmp;
}

if (empty($errors) == true) {
    move_uploaded_file($file_tmp, "../../../" . $imagePath);
} else {
    // echo json_encode(array('status' => 'error', 'message' => $errors[0]));
}

// Return The JSON Output
echo $QueryResult;
