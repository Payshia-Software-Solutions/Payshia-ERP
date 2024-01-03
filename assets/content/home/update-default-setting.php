<?php
require_once('../../../include/config.php');
include '../../../include/settings_functions.php';

$userName = $_POST['userName'];
$locationId = $_POST['locationId'];
$setting = $_POST['setting'];
$queryResult = UpdateUserDefaultValue($link, $userName, $setting, $locationId);
echo $queryResult;
