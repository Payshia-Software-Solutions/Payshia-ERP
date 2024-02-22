<?php
require_once('../../../include/config.php');
$approveLink = $link;
$approveStatus = 0;
$LoggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$uniqueIdentifier = $_POST['visitorId'];

$dateTime = new DateTime();
$timestamp = $dateTime->format("Y-m-d H:i:s.u");

$sql = "SELECT `id` FROM `approved_device_list` WHERE `unique_identifier` LIKE '$uniqueIdentifier' AND `user_name` LIKE '$LoggedUser'";
$result = $approveLink->query($sql);
if ($result->num_rows <= 0) {
    $sql = "INSERT INTO `approved_device_list` (`unique_identifier`, `user_name`, `user_level`, `created_at`, `approve_status`) VALUES (?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($approveLink, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $uniqueIdentifier, $LoggedUser, $UserLevel, $timestamp, $approveStatus);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Device Request Successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
} else {
    $sql = "SELECT * FROM `approved_device_list` WHERE `unique_identifier` LIKE '$uniqueIdentifier' AND `user_name` LIKE '$LoggedUser' AND `approve_status` LIKE 1";
    $result = $approveLink->query($sql);
    if ($result->num_rows > 0) {
        $error = array('status' => 'success', 'message' => 'Device Authorized');
    } else {
        $error = array('status' => 'error', 'message' => 'Device Not Authorized');
    }
}
echo json_encode($error);
