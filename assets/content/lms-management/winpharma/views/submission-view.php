<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);

include_once '../classes/LmsDatabase.php';
include_once '../classes/Submissions.php';
include_once '../classes/Levels.php';
include_once '../classes/Tasks.php';
include_once '../classes/WinpharmaReasons.php';

$submissionId = $_POST['submissionId'];
$LoggedUser = $_POST['LoggedUser'];
$requestStatus = $_POST['requestStatus'];
// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$submissions = new Submissions($db);
$level = new Levels($db);
$Tasks = new Tasks($db);
$WinpharmaReasons = new WinpharmaReasons($db);

$submission = $submissions->fetchById($submissionId);
$reasonList = $WinpharmaReasons->fetchAll();

$defaultCourse = $submission['course_code'];
$Levels = $level->getLevels($defaultCourse);
$levelId = $submission["level_id"];

$taskList = $Tasks->GetTasks($levelId);

$resourceID = $submission["resource_id"];

$levelInfo = $Levels[$levelId];
$taskInfo = $taskList[$resourceID];
$submit_file = $submission["submission"];
$file_extension = strtolower(pathinfo($submit_file, PATHINFO_EXTENSION));
?>


<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Submission Details</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenSubmission('<?= $submissionId ?>', '<?= $requestStatus ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-md-8">


            <?php
            $rootFolder = 'http://localhost/mobile.pharmacollege.lk';
            $rootFolder = 'http://lms.pharmacollege.lk';

            if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'webp') { ?>

                <!-- Preview Submitted File -->
                <img class="rounded-4 shadow-sm w-100" id="myImage" src="<?= $rootFolder ?>/uploads/tasks/submission/<?= $submission["index_number"] ?>/<?= $submission["resource_id"]  ?>/<?= $submission["submission"] ?>">

                <!-- Rotate Button -->
                <button type="button" class="btn btn-primary rounded-2 mt-3 d-block" onclick="rotateImage()">Rotate Image</button>

                <!-- Download Button -->
                <a class="d-block" target="_blank" href="<?= $rootFolder ?>/uploads/tasks/submission/<?= $submission["index_number"] ?>/<?= $submission["resource_id"]  ?>/<?= $submission["submission"] ?>">
                    <button class="btn btn-warning rounded-2 mt-3">Download <?= $submission["submission"] ?></button>
                </a>

            <?php
            } else { ?>
                <p>
                    <a target="_blank" href="<?= $rootFolder ?>/uploads/tasks/submission/<?= $submission["index_number"] ?>/<?= $submission["resource_id"]  ?>/<?= $submission["submission"] ?>"><?= $submission["submission"] ?></a>
                </p>
            <?php
            }
            ?>
        </div>

        <div class="col-md-4">
            <h5 class="">Submission for <?= $levelInfo["level_name"] ?> & <?= $taskInfo["resource_title"] ?> of <?= $submission["index_number"] ?></h5>

            <form id="grade-form" method="post">
                <h4 class="card-title border-bottom pb-2 mb-2">Grading</h4>
                <div class="row g-2">

                    <div class="col-12">
                        <label>Current Grade Status: </label>
                        <?php if ($submission["grade_status"] != "Pending") : ?>
                            <div class="badge bg-primary rounded-2"><?= $submission["grade_status"] ?></div>
                            <div class="badge bg-success rounded-2"><?= $submission["grade"] ?>%</div>
                        <?php else : ?>
                            <div class="badge bg-danger rounded-2">Not Graded</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label>Grade Status</label>
                        <select onchange="ChangeGradeValue()" name="grade_status" id="grade_status" class="form-control form-control-sm" required>
                            <option <?= ($submission["grade_status"] == "Completed") ? 'selected' : '' ?> value="Completed">Completed</option>
                            <option <?= ($submission["grade_status"] == "Pending") ? '' : '' ?> value="Pending">Pending</option>
                            <option <?= ($submission["grade_status"] == "Sp-Pending") ? 'selected' : '' ?> value="Sp-Pending">Sp-Pending</option>
                            <option <?= ($submission["grade_status"] == "Try Again") ? 'selected' : '' ?> value="Try Again">Try Again</option>
                            <option <?= ($submission["grade_status"] == "Re-Correction") ? 'selected' : '' ?> value="Re-Correction">Re-Correction</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Grade Value %</label>
                        <input name="grade" id="grade" type="number" class="form-control form-control-sm" placeholder="85%" required value="<?= $submission["grade"] ?>">
                    </div>

                    <div class="col-md-12">
                        <label>Common Reason</label>
                        <select class="form-control" id="pre-reason" name="pre-reason">
                            <option value="">Select Common Reason</option>
                            <?php if (!empty($reasonList)) : ?>
                                <?php foreach ($reasonList as $reason) : ?>
                                    <option value="<?= $reason['reason'] ?>"><?= $reason['reason'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>

                    </div>

                    <div class="col-md-12">
                        <label>Reason</label>
                        <input name="reason" id="reason" type="text" class="form-control form-control-sm" placeholder="Reason" value="<?= $submission["reason"] ?>">
                    </div>
                    <div class="col-6 text-end mt-3 d-flex">
                        <button onclick="ViewResource('<?= $resourceID ?>')" type="button" class="btn btn-success btn-lg w-100 flex-fill"><i class="fa-solid fa-eye"></i> View Resource</button>
                    </div>

                    <div class="col-6 text-end mt-3 d-flex">
                        <button type="button" class="btn btn-lg btn-dark rounded-2 text-white flex-fill w-100" onclick="SaveGrade('<?= $submissionId ?>', '<?= $defaultCourse ?>', '<?= $requestStatus ?>') "><i class="fa-solid fa-floppy-disk"></i> Save Grade</button>
                    </div>

                </div>
            </form>




        </div>
    </div>
</div>


<script>
    function ChangeGradeValue() {
        const selectBox = document.getElementById('grade_status');
        const gradeIdInput = document.getElementById('grade');
        const selectedOption = selectBox.value;

        if (selectedOption === 'Pending') {
            gradeIdInput.value = '0';
        } else if (selectedOption === 'Completed') {
            gradeIdInput.value = '100';
        } else if (selectedOption === 'Not Completed') {
            gradeIdInput.value = '0';
        } else if (selectedOption === 'Try Again') {
            gradeIdInput.value = '-10';
        } else if (selectedOption === 'Sp-Pending') {
            gradeIdInput.value = '0';
        } else if (selectedOption === 'Re-Correction') {
            gradeIdInput.value = '0';
        }
    }

    var angle = 0;
    var img = document.getElementById('myImage');

    function rotateImage() {
        angle += 90;
        img.style.transform = 'rotate(' + angle + 'deg)';
    }

    $('#grade_status').select2();
    $('#pre-reason').select2();
</script>