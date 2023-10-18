<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$table_name = $_POST["table_name"];
$is_active = $_POST["is_active"];
$location_id = $_POST['location_id'];

$QueryResult = SaveTable($link, $table_name, $is_active, $created_by, $UpdateKey, $location_id);
echo $QueryResult;
