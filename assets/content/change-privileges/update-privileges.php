<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
$userName = $_POST['userName'];
$pageID = $_POST['pageID'];
$accessMode = $_POST['accessMode'];
$LoggedUser = $_POST['LoggedUser'];

$result = updatePrivilege($link, $userName, $pageID, $accessMode, $LoggedUser);
echo $result;
