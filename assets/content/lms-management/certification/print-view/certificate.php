<?php

require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$loggedUser = $_GET['PrintedId'];
$s_user_name = $_GET['studentNumber'];
$PrintDate = $_GET['issuedDate'];
$CourseCode = $_GET['selectedCourse'];
$templateId = $_GET['certificateTemplate'];
$backImageStatus = $_GET['backImageStatus'];
$orientationStatus = $_GET['orientationStatus'];
$TemplateDetails = GetTemplate($templateId);
$batchStudents =  GetLmsStudents();
$studentDetailsArray = $batchStudents[$s_user_name];

if (isset($TemplateDetails[$templateId])) {
    $Template = $TemplateDetails[$templateId];
}
$pgWidth = 297;
if ($orientationStatus == 'Portrait') {
    $pgWidth = 210;
}
$qr_position_from_left = $Template['left_to_qr'];
$qr_position_from_top = $Template['top_to_qr'];
$qr_code_width = $Template['qr_width'];

$date_position_from_left = $Template['left_to_date'];
$date_position_from_top = $Template['top_to_date'];

$name_position_from_left = $Template['left_margin'];
$name_position_from_top = $Template['top_to_name'];
$backImage = $Template['back_image'];

$printDate = date("Y-m-d H:i:s");
$certificateEntryResult = EnterCertificateEntry($printDate, 1, $loggedUser, 'Certificate', $s_user_name, $CourseCode);
// var_dump($certificateEntryResult);

// Include the qrlib file
require_once(__DIR__ . '/../../../../../vendor/phpqrcode/qrlib.php');


$text = "https://pharmacollege.lk/result-view.php?CourseCode=" . $CourseCode . "&LoggedUser=" . $s_user_name;
$ecc = 'L';
$pixel_Size = 10;
$frame_Size = 0;

// Generate the QR code image
ob_start();
QRcode::png($text, null, QR_ECLEVEL_L, 10, 0);
$image_data = ob_get_contents();
ob_end_clean();
?>

<title><?= $s_user_name ?> - <?= $CourseCode ?> - Certificate Print</title>

<style>
    @import url('https://fonts.cdnfonts.com/css/chaparral-pro?styles=15266');
    @import url(https://db.onlinewebfonts.com/c/5c0d13eb3af810e996bce6c3482f881c?family=Chaparral+Pro+Bold+Italic);

    /* 
        font-family: 'Courier Prime', monospace;
        font-family: 'IBM Plex Mono', monospace;
    */

    * {
        padding: 0px !important;
        margin: 0px !important;
    }

    .back-image {
        width: 297mm;
        height: 209.8mm;
    }

    .name-box {
        padding-left: <?= $name_position_from_left ?>mm !important;
    }

    .pv-number {
        position: fixed;
        left: 268mm;
        top: 5mm
    }

    .certificate-user {

        /* border: 1px solid black; */

        font-family: "Chaparral Pro Bold Italic";
        width: calc(<?= $pgWidth ?>mm - 100px);
        font-size: 35px;
        text-align: center !important;
        font-weight: 800 !important;
        position: fixed;
        top: <?= $name_position_from_top ?> !important;
    }

    .qr-code {
        left: <?= $qr_position_from_left ?> !important;
        top: <?= $qr_position_from_top ?> !important;
        position: fixed;
        width: <?= $qr_code_width ?>mm !important;
    }

    .print-date {
        left: <?= $date_position_from_left ?> !important;
        top: <?= $date_position_from_top ?> !important;
        position: fixed;
        font-family: 'Courier Prime', monospace;
    }

    .print-number {
        left: <?= $date_position_from_left ?> !important;
        top: <?= $date_position_from_top + (16 * 1) ?> !important;
        position: fixed;
        font-family: 'Courier Prime', monospace;
    }

    .certificate-number {
        left: <?= $date_position_from_left ?> !important;
        top: <?= $date_position_from_top + (16 * 2) ?> !important;
        position: fixed;
        font-family: 'Courier Prime', monospace;

    }
</style>

<?php
if ($backImage != "" && $backImageStatus == 1) {
?>
    <img class="back-image" src="../assets/images/certificate-back/<?= $backImage ?>">
<?php
}
?>
<p class="pv-number">PV00253555</p>
<div class="name-box">
    <p class="certificate-user"><?= $studentDetailsArray['name_on_certificate'] ?></p>

</div>
<img class="qr-code" src="data:image/png;base64,<?= base64_encode($image_data) ?>">
<p class="print-date">Date:<?= $PrintDate ?></p>
<p class="print-number">Index Number:<?= $s_user_name ?></p>
<p class="certificate-number">Certificate ID:<?= GetCertificateID('Certificate', $s_user_name, $CourseCode) ?></p>