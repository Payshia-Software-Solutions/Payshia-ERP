<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Parameters
$UserLevel = $_POST["UserLevel"];
$LoggedUser = $_POST["LoggedUser"];
$error = $file_name = "";
$item_image_tmp = "no-image.png";
$today = date("Y-m-d H:i:s");
$UpdateStatus = 0;

if ($_POST['UpdateKey'] != 0) {
    $destination_id = $_POST['UpdateKey'];
    $UpdateStatus = 1;
} else {
    $sql = "SELECT `destination_id` FROM `destination`";
    $result = $link->query($sql);
    $accommodation_count = $result->num_rows;
    $destination_id = $accommodation_count + 1;
}

$is_active = $_POST['is_active'];
$UpdateKey = $_POST['UpdateKey'];
// Parameters
$DestinationName = $_POST["DestinationName"];
$CityID = $_POST["CityID"];
$DistrictID = GetDistrictID($link, $CityID);
$DestinationDescription = $_POST["description"];
$CategoryID = $_POST["CategoryID"];
$location_tag = $_POST["location_tag"];

$dir = '../../../assets/images/destination/' . $destination_id;
$cover_dir = '../../../assets/images/destination/' . $destination_id . '/cover/';
$file = '../../../vendor/file-viewer/index.php';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

if (!file_exists($cover_dir)) {
    mkdir($cover_dir, 0777, true);
}

// copy file to directory
if (file_exists($file)) {
    $new_file = $dir . '/' . basename($file);
    copy($file, $new_file);
}


if (isset($_POST['item_image_tmp'])) {
    $item_image_tmp = $_POST['item_image_tmp'];
}
if (isset($_FILES['FeaturedImage'])) {

    $file_name = $_FILES['FeaturedImage']['name'];
    $file_size = $_FILES['FeaturedImage']['size'];
    $file_tmp = $_FILES['FeaturedImage']['tmp_name'];
    $file_type = $_FILES['FeaturedImage']['type'];

    $imagePath = "assets/images/destination/" . $destination_id . "/cover/" . $file_name;
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

if (isset($_POST['tmp_hotel_photo'])) {
    $tmp_hotel_photo = $_POST['tmp_hotel_photo'];
}


$QueryResult = SaveDestination($link, $DestinationName, $CategoryID, $CityID, $DistrictID, $LoggedUser, $file_name, $DestinationDescription, $location_tag, $UpdateKey, $is_active);
echo $QueryResult;
