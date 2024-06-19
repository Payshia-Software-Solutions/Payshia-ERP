<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

include_once '../classes/Database.php';
include_once '../classes/WorkLocation.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$userTheme = getUserTheme($userThemeInput);
$work_location_id = isset($_POST['work_location_id']) ? $_POST['work_location_id'] : null;

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

// Create a new Position object
$work_location = new WorkLocation($database);

if ($work_location_id != 0) {
    // Fetch all employees
    $arrayInfo = $work_location->fetchById($work_location_id);
}

?>


<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-6">
            <h4 class="mb-0">Create Department</h4>
        </div>

        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="AddNewWorkLocation('<?= $work_location_id ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-2 my-2"></div>
        </div>


    </div>

    <div class="row">
        <form action="#" id="work-location-form" method="post">
            <div class="row g-3">

                <div class="col-md-12">
                    <?php
                    $ElementName = 'Work Location Name';
                    $defaultValue = ($work_location_id != 0) ? $arrayInfo[convertToSnakeCase($ElementName)] : '';
                    echo ReturnTextInput($ElementName, 'required', 'form-control',)
                    ?>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-light btn-sm" onclick="ClosePopUP(1)" type="button"><i class="fa solid fa-xmark"></i> Cancel</button>
                    <button class="btn btn-success btn-sm" onclick="SaveWorkLocation('<?= $work_location_id ?>')" type="button"><i class="fa solid fa-floppy-disk"></i> Save Changes</button>
                </div>

            </div>
        </form>
    </div>
</div>