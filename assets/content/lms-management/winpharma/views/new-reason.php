<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);

include_once '../classes/LmsDatabase.php';
include_once '../classes/WinpharmaReasons.php';

$LoggedUser = $_POST['LoggedUser'];
$reasonId = $_POST['reasonId'];

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$WinpharmaReasons = new WinpharmaReasons($db);
$reason = $WinpharmaReasons->fetchById($reasonId);
$reasonDescription = '';
if (isset($reason['reason'])) {
    $reasonDescription = $reason['reason'];
}
?>

<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-6">
            <h4 class="mb-0">Reason Info</h4>
        </div>

        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="NewReason('<?= $reasonId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUP(0)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-2 my-2"></div>
        </div>
    </div>

    <form action="#" method="post" id="reason-form">
        <div class="row g-2">
            <div class="col-md-12">
                <input type="text" value="<?= $reasonDescription ?>" name="reason" id="reason" class="form-control" placeholder="Enter Reason Description">
            </div>
            <div class="col-md-12 text-end">
                <button onclick="saveReason('<?= $reasonId ?>')" type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>
        </div>
    </form>

</div>