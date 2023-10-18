<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
// Assuming you have received the necessary parameters for the plan
$LoggedUser = $_POST["LoggedUser"];
$UpdateKey = $_POST["UpdateKey"];
$IsActive = $_POST["IsActive"];

$result = UpdateTableStatus($link, $IsActive, $LoggedUser, $UpdateKey);
echo $result;
