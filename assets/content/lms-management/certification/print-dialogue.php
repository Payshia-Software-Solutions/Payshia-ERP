<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include '../assets/lms_methods/d-pad-methods.php';
include '../assets/lms_methods/quiz_methods.php';
include '../assets/lms_methods/win-pharma-functions.php';
include '../assets/lms_methods/pharma-hunter-methods.php';
include '../assets/lms_methods/pharma-reader-methods.php';


$indexNumber = $_POST['studentNumber'];
$studentBatch = $_POST['studentBatch'];

$CourseAssignments = GetAssignments($studentBatch);
$assignmentSubmissions = GetAssignmentSubmissionsByUser($indexNumber);

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$certificateTemplates = GetCertificateTemplates();
$certificateDefaults = GetTemplateConfig($studentBatch);

$defaultTemplateId = $issuedDate = '';
if (isset($certificateDefaults[$studentBatch])) {
    $defaultTemplateId = $certificateDefaults[$studentBatch]['defaultTemplate'];
    $issuedDate = $certificateDefaults[$studentBatch]['CompleteDate'];
}

$batchStudents =  GetLmsStudents();
$studentDetailsArray = $batchStudents[$indexNumber];

$paymentInfo = GetStudentBalance($indexNumber);

$certificateStatus = $transcriptStatus =  $workshopStatus = "";
$certificatePrintStatus = $transcriptPrintStatus = $workshopPrintStatus = "Not Printed";
$certificateBackground = $transcriptBackground = $workshopBackground = "danger";
$certification = CertificatePrintStatusByCourseStudent($studentBatch, $indexNumber);
if (!empty($certification)) {
    if (isset($certification['Transcript'])) {
        $transcriptStatus = $certification['Transcript'];
        $transcriptId = $transcriptStatus['certificate_id'];
        $printDate = $transcriptStatus['print_date'];
        $printStatus = $transcriptStatus['print_status'];
        $print_by = $transcriptStatus['print_by'];
        $type = $transcriptStatus['type'];
        $course_code = $transcriptStatus['course_code'];

        $transcriptPrintStatus = "Printed";
        $transcriptBackground = "success";
    }

    if (isset($certification['Certificate'])) {
        $certificateStatus = $certification['Certificate'];
        $certificateId = $certificateStatus['certificate_id'];
        $printDate = $certificateStatus['print_date'];
        $printStatus = $certificateStatus['print_status'];
        $print_by = $certificateStatus['print_by'];
        $type = $certificateStatus['type'];
        $course_code = $certificateStatus['course_code'];

        $certificatePrintStatus = "Printed";
        $certificateBackground = "success";
    }

    if (isset($certification['Workshop-Certificate'])) {
        $workshopStatus = $certification['Workshop-Certificate'];
        $certificateId = $workshopStatus['certificate_id'];
        $printDate = $workshopStatus['print_date'];
        $printStatus = $workshopStatus['print_status'];
        $print_by = $workshopStatus['print_by'];
        $type = $workshopStatus['type'];
        $course_code = $workshopStatus['course_code'];

        $workshopPrintStatus = "Printed";
        $workshopBackground = "success";
    }
}




// Game Results 

$studentNumber = $indexNumber;
$LoggedUser = $indexNumber;
$selectedCourseCode = $studentBatch;

$dPadGrade =  OverallGradeDpad($studentNumber);
$quizGrade = GetOverallGrade($studentNumber, $selectedCourseCode);

// WinPharma
$winpharmaCurrentTopLevel = GetTopLevel($lms_link, $studentNumber, $selectedCourseCode);
if ($winpharmaCurrentTopLevel == -1) {
    $winpharmaCurrentTopLevel = GetCourseTopLevel($lms_link, $selectedCourseCode);
}
if ($winpharmaCurrentTopLevel == -1) {
    $winpharmaCurrentTopLevel = 1;
}

