<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Parameters
$indexNo = $_POST["indexNo"];
$CourseCode = $_POST["CourseCode"];
$TitleIDs = $_POST["TitleIDs"];
$OptionValues = $_POST["OptionValues"];

$UserLevel = $_POST["UserLevel"];
$LoggedUser = $_POST["LoggedUser"];
$error = "";
$today = date("Y-m-d");

// Loop through the arrays of TitleIDs and OptionValues
for ($i = 0; $i < count($TitleIDs); $i++) {
    $TitleID = $TitleIDs[$i];
    $OptionValue = $OptionValues[$i];

    $sql_inner = "SELECT `id` FROM `certificate_user_result` WHERE `index_number` = ? AND `course_code` = ? AND `title_id` = ?";
    $stmt_inner = $lms_link->prepare($sql_inner);
    $stmt_inner->bind_param("sss", $indexNo, $CourseCode, $TitleID);
    $stmt_inner->execute();
    $result_inner = $stmt_inner->get_result();

    if ($result_inner->num_rows > 0) {
        $sql = "UPDATE `certificate_user_result` SET `result` = ?, `created_by` = ? WHERE `index_number` = ? AND `course_code` = ? AND `title_id` = ?";
    } else {
        $sql = "INSERT INTO `certificate_user_result`(`index_number`, `course_code`, `title_id`, `result`, `created_by`) VALUES (?, ?, ?, ?, ?)";
    }

    $stmt_sql = $lms_link->prepare($sql);
    $stmt_sql->bind_param("sssss", $indexNo, $CourseCode, $TitleID, $OptionValue, $LoggedUser);

    if ($stmt_sql->execute()) {
        $error = "Successfully Saved";
    } else {
        $error .= "Error saving certificate for TitleID: $TitleID<br>";
    }
}

echo $error;
