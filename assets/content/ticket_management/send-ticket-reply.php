<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods
$replyId = $_POST['replyId'];
$userLevel = $_POST['UserLevel'];
$indexNumber = $_POST['LoggedUser'];
$parentId = $_POST['ticketId'];
$ticketText = $_POST['ticketText'];

$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

$isActive = 1;

$ticketInfo = GetTicketsById($parentId);
$ticketSubject = $ticketInfo['subject'];
$ticketDepartment = $ticketInfo['department'];
$relatedService = $ticketInfo['related_service'];
$attachmentList = '';

$toIndexNumber = 'Admin';
$readStatus = 'unread';

$saveResult = SaveTicket($replyId, $indexNumber, $ticketSubject, $ticketDepartment, $relatedService, $ticketText, $attachmentList, $isActive, $toIndexNumber, $readStatus, $parentId);
echo json_encode($saveResult);
