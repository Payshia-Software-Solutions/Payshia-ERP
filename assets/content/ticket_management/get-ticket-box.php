<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods

$FilterKey = $_POST['FilterKey'];
$LoggedUser = $_POST['LoggedUser'];
$studentBatch = $_POST['studentBatch'];

$ticketList = GetTickets();
$CourseBatches = getLmsBatches();
$Locations = GetLocations($link);
$accountDetails = GetAccounts($link);

$ticketCount = count($ticketList);
$ActiveStatus = 0;
?>

<table class="table table-striped table-hover table-fixed" id="ticket-table">
    <thead>
        <tr>
            <th scope="col">Ticket #</th>
            <th scope="col">Status</th>
            <th scope="col">Message</th>
            <th scope="col">Assign</th>
            <th scope="col">Subject</th>
            <th scope="col">Student</th>
            <th scope="col">Department</th>
            <th scope="col">Service</th>
            <th scope="col">Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ticketId = 0;
        if (!empty($ticketList)) {
            foreach ($ticketList as $ticket) {

                $ticketId = $ticket['ticket_id'];
                $ticketAssignments = GetTicketAssignment($ticketId);
                $assignedUser = 'None';
                if (isset($ticketAssignments[0])) {
                    if ($ticketAssignments[0]['user_name'] != $LoggedUser && $FilterKey == 'MyTickets') {
                        continue;
                    }
                    $assignedUser = $ticketAssignments[0]['user_name'];
                }

                if ($assignedUser == 'None' && $FilterKey == 'MyTickets') {
                    continue;
                }


                if ($ticket['parent_id'] != 0) {
                    $ticketId = $ticket['parent_id'];
                    continue;
                }

                $stateCode = $ticket['is_active'];
                $stateArray = GetTicketStatus($stateCode);
                $ticketReplies = GetReplyByTicket($ticketId);
        ?>
                <tr class="clickable" onclick="OpenTicket('<?= $ticketId ?>')">
                    <td>TK<?= str_pad($ticketId, 4, '0', STR_PAD_LEFT); ?></td>

                    <td>
                        <div class="badge bg-<?= $stateArray['bgColor'] ?>"><?= $stateArray['stateValue'] ?></div>
                    </td>
                    <td>
                        <?php
                        if (!empty($ticketReplies)) {
                            $replyText = strip_tags($ticketReplies[0]['ticket']);
                            $readStatus = $ticketReplies[0]['read_status'];

                            if ($readStatus === 'unread') {
                                $readBadgeColor = 'success';
                            } else if ($readStatus === 'read') {
                                $readBadgeColor = 'info';
                            } else if ($readStatus === 'replied') {
                                $readBadgeColor = 'dark';
                            }

                        ?>
                            <span class="text-muted">
                                <?php if ($ticketReplies[0]['index_number'] != $LoggedUser) { ?>
                                    <div class="badge bg-<?= $readBadgeColor ?>"><?= ucwords($readStatus) ?></div>
                                <?php }
                                ?>

                                Replied by <?= $ticketReplies[0]['index_number'] ?>
                            </span>
                            <span class="<?= ($readStatus === 'unread') ? 'fw-bolder' : '' ?>"> <?= truncateText($replyText, 100) ?></span>
                        <?php
                        } else {
                            $readStatus = $ticket['read_status'];

                            if ($readStatus === 'unread') {
                                $readBadgeColor = 'success';
                            } else if ($readStatus === 'read') {
                                $readBadgeColor = 'info';
                            } else if ($readStatus === 'replied') {
                                $readBadgeColor = 'dark';
                            }

                        ?>
                            <span class="<?= ($readStatus === 'unread') ? 'fw-bolder' : '' ?>">
                                <div class="badge bg-<?= $readBadgeColor ?>"><?= ucwords($readStatus) ?></div> <?= truncateText(strip_tags($ticket['ticket']), 100) ?>
                            </span>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($ticketAssignments[0])) { ?>
                            <div class="badge bg-success"><?= $accountDetails[$assignedUser]['first_name'] ?> <?= $accountDetails[$assignedUser]['last_name'] ?></div>
                        <?php } else {
                            echo "None";
                        } ?>
                    </td>
                    <td><?= $ticket['subject'] ?></td>
                    <td><?= $ticket['index_number'] ?></td>
                    <td><?= $ticket['department'] ?></td>
                    <td><?= $ticket['related_service'] ?></td>
                    <td>
                        <?php
                        if (!empty($ticketReplies)) {
                            echo date("Y-m-d H:i", strtotime($ticketReplies[0]['created_at']));
                        } else {
                            echo date("Y-m-d H:i", strtotime($ticket['created_at']));
                        }
                        ?>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#ticket-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [7, 'desc']
            ]
        });



    });
</script>