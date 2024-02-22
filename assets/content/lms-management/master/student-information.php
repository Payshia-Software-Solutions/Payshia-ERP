<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include '../assets/lms_methods/d-pad-methods.php';
include '../assets/lms_methods/quiz_methods.php';
include '../assets/lms_methods/win-pharma-functions.php';

$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];
$displayStatusMessage = $transcriptStatusMessage = "Not Printed";
$certificateBadgeColor = $transcriptBadgeColor =  "danger";

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();
$cityList = GetCityList($link);
$districtList = getDistricts($link);
$studentEnrollments = getUserEnrollments($studentNumber);
$lmsStudents = GetLmsStudents();

$studentCount = count($lmsStudents);
$searchedStudent = $lmsStudents[$studentNumber];
$registeredDate = date("Y-m-d H:i:s", strtotime($searchedStudent['updated_at']));

$studentDefaultCourseCode =  GetDefaultCourseValue($studentNumber);
$dPadGrade =  OverallGradeDpad($studentNumber);
$quizGrade = GetOverallGrade($studentNumber, $studentDefaultCourseCode);

$balanceDetails = GetStudentBalance($studentNumber);
$dueBalance = $balanceDetails['studentBalance'];
$fromAddress = 'info@pharmacollege.lk';
$toEmailAddress = $searchedStudent['e_mail'];

$certificateId = 1;

$certificateStatusList = GetCertificatePrintStatus();
$transcriptStatusList = GetTranscriptPrintStatus();

$userCertificateStatus = 0;
$userTranscriptStatus = 0;

if (!empty($certificateStatusList)) {
    if (isset($certificateStatusList[$certificateId . '-' . $studentNumber])) {

        $userCertificateStatus = $certificateStatusList[$certificateId . '-' . $studentNumber]['print_status'];
    }
}


if (!empty($transcriptStatusList)) {
    if (isset($transcriptStatusList[$certificateId . '-' . $studentNumber])) {
        $userTranscriptStatus = $transcriptStatusList[$certificateId . '-' . $studentNumber]['print_status'];
    }
}


if ($userCertificateStatus == 1) {
    $displayStatusMessage = 'Printed';
    $certificateBadgeColor = "success";
}


if ($userTranscriptStatus == 1) {
    $transcriptStatusMessage = 'Printed';
    $transcriptBadgeColor = "success";
}
?>


<div class="row my-4">
    <div class="col-md-12 text-end mb-3 mb-md-5">
        <button type="button" onclick=" GetStudentInformation('<?= $studentNumber ?>')" class="btn btn-dark">
            <i class="fa-solid fa-rotate-right"></i> Reload
        </button>

        <button type="button" onclick="GetSearchPopUp()" class="btn btn-dark">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Due Balance</p>
                <h4 class="">LKR <?= number_format($dueBalance, 2) ?></h4>
                <div class="border-bottom my-2"></div>

                <div class="text-end">
                    <button onclick="MakeStudentPayment('<?= $studentNumber ?>')" type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-money-bill"></i> Payment</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-certificate icon-card"></i>
            </div>
            <div class="card-body">
                <h4 class="mb-0">Certificate Status</h4>
                <span class="badge bg-<?= $certificateBadgeColor ?>"><?= $displayStatusMessage ?></span>
                <div class="border-bottom my-2"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <h4 class="mb-0">Transcript Status</h4>
                <span class="badge bg-<?= $transcriptBadgeColor ?>"><?= $transcriptStatusMessage ?></span>
                <div class="border-bottom my-2"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-rectangle-list icon-card"></i>
            </div>
            <div class="card-body">
                <h4 class="mb-0">Courier Status</h4>
                <span class="badge bg-primary">Pending</span>
                <div class="border-bottom my-2"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-dark btn-sm"><i class="fa-solid fa-eye"></i> View Orders</button>
                </div>
            </div>
        </div>
    </div>



</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 d-flex">
        <button onclick="SendSmsPopup('<?= $searchedStudent['telephone_1'] ?>')" class="btn btn-dark w-100 flex-fill" type="button">
            <i class="fa-solid fa-2x fa-comment-sms mt-2"></i>
            <h4 class="fw-normal">Send SMS</h4>
        </button>
    </div>
    <div class="col-md-2 d-flex">
        <button onclick="SendEmailPopup('<?= $fromAddress ?>', '<?= $toEmailAddress ?>')" class="btn btn-dark w-100 flex-fill" type="button">
            <i class="fa-solid fa-2x fa-envelope mt-2"></i>
            <h4 class="fw-normal">Send Email</h4>
        </button>
    </div>
    <div class="col-md-2 d-flex">
        <button onclick="MakeStudentPayment('<?= $studentNumber ?>')" class="btn btn-dark w-100 flex-fill" type="button">
            <i class="fa-solid fa-2x fa-money-bills mt-2"></i>
            <h4 class="fw-normal">Payment</h4>
        </button>
    </div>
    <div class="col-md-2 d-flex">
        <button class="btn btn-dark w-100 flex-fill" type="button">
            <i class="fa-solid fa-2x fa-money-bills mt-2"></i>
            <h4 class="fw-normal">Enrollments</h4>
        </button>
    </div>
