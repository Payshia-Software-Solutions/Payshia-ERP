<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$courseCode = $_POST['selectedCourse'];
$defaultTemplate = $_POST['defaultTemplate'];
$completeDate = $_POST['EndDate'];
$convocationDate = $_POST['convocationDate'];
$convocationPlace = $_POST['convocationPlace'];

$file_name = '';
$item_image_tmp = "no-image.png";
$item_image_tmp = $_POST['TranscriptBackTemp'];
if ($item_image_tmp == "") {
    $item_image_tmp = "no-image.png";
}

if (isset($_FILES['TranscriptBack'])) {
    $file_name = $_FILES['TranscriptBack']['name'];
}

if ($file_name == "") {
    $file_name = $item_image_tmp;
}

// Image Upload
$dir = '../assets/images/transcript-back/';

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

if (isset($_FILES['TranscriptBack'])) {
    $file_name = $_FILES['TranscriptBack']['name'];
    $file_size = $_FILES['TranscriptBack']['size'];
    $file_tmp = $_FILES['TranscriptBack']['tmp_name'];
    $file_type = $_FILES['TranscriptBack']['type'];

    $imagePath = "./assets/images/transcript-back/" . $file_name;
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




$saveResult = SaveOrUpdateTemplateConfig($courseCode, $completeDate, $defaultTemplate, $convocationDate, $convocationPlace, $file_name);
echo json_encode($saveResult);
