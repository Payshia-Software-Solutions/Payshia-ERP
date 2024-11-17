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
$indexNumber = $_POST['indexNumber'];

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
$submissionInfo = $submissions->fetchUserByAssignmentId($assignmentId, $indexNumber);

function displaySubmissionContent($file_name, $assignmentId, $username)
{
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    // $file_path = "http://localhost/mobile.pharmacollege.lk/uploads/assignment-submissions/" . $assignmentId . "/" . $username . "/" . $file_name; // Update this path as needed
    $file_path = "https://lms.pharmacollege.lk/uploads/assignment-submissions/" . $assignmentId . "/" . $username . "/" . $file_name; // Update this path as needed
    switch ($file_extension) {
        case 'pdf':
            return "<embed src='$file_path' width='100%' height='800px' type='application/pdf'>";
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            return "<img src='$file_path' alt='Assignment Image' class='w-100 rounded-4'>";
        case 'mp4':
            return "<video width='100%' controls><source src='$file_path' type='video/mp4'>Your browser does not support the video tag.</video>";
        default:
            return "<a href='$file_path' download>Download Assignment</a>";
    }
}
?>

<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?> ">

    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Assignment Details</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenSubmittedFiles( '<?= $assignmentId ?>', '<?= $indexNumber ?>', '<?= $courseCode ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php
            foreach ($submissionInfo as $submission) :
                $fileList = $submission['file_list'];
                $fileList = explode(',', $fileList);
            ?>

                <div class="alert alert-warning">Submitted at <?= $submission['created_at'] ?></div>
                <div class="row g-2">
                    <?php foreach ($fileList as $file) :
                        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        // $file_path = "http://localhost/mobile.pharmacollege.lk/uploads/assignment-submissions/" . $assignmentId . "/" . $submission['created_by'] . "/" . $file;
                        $file_path = "https://lms.pharmacollege.lk/uploads/assignment-submissions/" . $assignmentId . "/" . $submission['created_by'] . "/" . $file;

                    ?>
                        <?php if ($file_extension == 'pdf') : ?>
                            <div class="col-12">
                                <?= displaySubmissionContent($file, $assignmentId, $submission['created_by']); ?>
                                <a class="btn btn-light btn-sm w-100 my-2" href="<?= $file_path ?>" target="_blank">Download</a>
                            </div>
                        <?php else : ?>
                            <div class="col-6"><?= displaySubmissionContent($file, $assignmentId, $submission['created_by']); ?>
                                <a class="btn btn-light btn-sm w-100 my-2" href="<?= $file_path ?>" target="_blank">Open</a>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>

            <?php endforeach; ?>

        </div>
        <div class="col-md-4">
            <form action="#" id="grade-form" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="student_number" name="student_number" placeholder="" readonly value="<?= $indexNumber ?>">
                            <label for="floatingInput">Student Number</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="grade_value" name="grade_value" placeholder="100" required value="<?= $submission['grade'] ?>">
                            <label for="floatingInput">Grade</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button onclick="SaveGrade('<?= $courseCode ?>', '<?= $assignmentId ?>', 1)" type="button" class="btn btn-dark btn-lg w-100"><i class="fa-solid fa-floppy-disk"></i> Save Grade</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="my-5"></div>