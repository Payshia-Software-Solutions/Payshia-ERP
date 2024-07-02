<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Include the classes
include_once '../classes/LmsDatabase.php';
include_once '../classes/WinpharmaReasons.php';


// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';

$database2 = new LmsDatabase($config_file);
$db2 = $database2->getConnection();

$LoggedUser = $_POST['LoggedUser'];
$medicineId = $_POST['medicineId'];

$WinpharmaReasons = new WinpharmaReasons($database2);

// Update an employee
$updateData = [
    'category_id' => $_POST['category_id'],
    'product_code' => $_POST['product_code'],
    'medicine_name' =>  $_POST['medicine_name'],
    'file_path' =>  $_POST['file_path'],
    'active_status' =>  $_POST['active_status'],
    'created_at' =>  $_POST['created_at'],
    'created_by' =>  $_POST['created_by'],
];

if ($reasonId == 0) :
    if ($WinpharmaReasons->add($updateData)) {
        $error = array('status' => 'success', 'message' => 'Reason saved successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update grade.' . $WinpharmaReasons->getLastError());
    }

else :
    if ($WinpharmaReasons->update($updateData, $submissionId)) {
        $error = array('status' => 'success', 'message' => 'Reason Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update grade.' . $WinpharmaReasons->getLastError());
    }

endif;

$keyString = 'nic';
if (isset($_FILES[$keyString]) && $_FILES[$keyString]['error'] != UPLOAD_ERR_NO_FILE) {
    $targetFolder = '../assets/images/medicines/' . $medicineId . '/';
    $nic_upload = new ImageUpload($_FILES[$keyString], $targetFolder);

    if ($fileName = $nic_upload->upload()) {
        $image_error = array('status' => 'success', 'message' => 'Image uploaded successfully: ' . $fileName);
        $newEmployee[$keyString] = $fileName;
    } else {
        $image_error = array('status' => 'error', 'message' => 'Error uploading image: ' . $nic_upload->getError());
    }
} else {
    $newEmployee[$keyString] = $_POST['temp_' . $keyString];
}

echo json_encode($error);
