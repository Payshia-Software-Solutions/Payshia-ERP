<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$LoggedUser = $_POST['LoggedUser'];

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();
$studentEnrollCounts = GetStudentEnrollCounts();

$studentCount = count($accountDetails);
$templateCount = 5;
?>

<div class="row mt-5">

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Students</p>
                <h1><?= $studentCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-certificate icon-card"></i>
            </div>
            <div class="card-body">
                <p>Templates</p>
                <h1><?= $templateCount ?></h1>
            </div>
        </div>
    </div>



</div>

<div class="row g-3">

    <div class="col-12">
        <h5 class="table-title">Certification Operations</h5>
    </div>

    <div class="col-md-8">
        <div class="row g-3">
            <?php
            if (!empty($CourseBatches)) {
                foreach ($CourseBatches as $SelectArray) {
                    $selectedCourseCode = $SelectArray['course_code'];
                    $courseImage = $SelectArray['course_img'];

                    $certificateStatusByCourse = CertificatePrintStatusByCourse($selectedCourseCode, 'Certificate');
                    $certificatePrintCount = count($certificateStatusByCourse);
                    $transcriptPrintCount =  count(CertificatePrintStatusByCourse($selectedCourseCode, 'Transcript'));
                    $workshopCertificateCount =  count(CertificatePrintStatusByCourse($selectedCourseCode, 'Workshop-Certificate'));
            ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-10">
                                        <h5 class="border-bottom pb-2"><?= $SelectArray['course_name'] ?> - <?= $selectedCourseCode ?></h5>
                                        <div class="row g-2">
                                            <div class="col-6 col-md-3">
                                                <h6 class="text-secondary mb-0">Enrollments</h6>
                                                <h3 class="mb-0"><?= $studentEnrollCounts[$selectedCourseCode] ?></h3>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <h6 class="text-secondary mb-0">Certificate Printed</h6>
                                                <h3 class="mb-0"><?= $certificatePrintCount ?></h3>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <h6 class="text-secondary mb-0">Transcript Printed</h6>
                                                <h3 class="mb-0"><?= $transcriptPrintCount ?></h3>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <h6 class="text-secondary mb-0">Workshop Printed</h6>
                                                <h3 class="mb-0"><?= $workshopCertificateCount ?></h3>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button type="button" onclick=" GetCertificationPage('<?= $selectedCourseCode ?>')" class="btn btn-dark btn-sm"><i class="fa-solid fa-eye"></i> View Detail</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-none d-md-block">
                                        <img class="w-100 rounded-3" src="./assets/content/lms-management/assets/images/course-img/<?= $selectedCourseCode ?>/<?= $courseImage ?>">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">No Entires</p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>


</div>