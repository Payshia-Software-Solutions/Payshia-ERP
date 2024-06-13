<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/finance-functions.php';
include '../../../include/reporting-functions.php';
include '../../../include/lms-functions.php';

include '../../../assets/content/lms-management/assets/lms_methods/d-pad-methods.php';

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

// DPad
$prescriptionList =  GetActivePrescriptions();
$allSavedAnswers = GetSubmittedAnswersByAllUser($studentBatch);
$allEnvelopes = GetPrescriptionAllCoversDpad();

// var_dump($allSavedAnswers);

$pageTitle = "D-Pad Assessment Report - " . $studentBatch;;
$reportTitle = "D-Pad Assessment Report";
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
                        <th>D-Pad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($userList)) {
                        foreach ($userList as $selectedArray) {

                            $correctCount = $inCorrectCount = $correctScore = $inCorrectScore = $overallGrade = 0;
                            $selectedStudent = $selectedArray['student_id'];
                            $selectedUsername =  $userDetails[$selectedStudent]['username'];

                            // Regular expression pattern for keys starting with "1-" followed by any characters
                            $userAnswers = array_filter($allSavedAnswers, function ($submission) use ($selectedUsername) {
                                // Filter elements where the lesson_id matches the pattern
                                return $submission['created_by'] == $selectedUsername;
                            });


                            $totalEnvelopes = 0;
                            foreach ($prescriptionList as $selectedArray) {
                                $prescriptionId = $selectedArray['prescription_id'];
                                $medicineEnvelopes = $allEnvelopes[$prescriptionId];


                                if ($medicineEnvelopes) {
                                    $medicineCount = count($medicineEnvelopes);
                                    $totalEnvelopes += $medicineCount;

                                    $correctCount += GetSubmittedAnswersCount($selectedUsername, $prescriptionId, 'Correct', $medicineCount, $userAnswers);
                                    $inCorrectCount += GetSubmittedAnswersCount($selectedUsername, $prescriptionId, 'In-Correct', $medicineCount, $userAnswers);
                                }
                            }

                            $correctScore = $correctCount * $scorePerPrescription;
                            $inCorrectScore = $inCorrectCount * -1;

                            $prescriptionCount = count($prescriptionList);

                            if ($prescriptionCount > 0) {
                                $overallGrade = (($correctScore + $inCorrectScore) / ($totalEnvelopes * $scorePerPrescription)) * 100;
                            }

                    ?>
                            <tr>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['username'] ?></td>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['name_on_certificate'] ?></td>
                                <td class="border-bottom text-center"><?= number_format($overallGrade, 2) ?></td>
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