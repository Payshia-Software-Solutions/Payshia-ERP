<?php

// Include the qrlib file
require_once(__DIR__ . '/../../../../../vendor/phpqrcode/qrlib.php');
require_once(__DIR__ . '/../../../../../vendor/tecnickcom/tcpdf/tcpdf.php');

// Load the existing image
$imagePath = 'ceritifate.jpg';
$image = imagecreatefromjpeg($imagePath);

// Set the text color
$textColor = imagecolorallocate($image, 0, 0, 0); // RGB for black

// Text to be added
$text = 'Gamage Thilina Ruwan Kumara Doloswala';
$pvNumber = 'PV00253555';
$CourseCode = 'CS0001';
$certificateId = 11540;
$s_user_name = 'PA201152';
$dateText = 'Date : ' . date('Y-m-d');
$indexNumberText = 'Index Number: ' . $s_user_name;
$certificateIdText = 'Certificate ID: ' . $certificateId;



// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{
    //Page header
    public function Header()
    {
        global $outputImagePath;
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set background image
        $this->Image($outputImagePath, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
    }
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// add a page
$pdf->AddPage();

//Close and output PDF document
$pdf->Output(__DIR__ . '/CeylonPharmaCollege-e-Certificate-' . $s_user_name . '.pdf', 'F');
?>

<img src="CeylonPharmaCollege-e-Certificate-<?= $s_user_name ?>.jpg" alt="Modified Image">
<embed src="CeylonPharmaCollege-e-Certificate-<?= $s_user_name ?>.pdf" width="100%" height="100%" type="application/pdf">;