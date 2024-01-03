<?php
require_once('../../include/config.php');
include '../../include/function-update.php';
include '../../include/finance-functions.php';
include '../../include/reporting-functions.php';
include '../../include/lms-functions.php';


$currentURL = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$studentBatch = isset($_GET['studentBatch']) && $_GET['studentBatch'] !== '' ? $_GET['studentBatch'] : null;
$default_location = isset($_GET['default_location']) && $_GET['default_location'] !== '' ? $_GET['default_location'] : null;

// Check if the required parameter is not set or has an empty value
if ($studentBatch === null) {
    die("Invalid request. Please provide the 'studentBatch' parameter with a non-empty value.");
}

$location_name = $Locations[$default_location]['location_name'];
$AllStudents = GetLmsStudents();
$cities = GetCities($link);
$districtsList = getDistricts($link);
$studentBathes = getLmsBatches();
$allUserEnrollments =  getAllUserEnrollments();

// Rest of your code goes here...
$pageTitle = "Student Report  - " . $studentBatch . " - " . $studentBathes[$studentBatch]['course_name'];
$reportTitle = "Student Report";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:title" content="<?= $pageTitle ?>" />
    <meta property="og:description" content="<?= $pageTitle ?>" />
    <meta property="og:image" content="https://admin.pharmacollege.lk/assets/images/favicon/apple-touch-icon.png" />
    <meta property="og:url" content="<?= $currentURL ?>" />

    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/report-viewer.css">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
                /* You can adjust the margin as needed */
            }
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h2 class="report-title-mini"><?= strtoupper($reportTitle) ?></h2>
                <table>
                    <tr>
                        <th>Code</th>
                        <td class="text-end"><?= $studentBatch ?></td>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <td class="text-end"><?= $studentBathes[$studentBatch]['course_name'] ?></td>
                    </tr>
                </table>
            </div>

        </div>


        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Index Number</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Name with Initials</th>
                        <th scope="col">Name On Certificate</th>
                        <th scope="col">Enrollments</th>
                    </tr>
                </thead>
                <tbody>
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
                            $nameWithInitials = $selectedArray['name_with_initials'];
                            $nameOnCertificate = $selectedArray['name_on_certificate'];

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
                            <tr>
                                <td class="border-bottom"><?= $indexNumber ?></td>
                                <td class="border-bottom"><?= $studentName ?></td>
                                <td class="border-bottom"><?= $addressL1 ?>, <?= $addressL2 ?>, <?= $cityName ?>, <?= $districtName ?>, <?= $postalCode ?></td>
                                <td class="border-bottom"><?= $telephone_1 ?></td>
                                <td class="border-bottom"><?= $nameWithInitials ?></td>
                                <td class="border-bottom"><?= $nameOnCertificate ?></td>
                                <td class="text-center border-bottom">
                                    <?php
                                    if (!empty($enrollmentArray)) {
                                        foreach ($enrollmentArray as $enrollment) {
                                    ?>
                                            <p class="mb-0">
                                                <?= $enrollment['course_code'] ?>
                                            </p>
                                    <?php
                                        }
                                    }
                                    ?>
                                </td>

                            </tr>

                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>


        </div>

        <div id="container" class="section-6" style="margin-top: 60px;">
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Checked by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Authorized by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Received by</p>
            </div>
        </div>

        <script>
            window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>