<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
// Assuming you have received the necessary parameters for the plan
$LoggedUser = $_POST["LoggedUser"];
$DestinationID = $_POST["UpdateKey"];
$isActive = $_POST["IsActive"];
$createdAt = date("Y-m-d H:i:s"); // You can set the creation date here

$result = UpdateDestinationStatus($link, $DestinationID, $isActive, $createdAt, $LoggedUser);
echo $result;
