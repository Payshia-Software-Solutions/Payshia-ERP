<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$section_name = $_POST["section_name"];
$pos_display = $_POST["pos_display"];
$is_active = $_POST["is_active"];

$QueryResult = SaveSection($link, $section_name, $is_active, $created_by, $UpdateKey, $pos_display);
echo $QueryResult;
