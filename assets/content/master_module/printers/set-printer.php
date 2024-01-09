<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';

$location_id = $_POST['LocationID'];
$settingKey = $_POST['settingKey'];
$settingValue = $_POST['settingValue'];

$queryResult = UpdateSetting($link, $location_id, $settingKey, $settingValue);
echo $queryResult;
