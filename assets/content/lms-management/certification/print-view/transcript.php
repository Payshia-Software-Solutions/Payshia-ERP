<?php

require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$loggedUser = $_GET['PrintedId'];
$indexNumber = $_GET['studentNumber'];
$PrintDate = $_GET['issuedDate'];
$CourseCode = $_GET['selectedCourse'];
$templateId = $_GET['certificateTemplate'];
$backImageStatus = $_GET['backImageStatus'];
$TemplateDetails = GetTemplate($templateId);
$AcademicYear = date("Y", strtotime($PrintDate));

$batchStudents =  GetLmsStudents();
$UserInfo = $batchStudents[$indexNumber];

$CourseBatches = getLmsBatches();
$templateInfo = GetTemplateConfig($CourseCode)[$CourseCode];
// var_dump($templateInfo);

// Include the qrlib file
require_once(__DIR__ . '/../../../../../vendor/phpqrcode/qrlib.php');


$certificateEntryResult = EnterCertificateEntry($PrintDate, 1, $loggedUser, 'Transcript', $indexNumber, $CourseCode);
$text = "https://pharmacollege.lk/result-view.php?CourseCode=" . $CourseCode . "&LoggedUser=" . $indexNumber;

$ecc = 'L';
$pixel_Size = 10;
$frame_Size = 0;

// Generate the QR code image
ob_start();
QRcode::png($text, null, QR_ECLEVEL_L, 10, 0);
$image_data = ob_get_contents();
ob_end_clean();

$allowed_extensions = array("image/jpeg", "image/jpg", "image/png", "image/gif");

$final_percentage_value = "Result Not Submitted";
$CompleteDate = "Not Subbmitted";

$CompleteDate = $templateInfo['CompleteDate'];
$backImage = $templateInfo['transcript_back'];
$FinalGrades = GetFinalGrade($indexNumber, $CourseCode);
$CompanyDetails = GetCompanyDetails();

$ConvocationPlace = $templateInfo['convocation_place'];
$ConvocationDate = $templateInfo['convocation_date'];
$City = GetCities($link);
$Course = $CourseBatches[$CourseCode];


$address_line_1 = ucwords($UserInfo['address_line_1']);
$address_line_2 = ucwords($UserInfo['address_line_2']);

// Add a space after the comma if it's present
if (strpos($address_line_1, ',') !== false) {
    $address_line_1 = str_replace(',', ', ', $address_line_1);
}

if (strpos($address_line_2, ',') !== false) {
    $address_line_2 = str_replace(',', ', ', $address_line_2);
}


function capitalizeWordsWithSpaceAfterFullStop($str)
{
    // Add a space after full stops
    $str = str_replace('.', '. ', $str);

    // Split the input string into words
    $words = explode(' ', $str);

    // Capitalize the first letter of each word
    $capitalizedWords = array_map(function ($word) {
        return mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
    }, $words);

    // Join the capitalized words back into a single string
    return implode(' ', $capitalizedWords);
}

function formatAddress($address)
{
    // Split the address into parts using the comma as a separator
    $addressParts = explode(',', $address);

    // Capitalize the first letter of each part
    $capitalizedParts = array_map(function ($part) {
        return mb_convert_case(trim($part), MB_CASE_TITLE, 'UTF-8');
    }, $addressParts);

    // Join the parts back together with a comma and space
    $formattedAddress = implode(', ', $capitalizedParts);

    return $formattedAddress;
}
?>


<html>

