<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods

$ticketId = $_POST['ticketId'];
$userName = $_POST['userName'];
$loggedUser = $_POST['LoggedUser'];

$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

$saveResult = UpdateTicketAssignment($ticketId, $userName, $loggedUser);
echo json_encode($saveResult);
