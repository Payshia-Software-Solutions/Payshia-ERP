<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Parameters
$courseCode = $_POST['studentBatch'];
$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];


$CourseBatches = getLmsBatches();
$selectedCourse = $CourseBatches[$courseCode];

$courseName = $selectedCourse['course_name'];
$courseImage = $selectedCourse['course_img'];

$lmsStudents = GetLmsStudents();
$selectedStudent = $lmsStudents[$studentNumber];
?>

<div class="loading-popup-content">

    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <h4 class="mb-0 fw-bold">Do you really need to remove Enrollment ?</h4>
            <p>If you need to proceed the transaction please confirm!</p>

            <div class="bg-light rounded-3 p-3 border-2">
                <h5><?= $selectedStudent['first_name'] ?> <?= $selectedStudent['last_name'] ?></h5>
                <h5 class="fw-bold mb-1">
                    <span class="badge bg-primary"><?= $courseCode ?></span> <?= $courseName ?>
                </h5>
            </div>
        </div>


        <div class="col-12 text-end mt-3">
            <button type="button" class="btn btn-light"><i class="fa-solid fa-xmark"></i> No</button>
            <button type="button" class="btn btn-dark"><i class="fa-solid fa-check"></i> Yes</button>
        </div>
    </div>

</div>