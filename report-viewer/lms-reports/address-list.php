<?php
require_once('../../include/config.php');
include '../../include/function-update.php';
include '../../include/finance-functions.php';
include '../../include/reporting-functions.php';
include '../../include/lms-functions.php';

$Locations = GetLocations($link);
$studentBatch = $_GET['studentBatch'];
$default_location = $_GET['default_location'];
$location_name = $Locations[$default_location]['location_name'];
$AllStudents = GetLmsStudents();
$cities = GetCities($link);
$districtsList = getDistricts($link);
$studentBathes = getLmsBatches();
$allUserEnrollments =  getAllUserEnrollments();

$pageTitle = "Address List";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:title" content="<?= $pageTitle ?>" />
    <meta property="og:description" content="Address List Report for <?= $location_name ?>" />
    <meta property="og:image" content="https://admin.pharmacollege.lk/assets/images/favicon/apple-touch-icon.png" />
    <meta property="og:url" content="https://admin.pharmacollege.lk/report-viewer/lms-reports/address-list?default_location= <?= $default_location ?>&studentBatch= <?= $studentBatch ?>" />

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/report-viewer.css">
    <link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css" />
    <title><?= $pageTitle ?></title>

    <style>
        @media print {
            .page-break {
                page-break-inside: avoid;
            }

        }
    </style>
</head>

<body>

    <div class="invoice" style="padding: 50px;">
        <div class="row g-1">
            <?php
            if (!empty($AllStudents)) {
                foreach ($AllStudents as $selectedArray) {

                    $enrollmentArray = array();
                    $cityName = $districtName =  $postalCode =  $districtId = "";

                    $studentId = $selectedArray['student_id'];
                    $indexNumber = $selectedArray['username'];
                    $studentName = ucwords($selectedArray['first_name'] . " " . $selectedArray['last_name']);
                    $addressL1 = $selectedArray['address_line_1'];
                    $addressL2 = $selectedArray['address_line_2'];
                    $city = $selectedArray['city'];
                    $telephone_1 = $selectedArray['telephone_1'];

                    if (isset($cities[$city]['name_en'])) {
                        $cityName = $cities[$city]['name_en'];
                        $postalCode = $cities[$city]['postcode'];
                        $districtId = $cities[$city]['district_id'];
                    }

                    if (isset($districtsList[$districtId]['name_en'])) {
                        $districtName = $districtsList[$districtId]['name_en'];
                    }

                    // Use array_filter to filter out elements with the specified student_id
                    $enrollmentArray = array_filter($allUserEnrollments, function ($item) use ($studentId) {
                        return $item['student_id'] == $studentId;
                    });

                    if ($studentBatch != "0") {
                        $courseAvailability = array_filter($enrollmentArray, function ($item) use ($studentBatch) {
                            return $item['course_code'] == $studentBatch;
                        });


                        $conditionSatisfied = false;
                        if (empty($courseAvailability)) {
                            $conditionSatisfied = true;
                        }

                        if ($conditionSatisfied) {
                            continue; // Continue the main loop when the condition is satisfied
                        }
                    }

            ?>
                    <div class="col-4 d-flex">
                        <div class="card rounded-0 flex-fill page-break" style="max-height: 45mm;">
                            <div class="card-body p-2">
                                <h6 class="mb-0" style="font-weight: 600;"><?= $studentName ?></h6>
                                <p class="mb-0"><?= $addressL1 ?>,</p>
                                <p class="mb-0"><?= $addressL2 ?>,</p>
                                <p class="mb-0"><?= $cityName ?>, <?= $postalCode ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

        </div>
    </div>
</body>

</html>