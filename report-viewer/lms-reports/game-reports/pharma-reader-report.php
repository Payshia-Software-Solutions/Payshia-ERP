<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/finance-functions.php';
include '../../../include/reporting-functions.php';
include '../../../include/lms-functions.php';

include '../../../assets/content/lms-management/assets/lms_methods/pharma-reader-methods.php';

$location_id = $_GET['locationId'];
$scorePerPrescription = 10;
$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$location_name = $Locations[$location_id]['location_name'];

$studentBatch = $_GET['studentBatch'];
$userId = $_GET['userId'];

$userList = getAllUserEnrollmentsByCourse($studentBatch);
$userDetails =  GetLmsStudentsByUserId();

// Report Detail
$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

// Reader
$readerAttempts = GetAllUserReaderCounts();


$pageTitle = "Pharma Reader Assessment Report - " . $studentBatch;;
$reportTitle = "Pharma Reader Assessment Report";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../../../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../../../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../../../assets/css/report-viewer.css">

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
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                    <tr>
                        <th>Batch Code</th>
                        <td class="text-end"><?= $studentBatch ?></td>
                    </tr>

                </table>
            </div>

        </div>


        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table id="grade-table" class="display compact" style="width:100% !important">
                <thead>
                    <tr>
                        <th>Index No</th>
                        <th>Name</th>
                        <th>Read Count</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($userList)) {
                        foreach ($userList as $selectedArray) {
                            $attemptCount = 0;
                            $selectedStudent = $selectedArray['student_id'];
                            $selectedUsername =  $userDetails[$selectedStudent]['username'];

                            if (isset($readerAttempts[$selectedUsername])) {
                                $selectedArray = $readerAttempts[$selectedUsername];
                                $attemptCount = count($selectedArray);
                            }
                    ?>
                            <tr>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['username'] ?></td>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['name_on_certificate'] ?></td>
                                <td class="border-bottom text-center"><?= $attemptCount ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</body>

</html>