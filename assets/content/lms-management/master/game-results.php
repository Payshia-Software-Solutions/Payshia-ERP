<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include '../assets/lms_methods/d-pad-methods.php';
include '../assets/lms_methods/quiz_methods.php';
include '../assets/lms_methods/win-pharma-functions.php';
include '../assets/lms_methods/pharma-hunter-methods.php';
include '../assets/lms_methods/pharma-reader-methods.php';

$pharmaHunterGrade = 0;


$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['selectedStudent'];
$selectedCourseCode = $_POST['selectedCourseCode'];

$CourseBatches = getLmsBatches();
$lmsStudents = GetLmsStudents();

$dPadGrade =  OverallGradeDpad($studentNumber);
$quizGrade = GetOverallGrade($studentNumber, $selectedCourseCode);
$winpharmaCurrentTopLevel = GetTopLevel($lms_link, $studentNumber, $selectedCourseCode);
if ($winpharmaCurrentTopLevel == -1) {
    $winpharmaCurrentTopLevel = GetCourseTopLevel($lms_link, $selectedCourseCode);
}
if ($winpharmaCurrentTopLevel == -1) {
    $winpharmaCurrentTopLevel = 1;
}


// Pharma Hunter 
$Medicines = GetProMedicines($lms_link, $selectedCourseCode);
$MedicineCount = count($Medicines);
$Score = GetAttemptResult($lms_link, $LoggedUser);
$AttemptCount = GetHunterProAttemptCount($lms_link, $LoggedUser);
$CountAnswer = GetHunterProAttempts($lms_link);

// Create Score
$TotalScore = $AttemptCount * $CountAnswer * 4; //4 Selections 10 for each
$pharmaHunterGrade = ($TotalScore > 0) ? ($Score / $TotalScore) * 100 : 0;

$AttemptRate = $AttemptCount / $CountAnswer;
if ($AttemptRate > $MedicineCount) {
    $AttemptRate = $MedicineCount;
}


// Pharma Reader
$pharmaReaderGradeArray = GetReaderOverallGrade($LoggedUser);
$pharmaReaderGrade = $pharmaReaderGradeArray['overallGrade'];
?>

<div class="row g-3">
    <div class="col-12">
        <div class="bg-white rounded-3 p-3 shadow-sm">
            <h5 class="mb-0">Game Results for <?= $selectedCourseCode ?> - <?= $CourseBatches[$selectedCourseCode]['course_name'] ?></h5>
        </div>
    </div>
    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/question.gif" class="game-icon w-25">
                <h5 class="mb-0">Quiz</h5>
                <h2 class="mb-0 fw-bold"><?= number_format($quizGrade, 2) ?>%</h2>
            </div>
        </div>
    </div>


    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/drugs.gif" class="game-icon w-25">
                <h5 class="mb-0">Winpharma</h5>
                <h2 class="mb-0 fw-bold">Level <?= $winpharmaCurrentTopLevel ?></h2>
            </div>
        </div>
    </div>


    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/pill.gif" class="game-icon w-25">
                <h5 class="mb-0">D-Pad</h5>
                <h2 class="mb-0 fw-bold"><?= number_format($dPadGrade['overallGrade'], 2) ?>%</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/pharmacy.gif" class="game-icon w-25">
                <h5 class="mb-0">Ceylon Pharmacy</h5>
                <h2 class="mb-0 fw-bold">-</h2>
            </div>
        </div>
    </div>


    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/medicine.gif" class="game-icon w-25">
                <h5 class="mb-0">Pharma Hunter</h5>
                <h2 class="mb-0 fw-bold"><?= $pharmaHunterGrade ?>%</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <img src="./assets/content/lms-management/assets/images/icons/alternative-medicine.gif" class="game-icon w-25">
                <h5 class="mb-0">Pharma Reader</h5>
                <h2 class="mb-0 fw-bold"><?= $pharmaReaderGrade ?>%</h2>
            </div>
        </div>
    </div>


</div>