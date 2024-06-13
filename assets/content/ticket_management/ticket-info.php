<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods


$accountList =  GetAccounts($link);

$ticketId = $_POST['ticketId'];
$ticketInfo = GetTicketsById($ticketId);

$stateCode = $ticketInfo['is_active'];
$stateArray = GetTicketStatus($stateCode);

$ticketReplies = GetReplyByTicketASC($ticketId);
$attachments = explode(', ', $ticketInfo['attachments']);
$studentInfo = GetLmsStudentsByUserName($ticketInfo['index_number']);
$ticketAssignments = GetTicketAssignment($ticketId);
?>
<style>
    .ticket-body img {
        max-width: 50% !important;
    }

    .ticket-body p {
        margin: 0
    }

    .ticket-body h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 3px 0 3px 0;
    }
</style>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">Ticket Details</h5>
            <span class="badge bg-<?= $stateArray['bgColor'] ?>"><?= $stateArray['stateValue'] ?></span>
            <h4><?= $ticketInfo['subject'] ?></h4>
            <p class="border-bottom pb-2"></p>


            <div class="bg-light rounded-3 p-3 text-end my-2">

                Assign To :
                <select onchange="TicketAssignmentUpdate(this.value, '<?= $ticketId; ?>', 1)" class="" name="userAccount" id="userAccount">
                    <option value="">Select User</option>
                    <?php
                    if (!empty($accountList)) {
                        foreach ($accountList as $userAccount) {
                    ?>
                            <option <?php if (isset($ticketAssignments[0])) {
                                        echo ($ticketAssignments[0]['user_name'] == $userAccount['user_name']) ? 'selected' : '';
                                    }  ?> value="<?= $userAccount['user_name']; ?>">
                                <?= $userAccount['user_name']; ?> - <?= $userAccount['first_name']; ?> <?= $userAccount['last_name']; ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <button onclick="ChangeTicketReadStatus('<?= $ticketId ?>', 'unread', 1)" class="btn btn-secondary "><i class="fa-solid fa-eye"></i> Mark As Unread</button>
                <button onclick="ChangeTicketStatus('<?= $ticketId ?>', 1)" class="btn btn-primary "><i class="fa-solid fa-check"></i> Active</button>
                <button onclick="ChangeTicketStatus('<?= $ticketId ?>', 3)" class="btn btn-danger "><i class="fa-solid fa-trash"></i> Delete</button>
                <button onclick="ChangeTicketStatus('<?= $ticketId ?>', 2)" class="btn btn-warning "><i class="fa-solid fa-xmark"></i> Close</button>
                <button onclick="OpenTicketReply('<?= $ticketId ?>')" class="btn btn-dark "><i class="fa-solid fa-reply"></i> Reply</button>
            </div>

            <div class="bg-light p-3 rounded-3 my-2">
                <div class="row g-2">
                    <div class="col-4">
                        Student
                    </div>
                    <div class="col-8">
                        <?= $studentInfo['name_on_certificate'] ?>
                    </div>

                    <div class="col-4">
                        Telephone
                    </div>
                    <div class="col-8">
                        <a href="tel:<?= $studentInfo['telephone_1'] ?>"><?= $studentInfo['telephone_1'] ?></a> / <a href="tel:<?= $studentInfo['telephone_2'] ?>"><?= $studentInfo['telephone_2'] ?></a>
                    </div>
                </div>

            </div>


            <div class="ticket-body mt-2">
                <?= $ticketInfo['ticket'] ?>
            </div>

            <div class="border-bottom my-2"></div>
            <?php
            if (!empty($attachments[0])) {
            ?>
                <h6 class="mt-3">Attachments</h6>
                <?php
                foreach ($attachments as $attachment) {
                ?>
                    <p class="mb-0"><a href="http://web.pharmacollege.lk/lib/ticket/assets/ticket_img/<?= $attachment ?>" target="_blank"><?= $attachment ?></a></p>
            <?php
                }
            }
            ?>

            <?php
            if (!empty($ticketReplies)) {
                foreach ($ticketReplies as $replyArray) {
            ?>
                    <div class="p-3 shadow-sm bg-white border-0 rounded-3 my-2">
                        <div class="card-body ticket-body">
                            <h6 class="border-bottom mb-2"><?= $replyArray['index_number'] ?></h6>
                            <?= $replyArray['ticket'] ?>
                        </div>
                    </div>
            <?php
                }
            }
            ?>


        </div>
    </div>
</div>


<script>
    $('userAccount').select2()
</script>