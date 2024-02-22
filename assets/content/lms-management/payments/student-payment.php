<?php

require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Define Variables
$studentBalance = 0;

$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$CourseBatches = getLmsBatches();

$studentBalance = GetStudentBalance($studentNumber)['studentBalance'];
$lmsStudents = GetLmsStudents();
$studentEnrollments = getUserEnrollments($studentNumber);
$selectedStudent = $lmsStudents[$studentNumber];

$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"],
    ["id" => "4", "text" => "Bank Transfer"]
];


?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 pb-2 border-bottom fw-bold">Student Information</h5>
        </div>

        <div class="col-12 text-center">
            <p class="mb-0">Due Balance for <?= $studentNumber ?></p>
            <h2 class="mb-0 fw-bold"><?= number_format($studentBalance, 2) ?></h2>
        </div>
    </div>


    <div class="p-3 border border-2 bg-light rounded-4 mt-4" id="product-selector">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-control" name="courseCode" id="courseCode">
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
                    <label class="form-label">Select Course</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-control" name="paymentType" id="paymentType" required autocomplete="off" onchange="goToValue(this.value)">
                        <option value="">Select Payment Method</option>
                        <?php
                        if (!empty($PaymentTypes)) {
                            foreach ($PaymentTypes as $SelectedArray) {
                        ?>
                                <option value="<?= $SelectedArray['id'] ?>"><?= $SelectedArray['text'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <label id="paymentType">Select Payment Method</label>
                </div>
            </div>

            <div class="col-6 col-md-6" id="amountSet">
                <div class="form-floating">
                    <input onclick="this.select()" value="0" type="number" step="0.01" onchange="" class="form-control text-end" name="discountAmount" id="discountAmount" placeholder="0.0">
                    <label for="discountAmount">Discount Amount</label>
                </div>
            </div>

            <div class="col-6 col-md-6" id="amountSet">
                <div class="form-floating">
                    <input onclick="this.select()" type="number" value="<?= $studentBalance ?>" step="0.01" onchange="" class="form-control text-end" name="payment_amount" id="payment_amount" placeholder="0.0">
                    <label for="payment_amount">Payment Amount</label>
                </div>
            </div>



            <div class="col-md-12 text-end">
                <button type="button" onclick="ProceedStudentPayment('<?= $studentNumber ?>')" class="btn btn-dark">Proceed Payment</button>
            </div>
        </div>
    </div>
</div>