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
$assignments = new Assignments($database);
$submissions = new AssignmentSubmissions($database);
$db = $database->getConnection();

$courseCode = $_POST['courseCode'];
$assignmentId = $_POST['assignmentId'];
$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

// Get Assignment Info
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

?>

<div class="row">
    <div class="col-12 text-end">
        <button class="btn btn-dark btn-sm" type="button" onclick="OpenSubmissions( '<?= $courseCode ?>', '<?= $assignmentId ?>')"><i class="fa-solid fa-rotate-left"></i> Reload</button>
        <button class="btn btn-warning  btn-sm" type="button" onclick="OpenAssignment( '<?= $courseCode ?>', '<?= $assignmentId ?>')"><i class="fa-solid fa-arrow-left"></i> Back</button>
    </div>
</div>
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
                            <th>Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissionList as $submission) :
                        ?>
                            <tr>
                                <td><?= $submission['id'] ?></td>
                                <td><?= $submission['created_by'] ?></td>
                                <td>
                                    <button type="button" onclick="OpenSubmittedFiles('<?= $submission['assignment_id'] ?>', '<?= $submission['created_by'] ?>', '<?= $courseCode ?>')" class="btn btn-sm btn-dark"><i class="fa-solid fa-eye"></i></button>
                                    <button type="button" onclick="ChangeStatus('<?= $submission['assignment_id'] ?>', '<?= $submission['created_by'] ?>', '<?= $courseCode ?>', 0)" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                                <td><?= $submission['grade'] ?></td>
                                <td><?= $submission['grade_status'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#submission-table').dataTable()
</script>