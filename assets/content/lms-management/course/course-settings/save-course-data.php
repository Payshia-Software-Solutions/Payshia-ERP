<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include '../methods/course_methods.php';

$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
$generatedCourseCode = isset($_POST['course_code']) && !empty($_POST['course_code']) ? $_POST['course_code'] : GenerateNewCourseCode();
$updateStatus = isset($_POST['course_code']) && !empty($_POST['course_code']) ? 1 : 0;
$item_image_tmp = isset($_POST['item_image_tmp']) && !empty($_POST['item_image_tmp']) ? $_POST['item_image_tmp'] : "no-image.png";
$module_list = '';
$targetDir = './../../assets/images/course-img/' . $generatedCourseCode . '/';

// Create the target directory if it doesn't exist
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Check if an image file is uploaded
if (isset($_FILES['course_img']) && $_FILES['course_img']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['course_img']['name'];
    $file_size = $_FILES['course_img']['size'];
    $file_tmp = $_FILES['course_img']['tmp_name'];
    $file_type = $_FILES['course_img']['type'];

    $imagePath = $targetDir . $file_name;

    // Check file extension
    $allowed_extensions = array("jpeg", "jpg", "png", "webp");
    $file_parts = pathinfo($file_name);
    $file_ext = strtolower($file_parts['extension']);

    if (!in_array($file_ext, $allowed_extensions)) {
        echo json_encode(array('status' => 'error', 'message' => 'Extension not allowed, please choose a JPEG, PNG, or WEBP file.'));
        exit;
    }

    // Check file size (2MB limit)
    if ($file_size > 2097152) {
        echo json_encode(array('status' => 'error', 'message' => 'File size must be no larger than 2 MB.'));
        exit;
    }

    // Move uploaded file to destination directory
    if (move_uploaded_file($file_tmp, $imagePath)) {
        // File uploaded successfully
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to move uploaded file.'));
        exit;
    }
} else {
    // Use default image if no file is uploaded
    $file_name = $item_image_tmp;
}

$moduleList = isset($_POST['module_list']) ? $_POST['module_list'] : '';
if (!empty($module_list)) {
    $module_list = implode(',', $moduleList);
}

// Sample course data taken from POST parameters
$courseData = array(
    'id' => isset($_POST['course_id']) ? $_POST['course_id'] : 0,
    'course_name' => isset($_POST['course_name']) ? $_POST['course_name'] : '',
    'course_code' => $generatedCourseCode,
    'instructor_id' => isset($_POST['instructor_id']) ? $_POST['instructor_id'] : 0,
    'course_description' => isset($_POST['course_description']) ? $_POST['course_description'] : '',
    'course_duration' => isset($_POST['course_duration']) ? $_POST['course_duration'] : '',
    'course_fee' => isset($_POST['course_fee']) ? $_POST['course_fee'] : 0.0,
    'registration_fee' => isset($_POST['registration_fee']) ? $_POST['registration_fee'] : 0.0,
    'created_at' => date('Y-m-d H:i:s'), // Assuming current datetime for created_at
    'created_by' => isset($_POST['LoggedUser']) ? $_POST['LoggedUser'] : '',
    'display' => isset($_POST['display']) ? $_POST['display'] : 0,
    'course_img' => $file_name,
    'courseMode' => isset($_POST['courseMode']) ? $_POST['courseMode'] : 'Free',
    'certification' => isset($_POST['certification']) ? $_POST['certification'] : '',
    'mini_description' => isset($_POST['mini_description']) ? $_POST['mini_description'] : '',
    'is_active' => isset($_POST['isActive']) ? $_POST['isActive'] : 1,
    'lecture_count' => isset($_POST['lecture_count']) ? $_POST['lecture_count'] : 0,
    'hours_per_lecture' => isset($_POST['hours_per_lecture']) ? $_POST['hours_per_lecture'] : 0,
    'assessments' => isset($_POST['assessments']) ? $_POST['assessments'] : '',
    'language' => isset($_POST['language']) ? $_POST['language'] : '',
    'quizzes' => isset($_POST['quizzes']) ? $_POST['quizzes'] : '',
    'skill_level' => isset($_POST['skill_level']) ? $_POST['skill_level'] : '',
    'head_count' => isset($_POST['head_count']) ? $_POST['head_count'] : 0,
    'module_list' => $module_list,
    'updateStatus' => $updateStatus,

);

// Call the function
$result = SaveCourse($courseData);
echo json_encode($result);
