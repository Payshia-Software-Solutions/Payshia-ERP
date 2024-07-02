<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include_once 'classes/LmsDatabase.php';
include_once 'classes/Submissions.php';
include_once 'classes/Levels.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$submission = new Submissions($db);
$level = new Levels($db);
$submissions = $submission->fetchAll();

$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();
?>


<div id="total-counters"></div>
<div class="row mt-4">
    <div class="col-12 text-end">
        <button onclick="OpenCommonReasons()" type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-plus"></i> Add Common Reasons</button>
    </div>
</div>
<div id="submission-list">
    <div class="row g-3">
        <div class="col-12">
            <h5 class="table-title mb-4">Please Choose Batch to open Winpharma Submissions</h5>
            <div class="row g-3">
                <?php foreach ($CourseBatches as $batch) : ?>
                    <div class="col-md-3 d-flex">
                        <div class="card clickable flex-fill" onclick="GetWinpharmaSubmissions('<?= $batch['course_code'] ?>', 'Pending')">
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
</div>