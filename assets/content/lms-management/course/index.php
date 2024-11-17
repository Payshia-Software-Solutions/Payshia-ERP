<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Parameters
$studentBatch = $_POST['studentBatch'];
$orderType = $_POST['orderType'];
$LoggedUser = $_POST['LoggedUser'];
$defaultLocation = $_POST['defaultLocation'];

$Locations = GetLocations($link);
$Deliveries = GetDeliverySetting();
$mainCourseList = GetMainCourseList();
$CourseBatches = getLmsBatches();

$ArrayCount = count($mainCourseList);
$ActiveCount = $ArrayCount;
?>

<div class="row mt-5 mb-4">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-award icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Courses</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-9 text-end mt-4 mt-md-0">
        <button class="btn btn-dark rounded-2" type="button" onclick="AddNewCourse()"><i class="fa-solid fa-plus"></i> New Course</button>
        <button class="btn btn-dark rounded-2" type="button" onclick="OpenGrading()"><i class="fa-solid fa-worm"></i> Grading</button>
    </div>
</div>


<div class="row">
    <div class="col-md-4">
        <div class="row g-3">
            <div class="col-12">
                <div class="table-title font-weight-bold mt-0">Course List</div>
            </div>
            <?php
            if (!empty($mainCourseList)) {
                foreach ($mainCourseList as $SelectArray) {
                    $active_status = "Deleted";
                    $color = "warning";
                    if ($SelectArray['is_active'] == 1) {
                        $active_status = "Active";
                        $color = "primary";
                    }
            ?>
                    <div class="col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body p-2 pb-2">
                                <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                                <h1 class="tutor-name mt-2 mb-0"><?= $SelectArray['course_name'] ?></h1>
                                <p><?= $SelectArray['course_code'] ?></p>
                                <div class="text-end mt-3">
                                    <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="AddNewCourse ('<?= $SelectArray['course_code'] ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>

                                    <?php
                                    if ($SelectArray['is_active'] == 1) {
                                        $active_status = "Active";
                                        $color = "primary";
                                    ?>
                                        <button class="mt-0 mb-1 btn btn-sm btn-secondary view-button" type="button" onclick="ChangeStatus(0, '<?= $SelectArray['id'] ?>')"><i class="fa-solid fa-ban"></i> Disable</button>
                                    <?php
                                    } else {
                                    ?>
                                        <button class="mt-0 mb-1 btn btn-sm btn-primary view-button" type="button" onclick="ChangeStatus(1, '<?= $SelectArray['id'] ?>')"><i class="fa-solid fa-check"></i> Active</button>
                                    <?php
                                    }
                                    ?>

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
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-12">
                <div class="table-title font-weight-bold mt-0">Batches List</div>
            </div>
            <?php
            if (!empty($CourseBatches)) {
                foreach ($CourseBatches as $SelectArray) {

                    $studentBatch = $SelectArray['course_code'];
                    $CourseAssignments = GetAssignments($studentBatch);
            ?>
                    <div class="col-lg-4 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body p-2 pb-2">
                                <span class="badge mt-2 bg-dark"><?= $studentBatch ?></span>
                                <h1 class="border-bottom pb-2 tutor-name mt-2"><?= $SelectArray['course_name'] ?></h1>
                                <div class="row g-2">

                                    <div class="col-9">
                                        Winpharma Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('winpharma-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>

                                    <div class="col-9">
                                        Quiz Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('quiz-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>

                                    <div class="col-9">
                                        D Pad Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('d-pad-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>

                                    <div class="col-9">
                                        Pharma Hunter Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('pharma-hunter-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>


                                    <div class="col-9">
                                        Ceylon Pharmacy Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('ceylon-pharmacy-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>

                                    <div class="col-9">
                                        Pharma Reader Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('pharma-reader-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>

                                    <div class="col-9">
                                        Assignment Report
                                    </div>
                                    <div class="col-3">
                                        <button class="mt-0 mb-1 btn btn-sm btn-light view-button w-100" type="button" onclick="PrintGameReport ('assignment-report', '<?= $studentBatch ?>', 'all', '<?= $defaultLocation ?>')"><i class="fa-solid fa-print"></i></button>
                                    </div>




                                    <div class="col-md-12">
                                        <button class="mt-0 mb-1 btn btn-sm btn-dark view-button w-100" type="button" onclick="EditGradesByCourse ('<?= $studentBatch ?>')"><i class="fa-solid fa-pen-to-square"></i> Edit Grading</button>

                                        <button class="mt-0 mb-1 btn btn-sm btn-dark view-button w-100" type="button" onclick="ViewGradesByCourse ('<?= $studentBatch ?>')"><i class="fa-solid fa-pen-to-square"></i> View Grading</button>
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