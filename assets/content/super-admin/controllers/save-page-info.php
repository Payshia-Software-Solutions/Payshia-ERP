<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

include_once '../classes/Database.php';
include_once '../classes/Pages.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();
$pages = new Pages($database);

$page_id = $_POST['pageId'];

// Update
$updateData = [
    'display_name' => $_POST['display_name'],
    'page_name' => $_POST['page_name'],
    'pack_icon' => $_POST['pack_icon'],
    'root' => $_POST['root'],
    'open_type' => $_POST['open_type'],
];

if ($page_id == 0) {
    if ($pages->add($updateData)) {
        $error = array('status' => 'success', 'message' => 'Page Saved successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $pages->getLastError());
    }
} else {
    if ($pages->update($updateData, $page_id)) {
        $error = array('status' => 'success', 'message' => 'Page Updated successfully.');
    } else {
        $error = array('status' => 'error', 'message' => 'Failed to Update employee.' . $pages->getLastError());
    }
}


echo json_encode($error);