$winPharmaLevels = GetLevels($lms_link, $selectedCourseCode);
$getSubmissionLevelCount = GetSubmissionLevelCount($indexNumber, $selectedCourseCode);
$winPharmaLevelCount = count($winPharmaLevels);
if ($winPharmaLevelCount > 0) {
    $winPharmaPercentage = ($getSubmissionLevelCount / $winPharmaLevelCount) * 100;
} else {
    $winPharmaPercentage = 0;
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
$ceylonPharmacyGrade = 0;
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>

    </div>

    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0">Certification Details</h5>
            <p class="border-bottom pb-2"></p>

            <form id="print-certificate-form">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Index Number</p>
                                <h5 class="mb-0"><?= $indexNumber ?></h5>
                            </div>

                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Student Name</p>
                                <h5 class="mb-0"><?= $studentDetailsArray['first_name'] ?> <?= $studentDetailsArray['last_name'] ?></h5>
                            </div>

                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Student Balance</p>
                                <h5 class="mb-0"><?= number_format($paymentInfo['studentBalance'], 2) ?></h5>
                            </div>

                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Image Status</p>
                                <select class="form-control" name="backImageStatus" id="backImageStatus">
                                    <option value="0">Without Background Image</option>
                                    <option value="1">With Background Image</option>
                                </select>
                            </div>

                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-8">
                                <p class="mb-0 text-secondary">Certificate Template</p>
                                <select class="form-control form-control-sm" name="certificateTemplate" id="certificateTemplate" required>
                                    <?php
                                    if (!empty($certificateTemplates)) {
                                        foreach ($certificateTemplates as $selectedTemplate) {
                                    ?>
                                            <option <?= ($defaultTemplateId == $selectedTemplate['template_id']) ? 'selected' : '' ?> value="<?= $selectedTemplate['template_id'] ?>"><?= $selectedTemplate['template_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Issue Date</p>
                                <input class="form-control" type="date" name="issuedDate" id="issuedDate" value="<?= $issuedDate ?>" required>
                            </div>



                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Certificate Status</p>
                                <h5 class="mb-0"><span class="badge bg-<?= $certificateBackground ?>"><?= $certificatePrintStatus ?></span></h5>
                            </div>

                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Transcript Status</p>
                                <h5 class="mb-0"><span class="badge bg-<?= $transcriptBackground ?>"><?= $transcriptPrintStatus ?></span></h5>
                            </div>
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Workshop Status</p>
                                <h5 class="mb-0"><span class="badge bg-<?= $workshopBackground ?>"><?= $workshopPrintStatus ?></span></h5>
                            </div>

                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-secondary">Name on Certificate</p>
                                <h5 class="mb-0"><?= $studentDetailsArray['name_on_certificate'] ?></h5>
                            </div>

                            <div class="col-12 text-end">
                                <button onclick="OpenTranscriptDataEntry('<?= $studentNumber ?>', '<?= $studentBatch ?>')" class="btn btn-light" type="button"><i class="fa-solid fa-file-lines"></i> Update Transcript</button>
                                <button class="btn btn-light" type="button" onclick="OpenEditProfile('<?= $studentNumber ?>', '<?= $studentBatch ?>')"><i class="fa-solid fa-pencil"></i> Edit Profile</button>
                            </div>
                        </div>
                        <hr>

                        <div class="row g-2">
                            <div class="col-12">
                                <h6 class="mb-0">Assignment Details</h6>
                            </div>
                            <?php
                            if (!empty($CourseAssignments)) {
                                foreach ($CourseAssignments as $selectedAssignment) {

                                    $assignmentId = $selectedAssignment['assignment_id'];
                            ?>
                                    <div class="col-6 col-md-4">
                                        <p class="mb-0 text-secondary"><?= $assignmentId ?></p>
                                        <h5 class="mb-0"><?= number_format($assignmentSubmissions[$assignmentId]['grade'], 2) ?>%</h5>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="col-12">
                                    <p class="text-secondary">No Assignments</p>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-12">
                                <div class="border-bottom mb-2"></div>
                            </div>
                        </div>

                        <div class="row g-2">

                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Quiz</p>
                                <h5 class="mb-0"><?= number_format($quizGrade, 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Winpharma</p>
                                <h5 class="mb-0"><?= number_format($winPharmaPercentage, 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Dpad</p>
                                <h5 class="mb-0"><?= number_format($dPadGrade['overallGrade'], 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Ceylon Pharmacy</p>
                                <h5 class="mb-0"><?= number_format($ceylonPharmacyGrade, 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Pharma hunter</p>
                                <h5 class="mb-0"><?= number_format($pharmaHunterGrade, 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Hunter Pro</p>
                                <h5 class="mb-0"><?= number_format($pharmaHunterGrade, 2) ?>%</h5>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="mb-0 text-secondary">Pharma Reader</p>
                                <h5 class="mb-0"><?= number_format($pharmaReaderGrade, 2) ?>%</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="button" onclick="PrintCertificate('<?= $indexNumber ?>', '<?= $studentBatch ?>')" class="btn btn-dark rounded-3 w-100 p-2 mb-2">
                            <i class="fa-solid fa-print fa-2x mt-2"></i>
                            <h5>Print Certificate</h5>
                        </button>
                        <button type="button" onclick="PrintTranscript('<?= $indexNumber ?>', '<?= $studentBatch ?>')" class="btn btn-secondary rounded-3 w-100 p-2 mb-2">
                            <i class="fa-solid fa-print fa-2x mt-2"></i>
                            <h5>Print Transcript</h5>
                        </button>
                        <button type="button" onclick="PrintWorkshop('<?= $indexNumber ?>', '<?= $studentBatch ?>')" class="btn btn-success rounded-3 w-100 p-2">
                            <i class="fa-solid fa-print fa-2x mt-2"></i>
                            <h5>Print Workshop</h5>
                        </button>
                    </div>



                </div>
            </form>
        </div>



    </div>



</div>