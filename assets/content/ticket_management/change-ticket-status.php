<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods


$ticketId = $_POST['ticketId'];
$ticketStatus = $_POST['ticketStatus'];

$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

$saveResult = UpdateTicketStatus($ticketId, $ticketStatus);
echo json_encode($saveResult);
