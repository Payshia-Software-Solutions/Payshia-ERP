<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include_once '../classes/LmsDatabase.php';
include_once '../classes/Assignments.php';
include_once '../classes/AssignmentSubmissions.php';
include '../include/functions.php';

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
$submissions = new AssignmentSubmissions($database);

$assignmentInfo = $assignments->fetchById($assignmentId);
$assignmentName = $assignmentInfo['assignment_name'];
$ass_type = $assignmentInfo['type'];
$file_name = $assignmentInfo['file_path'];
$activeStatus = $assignmentInfo['active_status'];

// Split due_date_time into due_date and due_time
$due_date_time = new DateTime($assignmentInfo['due_date']);
$due_date = $due_date_time->format('Y-m-d');
$due_time = $due_date_time->format('H:i');

$assignmentTypes = [1 => 'Assignment', 2 => 'End Exam', 3 => 'Unit Test'];
$submissionList = $submissions->fetchAllByAssignmentId($assignmentId);
$gradedList = $submissions->fetchAllGradedByAssignmentId($assignmentId);


?>
<div class="row">
    <div class="col-6 offset-6">
        <div class="d-flex justify-content-end gap-2">
            <?php if ($activeStatus == 1) : ?>
                <button class="btn btn-danger flex-fill" type="button" onclick="ChangeAssignmentStatus( '<?= $assignmentId ?>', '<?= $courseCode ?>', 0)"><i class="fa-solid fa-pencil-alt"></i> Delete Assignment</button>
            <?php else : ?>
                <button class="btn btn-primary flex-fill" type="button" onclick="ChangeAssignmentStatus( '<?= $assignmentId ?>', '<?= $courseCode ?>', 1)"><i class="fa-solid fa-pencil-alt"></i> Active Assignment</button>
            <?php endif ?>


            <button class="btn btn-dark flex-fill" type="button" onclick="AddNewAssignment( '<?= $courseCode ?>', '<?= $assignmentId ?>')"><i class="fa-solid fa-pencil-alt"></i> Edit Assignment</button>
            <button class="btn btn-dark flex-fill" type="button" onclick="OpenSubmissions( '<?= $courseCode ?>', '<?= $assignmentId ?>')"><i class="fa-solid fa-upload"></i> Submissions</button>
            <button class="btn btn-warning flex-fill" type="button" onclick="OpenAssignment( '<?= $courseCode ?>', '<?= $assignmentId ?>')"><i class="fa-solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-success flex-fill" type="button" onclick="GetCourseAssignments( '<?= $courseCode ?>')"><i class="fa-solid fa-arrow-left"></i> Back</button>
        </div>
    </div>
</div>
<div class="border-bottom my-2 border-2"></div>
<div class="row g-3">

    <div class="col-12 col-md-7">
        <?= displayAssignmentContent($file_name) ?>
    </div>
    <div class="col-12 col-md-5">
        <div class="row g-2 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2 border-bottom">Assignment</p>
                        <h3 class=""><?= $assignmentName ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <p class="mb-2 border-bottom">Due</p>
                        <h3 class=""><?= $due_date ?> <?= $due_time ?></h3>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2 border-bottom">Submissions</p>
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-0"><?= count($submissionList) ?></h4>
                            <button type="button" onclick="OpenSubmissions( '<?= $courseCode ?>', '<?= $assignmentId ?>')" class="btn btn-sm btn-dark"><i class="fa-solid fa-eye"></i> View</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2 border-bottom">Graded</p>
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-0"><?= count($gradedList) ?></h4>
                            <button class="btn btn-sm btn-dark"><i class="fa-solid fa-eye"></i> View</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover table-striped" id="submission-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Grade</th>
                                            <th>Grade Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($submissionList as $submission) :
                                            $bgColor = $submission['grade_status'] == 0 ? "danger" : "success";
                                            $gradeValue = $submission['grade_status'] == 0 ? "Not Graded" : "Graded";

                                            $activeBgColor = $submission['is_active'] == 0 ? "danger" : "primary";
                                            $activeGradeValue = $submission['is_active'] == 0 ? "Deleted" : "Active";
                                            if ($submission['is_active'] == 0) {
                                                continue;
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $submission['id'] ?></td>
                                                <td><?= $submission['created_by'] ?></td>
                                                <td>
                                                    <?php if ($submission['is_active'] == 1) : ?>
                                                        <button type="button" onclick="OpenSubmittedFiles('<?= $submission['assignment_id'] ?>', '<?= $submission['created_by'] ?>', '<?= $courseCode ?>')" class="btn btn-sm btn-dark"><i class="fa-solid fa-eye"></i></button>
                                                        <button type="button" onclick="ForceDeleteSubmission('<?= $submission['id'] ?>','<?= $courseCode ?>', '<?= $submission['assignment_id'] ?>')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                    <?php else : ?>
                                                        <button type="button" onclick="ChangeStatus('<?= $submission['assignment_id'] ?>', '<?= $submission['created_by'] ?>', '<?= $courseCode ?>', 1)" class="btn btn-sm btn-primary"><i class="fa-solid fa-check"></i> Reactive</button>
                                                    <?php endif ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?= $activeBgColor ?> bg-<?= $activeBgColor ?>"><?= $activeGradeValue ?></span>
                                                </td>
                                                <td><?= $submission['grade'] ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $bgColor ?> bg-<?= $bgColor ?>"><?= $gradeValue ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    $('#submission-table').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
            // 'colvis'
        ],
        order: [
            [4, 'asc'],
            [0, 'asc']
        ]
    })
</script>