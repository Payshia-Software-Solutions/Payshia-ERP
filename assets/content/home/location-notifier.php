
<?php
require_once('../../../include/config.php');
include '../../../include/settings_functions.php';
$defaultLocation = GetUserDefaultValue($link, $session_student_number, 'defaultLocation');

$error = array('status' => 'success', 'defaultLocation' => $defaultLocation);
echo json_encode($error);
