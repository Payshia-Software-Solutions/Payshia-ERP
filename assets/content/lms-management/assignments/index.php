<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include_once './classes/LmsDatabase.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();
$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();
?>

<div class="row g-3">
    <div class="col-12">
        <h5 class="table-title mb-4">Please Choose Batch to open Assignments</h5>
        <div class="row g-3">
            <?php foreach ($CourseBatches as $batch) : ?>
                <div class="col-md-3 d-flex">
                    <div class="card clickable flex-fill" onclick="GetCourseAssignments('<?= $batch['course_code'] ?>')">
                        <div class="card-body">
                            <h6 class="mb-0"><?= $batch['course_name'] ?></h6>
                            <p class="mb-0"><?= $batch['course_code'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>