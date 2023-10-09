<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Parameters
$UserLevel = $_POST["UserLevel"];
$LoggedUser = $_POST["LoggedUser"];
$error = $file_name = "";
$item_image_tmp = "no-image.png";
$CurrentTime = date("Y-m-d H:i:s");
$UpdateKey = 0;

// Parameters
$is_active = $_POST['is_active'];
$DestinationID = $_POST['DestinationID'];
$UpdateKey = $_POST['UpdateKey'];
$alt_text = $_POST["alt_text"];
$img_description = $_POST["img_description"];

if (isset($_FILES['destination_img'])) {

    $file_name = $_FILES['destination_img']['name'];
    $file_size = $_FILES['destination_img']['size'];
    $file_tmp = $_FILES['destination_img']['tmp_name'];
    $file_type = $_FILES['destination_img']['type'];

    $imagePath = "assets/images/destination/" . $DestinationID . "/" . $file_name;
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


if (empty($errors) == true) {
    move_uploaded_file($file_tmp, "../../../" . $imagePath);
} else {
    // echo json_encode(array('status' => 'error', 'message' => $errors[0]));
}


$QueryResult = SaveDestinationImage($link, $DestinationID, $file_name, $alt_text, $img_description, $LoggedUser, $CurrentTime, $UpdateKey, $is_active);
echo $QueryResult;