</div>


<div class="row g-3">
    <div class="col-md-8">

        <div class="table-title font-weight-bold mb-2 mt-0">Game Results</div>
        <div class="row mb-2">
            <div class="col-md-4 offset-md-8 text-end">
                <label class="text-secondary fw-bold">Choice Course</label>
                <select class="form-control" onchange="GetGameResults(this.value, '<?= $studentNumber ?>')">
                    <?php
                    if (!empty($studentEnrollments)) {
                        foreach ($studentEnrollments as $selectedArray) {
                            $courseCode = $selectedArray['course_code'];
                            $enrolledCourse = $CourseBatches[$courseCode];
                    ?>
                            <option value="<?= $courseCode ?>"><?= $courseCode ?> - <?= $CourseBatches[$courseCode]['course_name'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div id="gameResults"> </div>
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Student Details</div>
                <div class="row g-3">
                    <div class="col-4 col-md-4">
                        <label class="text-secondary">Civil Status</label>
                        <h5><?= $searchedStudent['civil_status'] ?></h5>
                    </div>

                    <div class="col-8 col-md-8">
                        <label class="text-secondary">Student Name</label>
                        <h5><?= $searchedStudent['first_name'] ?> <?= $searchedStudent['last_name'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">User Name</label>
                        <h5><?= $searchedStudent['username'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">User ID</label>
                        <h5><?= $searchedStudent['student_id'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">Email Address</label>
                        <h5><?= $searchedStudent['e_mail'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">Primary Number</label>
                        <h5><?= $searchedStudent['telephone_1'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">Gender</label>
                        <h5><?= $searchedStudent['gender'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">Registered Date</label>
                        <h5><?= $registeredDate ?></h5>
                    </div>




                    <div class="col-6 col-md-4">
                        <label class="text-secondary">Birth Day</label>
                        <h5><?= $searchedStudent['birth_day'] ?></h5>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="text-secondary">NIC</label>
                        <h5><?= $searchedStudent['nic'] ?></h5>
                    </div>


                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="table-title font-weight-bold">Contact Details</div>
                            </div>

                            <div class="col-md-8">
                                <label class="text-secondary">Address</label>
                                <h5><?= $searchedStudent['address_line_1'] ?>, <?= $searchedStudent['address_line_2'] ?>, <?= $cityList[$searchedStudent['city']]['name_en'] ?>, <?= $cityList[$searchedStudent['district']]['name_en'] ?>, <?= $searchedStudent['postal_code'] ?></h5>
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="text-secondary">Secondary Number</label>
                                <h5><?= $searchedStudent['telephone_2'] ?></h5>
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="table-title font-weight-bold">Certificate Details</div>
                            </div>

                            <div class="col-md-6">
                                <label class="text-secondary">Full Name</label>
                                <h5><?= $searchedStudent['full_name'] ?></h5>
                            </div>

                            <div class="col-md-6">
                                <label class="text-secondary">Name with Initials</label>
                                <h5><?= $searchedStudent['name_with_initials'] ?></h5>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary">Name on Certificate</label>
                                <h5><?= $searchedStudent['name_on_certificate'] ?></h5>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>

    <div class="col-md-4">

        <div class="table-title font-weight-bold mb-2 mt-0">Enrollments</div>
        <div class="row g-2">
            <?php
            if (!empty($studentEnrollments)) {
                foreach ($studentEnrollments as $selectedArray) {
                    $courseCode = $selectedArray['course_code'];
                    $enrolledCourse = $CourseBatches[$courseCode];
                    $enrolledCourseImg = $enrolledCourse['course_img'];

            ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-4">
                                    <img class="w-100 rounded-3" src="./assets/content/lms-management/assets/images/course-img/<?= $courseCode ?>/<?= $enrolledCourseImg ?>">
                                </div>
                                <div class="col-8">
                                    <h5 class="fw-bold mb-1"><?= $enrolledCourse['course_name'] ?></h5>
                                    <span class="btn btn-primary btn-sm"><?= $courseCode ?></span>
                                    <button onclick="ConfirmEnrollmentRemove('<?= $studentNumber ?>', '<?= $courseCode ?>')" type="button" class=" btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Remove Enrollment</button>

                                    <div class="border-bottom my-2"></div>

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="text-secondary">Certificate Status</div>
                                            <span class="badge bg-warning">Pending</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary">Courier Status</div>
                                            <span class="badge bg-success">Delivered</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

        </div>
    </div>
</div>

<script>
    GetGameResults('<?= $studentDefaultCourseCode ?>', '<?= $studentNumber ?>');
</script>