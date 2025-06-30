<?php
require_once __DIR__ . '/../vendor/autoload.php';

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

$data   = 'otpauth://totp/test?secret=B3JX4VCVJDVNXNZ5&issuer=chillerlan.net';
$options = new QROptions;
$options->outputType = QROutputInterface::GDIMAGE_PNG;

$qrcode = (new QRCode($options))->render($data);

echo $qrcode;

// default output is a base64 encoded data URI
// printf('<img src="%s" alt="QR Code" />', $qrcode);
