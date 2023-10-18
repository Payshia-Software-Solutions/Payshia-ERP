<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$ProductID = $_POST['ProductID'];
$UserName = $_POST['LoggedUser'];

$result = RemoveFromCart($link, $ProductID, $UserName);
echo $result;
