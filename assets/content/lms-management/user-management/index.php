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

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Registrations</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Pending</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Approved</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

</div>


<div class="row mt-5">
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Not Approved List</div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-end">
                        <button class="btn btn-primary btn-sm" type="button" onclick="OpenPendingUser()">Download List</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                        <td>
                                            <?= $student_name; ?>
                                            <p class="mb-0"><?= $nic_number; ?></p>
                                            <p class="mb-0">Phone : <?= $phone_number; ?></p>
                                            <p class="mb-0"><?= $address_l1; ?>, <?= $address_l2; ?></p>
                                            <p class="mb-0">Register Date - <?= $formattedRegDate ?></p>
                                        </td>
                                        <td class="text-center"><span class="badge bg-<?= $color ?>"><?= $approved_status ?></span></td>
                                        <td>
                                            <div class="text-center">
                                                <button class="btn btn-sm btn-dark view-button mb-2" type="button" onclick="OpenUserInfo('<?= $referenceId ?>')">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <button class="btn btn-sm btn-light" type="button" onclick="OpenEditUserInfo('<?= $referenceId ?>')">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </button>
                                            </div>
                                        </td>

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
    <div class="col-md-4">
        <div class="table-title font-weight-bold mb-4 mt-0">Recent List</div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="approvedTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Details</th>
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
                                    $index_number = $selectedArray['index_number'];
                                    $student_name = $first_name . " " . $last_name;

                                    if ($approved_status == "Not Approved") {
                                        continue;
                                        $color = "danger";
                                    } else if ($approved_status == "Approved") {
                                        $color = "dark";
                                    } else if ($approved_status == "Rejected") {
                                        $color = "danger";
                                    } else {
                                        $color = "success";
                                    }

                            ?>
                                    <tr>
                                        <td>
                                            <p class="mb-0">REF# <?= $referenceId; ?></p>
                                            <h6 class="mb-0"><?= $index_number ?></h6>
                                            <p class="mb-0"><?= $email_address; ?></p>
                                            <p class="mb-0"><?= $student_name; ?></p>
                                            <p class="mb-0"><?= $phone_number; ?></p>
                                            <p class="mb-0 mt-1">
                                                <?= $address_l1; ?>, <?= $address_l2; ?>
                                            </p>
                                            <span class="badge bg-<?= $color ?>"><?= $approved_status ?></span>
                                            <?php
                                            if ($UserLevel == "Admin" && $approved_status == "Rejected") {
                                            ?><div class="mt-2">
                                                    <button class="btn btn-sm btn-dark view-button" type="button" onclick="OpenUserInfo('<?= $referenceId ?>')">
                                                        <i class="fa-solid fa-eye"></i> Update Status
                                                    </button>
                                                </div>
                                            <?php
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
                </div>
            </div>
        </div>

    </div>


</div>


<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            ordering: false
        });

        $('#approvedTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            pageLength: 6,
            ordering: false
        });
    });
</script>