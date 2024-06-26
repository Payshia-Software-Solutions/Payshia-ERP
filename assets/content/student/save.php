<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../include/config.php');
include '../../../include/function-update.php';

$cover_img_tmp = $file_name = "no-image.png";
// Parameters
$company_id = $_POST['company_id'];
$is_active = $_POST['is_active'];
$updated_by = $_POST['updated_by'];
$updateKey = $_POST['updateKey'];

// New user account parameters
$status = $_POST['status'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$nic = $_POST['nic_number'];
$user_type = $_POST['user_type'];
$student_number = GenerateIndexNumber($link, $user_type);
$address_line_1 = $_POST['address_line1'];
$address_line_2 = $_POST['address_line2'];
$city = $_POST['city_id'];
$postal_code = $city;
$sex = $_POST['gender'];
$phone_number = $_POST['phone_number'];
$email_address = $_POST['email_address'];
$password = $_POST['password'];
$c_password = $_POST['c_password'];

if (isset($_POST['img_tmp'])) {
    $cover_img_tmp = $_POST['img_tmp'];
}

$result = array();
// var_dump($_FILES['profile-image']);
if (isset($_FILES['profile-image']) && $_FILES['profile-image']['name'] != "") {
    $file_name = $_FILES['profile-image']['name'];
    $file_size = $_FILES['profile-image']['size'];
    $file_tmp = $_FILES['profile-image']['tmp_name'];
    $file_type = $_FILES['profile-image']['type'];

    $file_parts = explode('.', $file_name);
    $file_ext = strtolower(end($file_parts));
    $expensions = array("jpeg", "jpg", "png", "webp");
    if (in_array($file_ext, $expensions) === false) {
        $result['extension_error'] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size > 5242880) {
        $result['file_size'] = 'File size must be less than 5 MB';
    }

    // Generate a unique file name using uniqid()
    $imagePath = "./assets/images/student/" . $file_name;

    if (empty($result) == true) {
        if (move_uploaded_file($file_tmp, "../../../" . $imagePath)) {
        } else {
            // Image upload failed
            $result['img_upload'] =  "Image upload failed.";
        }
    }
}

if ($file_name == "no-image.png") {
    $file_name = $cover_img_tmp;
}


// echo $file_name;


// var_dump($result);
if (empty($result) == true) {
    echo SaveUserAccount($link, $status, $first_name, $last_name, $nic, $student_number, $address_line_1, $address_line_2, $city, $postal_code, $sex, $phone_number, $email_address, $password, $user_type, $is_active, $updateKey, $company_id, $file_name, $updated_by);
}
