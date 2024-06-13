<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods


$ticketId = $_POST['ticketId'];
$readStatus = $_POST['readStatus'];

$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

$saveResult = UpdateTicketReadStatus($ticketId, $readStatus);
echo json_encode($saveResult);
