<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$RecordID = $_POST['RecordID'];
$error = RemoveFromOrder($link, $RecordID);
echo $error;
