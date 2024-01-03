<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$erpProductId = $_POST['erpProductId'];
$createdBy = $_POST['LoggedUser'];
$refCode = $_POST['refCode'];
$isActive = $_POST['isActive'];

$queryStatus = SaveProductErpLink($erpProductId, $refCode, $createdBy, $isActive);
echo $queryStatus;
