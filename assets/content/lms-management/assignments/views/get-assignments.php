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
$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$assignments = new Assignments($database);
$assignmentList = $assignments->fetchAllByCourseCode($courseCode);
?>
<div class="row mb-3">
    <div class="col-12 text-end">
        <button type="button" onclick="AddNewAssignment('<?= $courseCode ?>')" class="btn btn-dark rounded-3"><i class="fa-solid fa-plus"></i> Add New</button>
    </div>
</div>
<?php if (empty($assignmentList)) : ?>
    <div class="row g-3">
        <div class="col-12">
            <div class="alert alert-danger">
                <h4 class="mb-0">No Assignments added to this Course</h4>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row my-3">
    <div class="col-12 text-end">
        <button class="btn btn-warning flex-fill" type="button" onclick="GetCourseAssignments( '<?= $courseCode ?>')"><i class="fa-solid fa-rotate-left"></i> Reload</button>
    </div>
</div>
<div class="row g-3">
    <?php foreach ($assignmentList as $assignment) :
        $activeBgColor = $assignment['active_status'] == 0 ? "danger" : "primary";
        $activeGradeValue = $assignment['active_status'] == 0 ? "Deleted" : "Active";
    ?>
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-4 shadow-sm clickable" onclick="OpenAssignment('<?= $courseCode ?>', '<?= $assignment['id'] ?>')">
                <div class="card-body">
                    <h4 class="mb-0"><?= $assignment['assignment_name'] ?></h4>
                    <span class="badge badge-<?= $activeBgColor ?> bg-<?= $activeBgColor ?>"><?= $activeGradeValue ?></span>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>