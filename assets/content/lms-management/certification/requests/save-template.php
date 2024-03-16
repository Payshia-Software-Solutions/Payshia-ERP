<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$templateId = $_POST['templateId'];
$LoggedUser = $_POST['LoggedUser'];
$studentBatch = $_POST['studentBatch'];
$error = $file_name = "";

$template_name = $_POST['template_name'];
$left_margin = $_POST['name_position_from_left'];
$top_to_name = $_POST['name_position_from_top'];
$left_to_date = $_POST['date_position_from_left'];
$top_to_date = $_POST['date_position_from_top'];
$left_to_qr = $_POST['qr_position_from_left'];
$top_to_qr = $_POST['qr_position_from_top'];
$qr_width = $_POST['qr_code_width'];

$item_image_tmp = "no-image.png";
$item_image_tmp = $_POST['tempImage'];
if ($item_image_tmp == "") {
    $item_image_tmp = "no-image.png";
}

if (isset($_FILES['template_back'])) {
    $file_name = $_FILES['template_back']['name'];
}

if ($file_name == "") {
    $file_name = $item_image_tmp;
}
// Image Upload
$dir = '../assets/images/certificate-back/';

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

if (isset($_FILES['template_back'])) {
    $file_name = $_FILES['template_back']['name'];
    $file_size = $_FILES['template_back']['size'];
    $file_tmp = $_FILES['template_back']['tmp_name'];
    $file_type = $_FILES['template_back']['type'];

    $imagePath = "./assets/images/certificate-back/" . $file_name;
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
    move_uploaded_file($file_tmp, "../" . $imagePath);
} else {
    // echo json_encode(array('status' => 'error', 'message' => $errors[0]));
}
$courseCode = $_POST['studentBatch'];

if ($templateId == 0) {
    $sql = "INSERT INTO `certificate_template` (`template_name`, `left_margin`, `top_to_name`, `left_to_date`, `top_to_date`, `left_to_qr`, `top_to_qr`, `qr_width`, `is_active`,  `back_image`, `course_code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
} else {
    $sql = "UPDATE`certificate_template` SET `template_name` =?, `left_margin` =?, `top_to_name` =?, `left_to_date` =?, `top_to_date` =?, `left_to_qr` =?, `top_to_qr` =?, `qr_width` =?, `is_active` =?,  `back_image` = ?, `course_code` = ? WHERE `template_id` LIKE '$templateId'";
}

$error = array();
$current_time = date("Y-m-d H:i:s");
$is_active = 1;

if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
    mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $template_name, $left_margin, $top_to_name, $left_to_date, $top_to_date, $left_to_qr, $top_to_qr, $qr_width, $is_active, $file_name, $courseCode);

    if (mysqli_stmt_execute($stmt_sql)) {
        $error = array('status' => 'success', 'message' => 'Certificate Template saved successfully');
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    mysqli_stmt_close($stmt_sql);
} else {
    $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
}

echo json_encode($error);
