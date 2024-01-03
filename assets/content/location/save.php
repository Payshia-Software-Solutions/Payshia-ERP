<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");
$file_name = "";
$today = date("Y-m-d H:i:s");
// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];


$item_image_tmp = $_POST['item_image_tmp'];
if ($item_image_tmp == "") {
    $item_image_tmp = "no-image.png";
}

if (isset($_FILES['location_image'])) {
    $file_name = $_FILES['location_image']['name'];
}

if ($file_name == "") {
    $file_name = $item_image_tmp;
}


// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$location_name = $_POST["location_name"];
$is_active = $_POST["is_active"];

$address_line_1 = $_POST["address_line_1"];
$address_line_2 = $_POST["address_line_2"];
$city_name = $_POST["city_name"];
$phone_number_1 = $_POST["phone_number_1"];
$phone_number_2 = $_POST["phone_number_2"];

$QueryResult = SaveLocation($link, $location_name, $is_active, $created_by, $file_name, $address_line_1, $address_line_2, $city_name, $phone_number_1, $phone_number_2, $UpdateKey);
echo $QueryResult;


// Decode the JSON response
$response = json_decode($QueryResult);
$UpdateKey = $lastInsertedId = $response->last_inserted_id;
// Image Upload
$dir = '../../../pos-system/assets/images/location/' . $UpdateKey;

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

if (isset($_FILES['location_image'])) {
    $file_name = $_FILES['location_image']['name'];
    $file_size = $_FILES['location_image']['size'];
    $file_tmp = $_FILES['location_image']['tmp_name'];
    $file_type = $_FILES['location_image']['type'];

    $imagePath = "./pos-system/assets/images/location/" . $UpdateKey . "/" . $file_name;
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
