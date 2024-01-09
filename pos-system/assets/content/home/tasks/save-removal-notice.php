<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$UserName = $_POST['LoggedUser'];
$LocationID = $_POST['LocationID'];

$productID = $_POST['productID'];
$ref_id = $_POST['refKey'];
$reason = $_POST['reason'];
$userID = $_POST['userID'];

$result = SaveRemovalNotice($link, $ref_id, $reason, $userID, $UserName, $LocationID, $productID);
echo $result;
