<?php
// Use the classes
// Include the classes
include_once '../classes/Database.php';
include_once '../classes/Employee.php';
include_once '../classes/ImageUpload.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Employee object
$employee = new Employee($database);

$newEmployee = $_POST;

// Unset Items
unset($newEmployee['temp_nic']);
unset($newEmployee['temp_grama_niladhari_certificate']);
unset($newEmployee['temp_cover_image']);
unset($newEmployee['temp_police_certificate']);
unset($newEmployee['UserLevel']);
unset($newEmployee['company_id']);

// Append Data
$newEmployee['updated_at'] = date('Y-m-d');




// if (isset($_FILES['grama_niladhari_certificate'])) {
//     $grama_niladhari_certificate_upload = new ImageUpload($_FILES['grama_niladhari_certificate'], 'uploads');
// }

// if (isset($_FILES['cover_image'])) {
//     $cover_image_upload = new ImageUpload($_FILES['cover_image'], 'uploads');
// }

// if (isset($_FILES['police_certificate'])) {
//     $police_certificate_upload = new ImageUpload($_FILES['police_certificate'], 'uploads');
// }

// // Try to upload the image
// if ($fileName = $upload->upload()) {
//     $image_error = array('status' => 'success', 'message' => 'Image uploaded successfully: ' . $fileName);
// } else {
//     $image_error = array('status' => 'error', 'message' => 'Error uploading image: ' . $upload->getError());
// }

// Create an instance of the ImageUpload classCopy code
// Check if the file input exists and a file is selected


if ($_POST['employee_id'] == 0) {
    $employee_id = $employee->generateNewEmployeeID();
    $newEmployee['employee_id'] = $employee_id;
} else {
    $employee_id = $_POST['employee_id'];
}

$keyString = 'nic';
if (isset($_FILES[$keyString]) && $_FILES[$keyString]['error'] != UPLOAD_ERR_NO_FILE) {
    $targetFolder = '../assets/images/employee/' . $employee_id . '/' . $keyString . '/';
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

$keyString = 'grama_niladhari_certificate';
if (isset($_FILES[$keyString]) && $_FILES[$keyString]['error'] != UPLOAD_ERR_NO_FILE) {
    $targetFolder = '../assets/images/employee/' . $employee_id . '/' . $keyString . '/';
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




if ($_POST['employee_id'] == 0) {

    // Insert a new employee
    if ($employee->addEmployee($newEmployee)) {
        $error = array('status' => 'success', 'message' => 'Employee added successfully.');
    } else {
        // $error = array('status' => 'error', 'message' => 'Failed to add employee.');
        $error = array('status' => 'error', 'message' => 'Failed to add employee.' . $employee->getLastError());
    }
} else {
    // Replace with the actual employee ID to update
    if ($employee->updateEmployee($newEmployee, $employee_id)) {
        $error = array('status' => 'success', 'message' => 'Employee Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $employee->getLastError());
    }
}



echo json_encode($error);
