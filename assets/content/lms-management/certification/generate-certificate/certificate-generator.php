<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
ini_set('display_errors', 1);

// Include the qrlib file
require_once(__DIR__ . '/../../../../../vendor/phpqrcode/qrlib.php');
require_once(__DIR__ . '/../../../../../vendor/tecnickcom/tcpdf/tcpdf.php');

// Load the existing image
$imagePath = 'certificate.jpg';
$image = imagecreatefromjpeg($imagePath);

// Set the text color
$textColor = imagecolorallocate($image, 0, 0, 0); // RGB for black

// Text to be added
$text = 'Gamage Thilina Ruwan Kumara Doloswala';
$pvNumber = 'PV00253555';
$CourseCode = 'CS0001';
$certificateId = 11540;
$s_user_name = 'PA18513';
$dateText = 'Date : ' . date('Y-m-d');
$indexNumberText = 'Index Number: ' . $s_user_name;
$certificateIdText = 'Certificate ID: ' . $certificateId;

// Font size
$fontSize = 35;

// Font file
$fontFile = __DIR__ . '/arial.ttf';
$nameFontFile = __DIR__ . '/chaparral-pro-bold-Italic.ttf';

// Get text dimensions
$textDimensions = imagettfbbox($fontSize, 0, $fontFile, $text);
$textWidth = $textDimensions[4] - $textDimensions[0];
$textHeight = $textDimensions[1] - $textDimensions[7];

// Calculate x-coordinate to center the text horizontally
$imageWidth = imagesx($image);
$textX = ($imageWidth - $textWidth) / 2;

// Y-coordinate
$textY = 560; // Adjust as needed

// Add text to the image
imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, $nameFontFile, $text);
imagettftext($image, 15, 0, 1620, 35, $textColor, $fontFile, $pvNumber);
imagettftext($image, 15, 0, 50, 1140, $textColor, $fontFile, $dateText);
imagettftext($image, 15, 0, 50, 1165, $textColor, $fontFile, $indexNumberText);
imagettftext($image, 15, 0, 50, 1190, $textColor, $fontFile, $certificateIdText);

// QR code related data
$textForQR = "https://pharmacollege.lk/result-view.php?CourseCode=" . $CourseCode . "&LoggedUser=" . $s_user_name;
$ecc = 'L';
$pixel_Size = 4;
$frame_Size = 0;

// Generate the QR code image
ob_start();
QRcode::png($textForQR, null, QR_ECLEVEL_L, $pixel_Size, $frame_Size);
$qrImageData = ob_get_contents();
ob_end_clean();

// Create image from QR code data
$qrImage = imagecreatefromstring($qrImageData);

// Merge QR code with certificate image
imagecopy($image, $qrImage, 50, 980, 0, 0, imagesx($qrImage), imagesy($qrImage));

// Get image string for signature image
$signatureImgPath = 'signature.png';
$signatureImg = imagecreatefrompng($signatureImgPath);
// Desired width and height for the resized signature image
$newWidth = 300; // Adjust this as needed, maintaining aspect ratio
$newHeight = null; // If null, height will be calculated to maintain aspect ratio

// Resize the signature image
$signatureImgResized = imagescale($signatureImg, $newWidth);

// Position and add the resized signature image to the main image
imagecopy($image, $signatureImgResized, 1040, 1050, 0, 0, imagesx($signatureImgResized), imagesy($signatureImgResized));

// Free up memory by destroying the image resources
imagedestroy($signatureImg);
imagedestroy($signatureImgResized);

$targetDir = '../../assets/images/student-certificates/' . $s_user_name .  '/';

// Create the target directory if it doesn't exist
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Save the modified image
$outputImagePath = $targetDir . '/CeylonPharmaCollege-e-Certificate-' . $s_user_name . '.jpg';
imagejpeg($image, $outputImagePath);

// Free up memory
imagedestroy($image);
