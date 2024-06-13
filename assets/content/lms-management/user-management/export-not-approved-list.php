<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';


$Locations = GetLocations($link);
$temporaryUsers = GetTemporaryUsers();
$CourseBatches = getLmsBatches();
$ArrayCount = count($temporaryUsers);

$LoggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="border-bottom pb-2"></p>

            <div class="table-responsive">
                <table id="exportUserTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Student Name</th>
                            <th>NIC #</th>
                            <th>Phone #</th>
                            <th>Address</th>
                            <th>Registered on</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($temporaryUsers)) {
                            foreach ($temporaryUsers as $selectedArray) {
                                $referenceId = $selectedArray['id'];
                                $email_address = $selectedArray['email_address'];
                                $first_name = $selectedArray['first_name'];
                                $last_name = $selectedArray['last_name'];
                                $nic_number = $selectedArray['nic_number'];
                                $phone_number = $selectedArray['phone_number'];
                                $address_l1 = $selectedArray['address_l1'];
                                $address_l2 = $selectedArray['address_l2'];
                                $paid_amount = $selectedArray['paid_amount'];
                                $approved_status = $selectedArray['aprroved_status'];
                                $student_name = $first_name . " " . $last_name;

                                if ($approved_status == "Not Approved") {
                                    $color = "danger";
                                } else if ($approved_status == "Approved") {
                                    continue;
                                    $color = "dark";
                                } else if ($approved_status == "Rejected") {
                                    continue;
                                    $color = "danger";
                                } else {
                                    $color = "success";
                                }


                                $regDate = new DateTime($selectedArray['created_at']);
                                $formattedRegDate = $regDate->format('Y-m-d H:i:s');
                        ?>
                                <tr>
                                    <td><?= $referenceId; ?></td>
                                    <td><?= $email_address; ?></td>
                                    <td><?= $student_name; ?></td>
                                    <td><?= $nic_number; ?></td>
                                    <td><?= $phone_number; ?></td>
                                    <td><?= $address_l1; ?>, <?= $address_l2; ?></td>
                                    <td><?= $formattedRegDate; ?></td>
                                    <td class="text-center"><span class="badge bg-<?= $color ?>"><?= $approved_status ?></span></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#exportUserTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'colvis'
            ],
            ordering: false
        });

    });
</script>