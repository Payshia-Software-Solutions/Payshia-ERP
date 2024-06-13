<?php

include __DIR__ . '/../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function truncateText($text, $maxLength = 150)
{
    // Check if the text length exceeds the maximum length
    if (strlen($text) > $maxLength) {
        // Truncate the text to the maximum length
        $shortText = substr($text, 0, $maxLength);
        // Find the last space within the truncated text
        $lastSpace = strrpos($shortText, ' ');
        // Create the short text by removing any incomplete words
        $shortText = substr($shortText, 0, $lastSpace) . '...';
        // Return the short text with a "Read more" lms_link
        return "$shortText";
    } else {
        // If the text is already shorter than the maximum length, return it as is
        return $text;
    }
}




function GetTickets()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` ORDER BY `ticket_id` DESC, parent_id DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ticket_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetTicketsByUser($indexNumber)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` WHERE `index_number` LIKE '$indexNumber' ORDER BY `ticket_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ticket_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetTicketsByUserByStatus($indexNumber, $ticketStatus)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` WHERE `index_number` LIKE '$indexNumber' AND `is_active` LIKE '$ticketStatus' ORDER BY `ticket_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ticket_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetTicketsById($ticketId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` WHERE `ticket_id` LIKE '$ticketId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ticket_id']] = $row;
        }
    }
    return $ArrayResult[$ticketId];
}

function SaveTicket($ticketId, $indexNumber, $ticketSubject, $ticketDepartment, $relatedService, $ticketInfo, $attachmentList, $isActive, $toIndexNumber, $readStatus, $parentId)
{
    global $lms_pdo;

    try {
        $current_time = date("Y-m-d H:i:s");

        if ($ticketId == 0) {
            $stmt = $lms_pdo->prepare("INSERT INTO `support_ticket` (`index_number`, `subject`, `department`, `related_service`, `ticket`, `attachments`, `created_at`, `is_active`, `to_index_number`, `read_status`, `parent_id`) VALUES (:index_number, :ticket_subject, :department, :related_service, :ticket, :attachments, :created_at, :is_active, :to_index_number,:read_status, :parent_id)");
        } else {
            $stmt = $lms_pdo->prepare("UPDATE `support_ticket`  SET `index_number`= :pres_name, `subject`= :pres_name, `department`= :pres_name, `related_service`= :pres_name, `ticket`= :pres_name, `attachments`= :pres_name, `created_at`= :pres_name, `is_active`= :pres_name WHERE `ticket_id ` = :ticket_id");
        }

        $stmt->bindParam(':index_number', $indexNumber);
        $stmt->bindParam(':ticket_subject', $ticketSubject);
        $stmt->bindParam(':department', $ticketDepartment);
        $stmt->bindParam(':related_service', $relatedService);
        $stmt->bindParam(':ticket', $ticketInfo);
        $stmt->bindParam(':attachments', $attachmentList);
        $stmt->bindParam(':created_at', $current_time);
        $stmt->bindParam(':is_active', $isActive);
        $stmt->bindParam(':to_index_number', $toIndexNumber);
        $stmt->bindParam(':read_status', $readStatus);
        $stmt->bindParam(':parent_id', $parentId);


        $stmt->execute();

        // SMS Send
        $messageText = 'Dear ' . $toIndexNumber . '
Your have Ticket Reply. Please check your support Tickets.';

        $studentInfo = GetLmsStudentsByUserName($toIndexNumber);
        SentSMS($studentInfo['telephone_1'], 'Pharma C.', $messageText);

        return array('status' => 'success', 'message' => 'Ticket Saved successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}

function GetReplyByTicket($ticketId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` WHERE `parent_id` LIKE '$ticketId' ORDER BY `ticket_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetReplyByTicketASC($ticketId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `support_ticket` WHERE `parent_id` LIKE '$ticketId' ORDER BY `ticket_id` ASC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function UpdateTicketStatus($ticketId, $ticketStatus)
{
    global $lms_pdo;

    try {
        $stmt = $lms_pdo->prepare("UPDATE `support_ticket` SET `is_active`= :is_active WHERE `ticket_id` = :ticket_id");

        $stmt->bindParam(':ticket_id', $ticketId);
        $stmt->bindParam(':is_active', $ticketStatus);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Ticket Status Updated successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function GetTicketStatus($stateCode)
{
    $resultArray = array();
    if ($stateCode == 1) {
        $stateValue = 'Open';
        $bgColor = 'primary';
    } else if ($stateCode == 2) {
        $stateValue = 'Closed';
        $bgColor = 'warning';
    } else if ($stateCode == 3) {
        $stateValue = 'Deleted';
        $bgColor = 'danger';
    }

    $resultArray = array(
        'stateValue' => $stateValue,
        'bgColor' => $bgColor
    );
    return $resultArray;
}


function UpdateTicketReadStatus($ticketId, $readStatus)
{
    global $lms_pdo;

    try {
        $stmt = $lms_pdo->prepare("UPDATE `support_ticket` SET `read_status`= :read_state WHERE `ticket_id` = :ticket_id OR `parent_id` = :parent_id");

        $stmt->bindParam(':ticket_id', $ticketId);
        $stmt->bindParam(':parent_id', $ticketId);
        $stmt->bindParam(':read_state', $readStatus);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Ticket Status Updated successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function UpdateTicketAssignment($ticketId, $assignUsername, $loggedUser)
{
    global $lms_pdo;
    $current_time = date("Y-m-d H:i:s");
    try {
        $stmt = $lms_pdo->prepare("INSERT INTO `ticket_assignment` (`user_name`, `ticket_id`, `created_by`, `created_at`) VALUES (:user_name, :ticket_id, :created_by, :created_at)");

        $stmt->bindParam(':user_name', $assignUsername);
        $stmt->bindParam(':ticket_id', $ticketId);
        $stmt->bindParam(':created_by', $loggedUser);
        $stmt->bindParam(':created_at', $current_time);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Ticket Assignment Updated successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function GetTicketAssignment($ticketId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `user_name`, `ticket_id`, `created_by`, `created_at` FROM `ticket_assignment` WHERE `ticket_id` LIKE '$ticketId' ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}
