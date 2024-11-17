<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
ini_set('display_errors', 1);

require_once 'CertificateGenerator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $imagePath = $data['imagePath'];
    $text = $data['text'];
    $pvNumber = $data['pvNumber'];
    $courseCode = $data['courseCode'];
    $certificateId = $data['certificateId'];
    $s_user_name = $data['s_user_name'];
    $dateText = 'Date : ' . date('Y-m-d');
    $indexNumberText = 'Index Number: ' . $s_user_name;
    $certificateIdText = 'Certificate ID: ' . $certificateId;

    $generator = new CertificateGenerator($imagePath);

    // Add main text
    $generator->addText($text, 560, 560, 35, __DIR__ . '/chaparral-pro-bold');

    // Add other texts
    $generator->addText($pvNumber, 1620, 35, 15, __DIR__ . '/arial');
    $generator->addText($dateText, 50, 1140, 15, __DIR__ . '/arial');
    $generator->addText($indexNumberText, 50, 1165, 15, __DIR__ . '/arial');
    $generator->addText($certificateIdText, 50, 1190, 15, __DIR__ . '/arial');

    // Add QR code
    $textForQR = "https://pharmacollege.lk/result-view.php?CourseCode=" . $courseCode . "&LoggedUser=" . $s_user_name;
    $generator->addQRCode($textForQR, 50, 980);

    // Add signature
    $generator->addSignature('signature.png', 1040, 1050, 300);

    // Save the image
    $targetDir = '../../assets/images/student-certificates/' . $s_user_name .  '/';
    CertificateGenerator::createDirectory($targetDir);
    $outputImagePath = $targetDir . '/CeylonPharmaCollege-e-Certificate-' . $s_user_name . '.jpg';
    $generator->save($outputImagePath);

    echo json_encode(['status' => 'success', 'path' => $outputImagePath]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
