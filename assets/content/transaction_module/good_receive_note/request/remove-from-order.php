<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$po_number = $_POST['po_number'];
$LoggedUser = $_POST['LoggedUser'];
$ProductID = $_POST['ProductID'];


$result = RemoveFromTempGRN($link, $LoggedUser, $ProductID, $po_number);
echo $result;