<head>
    <title>Print Transcript - <?= $indexNumber ?></title>
    <style>
        * {
            margin: 0px
        }

        body {
            height: 294mm;
            width: 210mm;
        }

        .main-content {
            position: fixed;
            top: 38mm;
            position: fixed;
            height: 294mm;
            width: 210mm;
        }

        .title {
            text-align: center !important;
        }

        .content {
            padding: 25px;
        }

        .content h4 {
            font-size: 20px;
        }

        .content h3 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 10px;
        }

        h4 {
            margin: 0px;
        }

        .back-image {
            z-index: -1;
            position: fixed;
            height: 294mm;
            width: 210mm;
        }

        .name-table {
            margin-top: 5px;
            width: 100%;
        }

        .signature-table {
            position: fixed;
            top: 225mm;
            width: 100% !important;
        }

        .details-table {
            margin-top: 10px;
            width: 100% !important;
        }

        .detail-title {
            width: 38mm;
        }

        .data-value {
            font-weight: 800 !important;
        }


        .name-table th {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .course-content-table {
            margin-top: 5px;
            margin-bottom: 5px;
            width: 100%;
            border: 1px solid #00000063;
            border-collapse: collapse;
        }

        .course-content-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .border-bottom {

            border-bottom: 1px solid #00000063;
        }


        .course-content-table th {
            font-weight: 400;
            padding: 5px;
            font-size: 18px;
            text-align: left;

            border-left: 1px solid #00000063;
            border-right: 1px solid #00000063;
            border-collapse: collapse;
        }

        .course-content-table td {
            font-weight: 400;
            padding: 5px;
            font-size: 18px;
            border-left: 1px solid #00000063;
            border-right: 1px solid #00000063;
            border-collapse: collapse;
        }

        .success {
            color: #008000;
        }

        .danger {
            color: #ff0000;
        }

        .footer-table {
            width: 197mm;
            margin-top: 25px;
            position: fixed;
            top: 234mm;
            color: #0000009c;
        }

        .foot-text {
            width: 197mm;
            position: fixed;
            top: 268mm;
            color: #0000009c;
            font-size: 13px;
            text-align: center !important;
        }

        .final-result {
            font-size: 28px;
        }

        .footer-table,
        .footer-table th,
        .footer-table td {
            text-align: center;
            font-size: 0.8rem;
            border: 1px solid #0000003c;
            border-collapse: collapse;
        }

        .title {
            font-weight: 800 !important;
        }

        .text-bold {
            font-weight: 800 !important;
        }

        .document-topic {
            margin-bottom: 5px !important;
        }

        .qr-code {
            width: 80px;
            margin-bottom: 5px;
        }
    </style>
</head>

<?php
if ($backImage != "" && $backImageStatus == 1) {
?>
    <img class="back-image" src="../assets/images/transcript-back/<?= $backImage ?>">
<?php
}
?>

<div class="main-content">

    <div class="content">
        <h3 class="document-topic">Student Transcript</h3>
        <!-- <h4>To Whom it may concern:</h4> -->
        <p>This is to clarify that <span class="text-normal"><?= capitalizeWordsWithSpaceAfterFullStop($UserInfo['name_on_certificate']) ?></span> has Successfully
            completed the <span class="text-normal"><?= $Course['course_name'] ?></span> conducted by the Ceylon Pharma College in the academic year <?= $AcademicYear ?></p>

        <table class="details-table">
            <tr>
                <td class="detail-title">Student Name</td>
                <td>:</td>
                <td class="data-value"><?= capitalizeWordsWithSpaceAfterFullStop($UserInfo['first_name']) ?> <?= capitalizeWordsWithSpaceAfterFullStop($UserInfo['last_name']) ?>
                </td>
            </tr>
            <tr>
                <td class="detail-title">NIC</td>
                <td>:</td>
                <td class="data-value"><?= strtoupper($UserInfo['nic']) ?>
                </td>
            </tr>
            <tr>
                <td class="detail-title">Address</td>
                <td>:</td>
                <td class="data-value"><?= formatAddress($address_line_1) . ', ' . formatAddress($address_line_2); ?></td>
            </tr>
            <tr>
                <td class="detail-title">Completed Date</td>
                <td>:</td>
                <td class="data-value"><?= $PrintDate ?></td>
            </tr>
        </table>
        <table class="name-table">
            <tr>
                <td class="detail-title">Final Result</td>
                <td>:</td>
                <td class="data-value final-result"><?= $FinalGrades['final_grade'] ?> <span class="success">(PASS)</span></td>
            </tr>
            <tr>
                <td class="detail-title">Student ID</td>
                <td>:</td>
                <td class="data-value"><?= $indexNumber ?></td>
            </tr>
        </table>

        <table class="course-content-table">
            <thead>
                <tr class="border-bottom">
                    <th colspan="3" class="title">Course Content</th>
                </tr>
                <tr>
                    <th class="text-bold">Module Code</th>
                    <th class="text-bold">Module</th>
                    <th class="text-bold">Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT `id`, `title_id`, `course_code`, `active_status`, `created_at`, `created_by` FROM `certificate_course` WHERE `active_status` NOT LIKE 'Deleted' AND `course_code` LIKE '$CourseCode'";
                $result = $lms_link->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $result_user = "Result Not Submitted";
                        $course_code = $row['course_code'];
                        $title_id = $row['title_id'];
                        $title_active_status = $row['active_status'];


                        $sql_inner = "SELECT `title_name`, `hours` FROM `certificate_title` WHERE `id` LIKE '$title_id'";
                        $result_inner = $lms_link->query($sql_inner);
                        if ($result_inner->num_rows > 0) {
                            while ($row = $result_inner->fetch_assoc()) {
                                $title_name = $row['title_name'];
                                $hours = $row['hours'];
                            }
                        }

                        $sql_inner = "SELECT `result` FROM `certificate_user_result` WHERE `index_number` LIKE '$indexNumber' AND `course_code` LIKE '$CourseCode' AND `title_id` LIKE '$title_id'";
                        $result_inner = $lms_link->query($sql_inner);
                        if ($result_inner->num_rows > 0) {
                            while ($row = $result_inner->fetch_assoc()) {
                                $result_user = $row['result'];
                            }
                        }
                        $formattedHours = sprintf("%02d", $hours);


                ?>

                        <tr>
                            <td>CPCA<?php echo $title_id; ?></td>
                            <td><?php echo $title_name; ?></td>
                            <td style="text-align: center;"><?php echo $formattedHours; ?> Hours</td>
                        </tr>


                <?php
                    }
                }
                ?>

            </tbody>
        </table>
        <div class="sp-note">Note: This Certification was issued by the Ceylon Pharma College on <span class="text-bold"><?= $ConvocationDate ?>,</span> at the <span class="text-bold"><?= $ConvocationPlace ?></span></div>


        <table class="signature-table">
            <tr>
                <td class="detail-title">.......................................................</td>
            </tr>
            <tr>
                <td class="detail-title">Course Director of Ceylon Pharma College</td>
            </tr>
        </table>



        <table class="footer-table">
            <tr>
                <th rowspan="4">Scan & Verify<br>
                    <?php
                    // Output the QR code image to the browser
                    echo '<img class="qr-code" src="data:image/png;base64,' . base64_encode($image_data) . '">';
                    ?>
                </th>
                <th> Grade </th>
                <th> Scale </th>
                <th> Grade </th>
                <th> Scale </th>
                <th> Grade </th>
                <th> Scale </th>
                <th> Grade </th>
                <th> Scale </th>
            </tr>
            <tr>
                <td align="">A+</td>
                <td style="white-space:nowrap;">90.00 - 100.00</td>

                <td align=""> B+ </td>
                <td style="white-space:nowrap;"> 70.00 - 74.00 </td>

                <td align=""> C+ </td>
                <td style="white-space:nowrap;"> 55.00 - 59.00 </td>

                <td align=""> D+ </td>
                <td style="white-space:nowrap;"> 35.00 - 39.00 </td>

            </tr>
            <tr>
                <td align=""> A </td>
                <td style="white-space:nowrap;"> 80.00 - 89.00 </td>

                <td align=""> B </td>
                <td style="white-space:nowrap;"> 65.00 - 69.00 </td>

                <td align=""> C </td>
                <td style="white-space:nowrap;"> 45.00 - 54.00 </td>

                <td align=""> D </td>
                <td style="white-space:nowrap;"> 30.00 - 34.00 </td>
            </tr>
            <tr>
                <td align=""> A- </td>
                <td style="white-space:nowrap;"> 75.00 - 79.00 </td>

                <td align=""> B- </td>
                <td style="white-space:nowrap;"> 60.00 - 64.00 </td>

                <td align=""> C- </td>
                <td style="white-space:nowrap;"> 40.00 - 44.00 </td>

                <td align=""> E </td>
                <td style="white-space:nowrap;"> 0.00 - 29.00 </td>
        </table>

        <div class="foot-text">
            This result serves as an official confirmation and should be regarded as a valid certification.
            <br>
            Copyright © 2020-2023 - Department of Examination - Ceylon Pharma College
        </div>


    </div>
</div>


<body></body>

</html>