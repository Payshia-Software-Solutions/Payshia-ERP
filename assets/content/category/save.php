<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$section_id = $_POST["section_id"];
$department_id = $_POST["department_id"];
$category_name = $_POST["category_name"];
$is_active = $_POST["is_active"];
$pos_display = $_POST['pos_display'];

$QueryResult = SaveCategory($link, $category_name, $is_active, $created_by, $UpdateKey, $section_id, $department_id, $pos_display);
echo $QueryResult;
