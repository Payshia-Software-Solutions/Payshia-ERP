<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include_once '../classes/LmsDatabase.php';
include_once '../classes/Assignments.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

$courseCode = $_POST['courseCode'];
$assignmentId = $_POST['assignmentId'];
$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);
$assignmentName = $ass_type = $due_date = $due_time = $file_name = '';
if ($assignmentId != 0) {
    $assignmentInfo = $assignments->fetchById($assignmentId);
    $assignmentName = $assignmentInfo['assignment_name'];
    $ass_type = $assignmentInfo['type'];
    $file_name = $assignmentInfo['file_path'];
    $activeStatus = $assignmentInfo['active_status'];

    // Split due_date_time into due_date and due_time
    $due_date_time = new DateTime($assignmentInfo['due_date']);
    $due_date = $due_date_time->format('Y-m-d');
    $due_time = $due_date_time->format('H:i');
}

$assignmentTypes = [1 => 'Assignment', 2 => 'End Exam', 3 => 'Unit Test'];
?>
<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Assignment Details</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="AddNewAssignment( '<?= $courseCode ?>', '<?= $assignmentId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUP(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>

    <form action="#" method="post" id="assignment-form" enctype="multipart/form-data">

        <div class="row g-3">
            <div class="col-6 col-md-3">
                <label for="assignmentId">Assignment ID</label>
                <input class="form-control" type="text" name="assignment_id" id="assignment_id" readonly value="<?= $assignmentId ?>">
            </div>

            <div class="col-6 col-md-3">
                <label for="assignment_type">Assignment Type</label>
                <select name="assignment_type" id="assignment_type" class="form-control">
                    <?php foreach ($assignmentTypes as $key => $assignmentType) : ?>
                        <option value="<?= $key ?>"><?= $assignmentType ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="assignment_name">Assignment Name</label>
                <input type="text" name="assignment_name" id="assignment_name" class="form-control" value="<?= $assignmentName ?>" required>
            </div>
        </div>
        <div class="row g-3 mt-2">


            <div class="col-6 col-md-3">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="<?= $due_date ?>" required>
            </div>

            <div class="col-6 col-md-3">
                <label for="due_time">Due Time</label>
                <input type="time" name="due_time" id="due_time" class="form-control" value="<?= $due_time ?>" required>
            </div>

            <div class="col-12 col-md-3">
                <label for="assignment_file">File</label>
                <input type="file" name="assignment_file" id="assignment_file" class="form-control">
                <input type="hidden" id="temp_name" name="temp_name" value="<?= $file_name ?>"">
            </div>

            <div class=" col-12 text-end">
                <button onclick="SaveAssignment('<?= $courseCode ?>', <?= $assignmentId ?>)" class="btn btn-dark btn-sm" type="button"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>

        </div>
    </form>
</div>