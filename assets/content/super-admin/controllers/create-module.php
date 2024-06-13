<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

include '../methods/functions.php';
include '../methods/templates.php';

$moduleName = isset($_POST['module_name']) ? formatModuleName($_POST['module_name']) : 'default_module';

// Call the function with the given name and template
$returnValues = createFolderStructure($moduleName, $indexTemplate);
echo json_encode($returnValues);
