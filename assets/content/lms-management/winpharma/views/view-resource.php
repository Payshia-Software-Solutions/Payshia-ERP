<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);

include_once '../classes/LmsDatabase.php';
include_once '../classes/Tasks.php';

$resourceId = $_POST['resourceId'];
$LoggedUser = $_POST['LoggedUser'];
// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$Tasks = new Tasks($db);

$taskInfo = $Tasks->GetTaskByResourceID($resourceId);
?>

<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-6">
            <h4 class="mb-0"><?= $taskInfo["resource_title"] ?></h4>
        </div>

        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="ViewResource('<?= $resourceId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUP(0)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-2 my-2"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-2">
            <?= $taskInfo["resource_data"] ?>
        </div>
    </div>

</div>