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
$reasonId = $_POST['reasonId'];

$WinpharmaReasons = new WinpharmaReasons($database2);

if ($WinpharmaReasons->delete($reasonId)) {
    $error = array('status' => 'success', 'message' => 'Reason Deleted successfully.');
} else {
    $error = array('status' => 'error', 'message' => 'Failed to Update grade.' . $WinpharmaReasons->getLastError());
}

echo json_encode($error);
