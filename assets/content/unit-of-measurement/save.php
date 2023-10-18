<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$unit_name = $_POST["unit_name"];
$is_active = $_POST["is_active"];

$QueryResult = SaveUnit($link, $unit_name, $is_active, $created_by, $UpdateKey);
echo $QueryResult;
