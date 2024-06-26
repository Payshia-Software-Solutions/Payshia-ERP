<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
$Students =  GetAccounts($link);
$StudentsCount = count($Students);

$ActiveStudentCount = 0;
$DeletedStudentCount = 0;
if (!empty($Students)) {
    foreach ($Students as $Student) {
        if ($Student['user_status'] == 1) {
            $ActiveStudentCount++;
        } else {
            $DeletedStudentCount++;
        }
    }
}
$Cities = GetCities($link);
?>
<style>
    .student-image {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
    }

    .student-image:hover {
        transform: scale(2);
        transition: all .2s;
    }

    .clickable {
        cursor: pointer;
    }
</style>
<div class="row mt-5">
    <div class="col-md-4">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-chalkboard-teacher icon-card"></i>
            </div>
            <div class="card-body">
                <p>Active Users</p>
                <h1><?= $ActiveStudentCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-user-xmark icon-card"></i>
            </div>
            <div class="card-body">
                <p>Deleted Users</p>
                <h1><?= $DeletedStudentCount ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-4">
        <div class="table-title font-weight-bold mb-4">User Accounts</div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-light my-0 table-hover" id="example">
                        <thead>
                            <tr>
                                <th scope="col">Email</th>
                                <th scope="col">Account #</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($Students)) {
                                foreach ($Students as $Student) {
                                    $active_status = "Deleted";
                                    $color = "warning";
                                    $type_color = "primary";

                                    if ($Student['user_status'] == 1) {
                                        $active_status = "Active";
                                        $color = "info";
                                    } else {
                                        continue;
                                    }

                                    if ($Student['acc_type'] == 'Tutor') {
                                        $type_color = "success";
                                    } else if ($Student['acc_type'] == 'Admin') {
                                        $type_color = "dark";
                                    } else if ($Student['acc_type'] == 'Parent') {
                                        $type_color = "danger";
                                    } else if ($Student['acc_type'] == 'officer') {
                                        $type_color = "warning";
                                    }

                            ?>

                                    <tr>
                                        <td>
                                            <?= $Student['email'] ?>
                                            <p class="my-0"><?= $Student['first_name'] ?> <?= $Student['last_name'] ?></p>
                                            <p class="my-0"><span class="badge bg-<?= $type_color ?>"><?= $Student['acc_type'] ?></span></p>
                                        </td>
                                        <td><?= $Student['user_name'] ?></td>

                                        <td class="text-center">
                                            <i class="fa-solid fa-pencil menu-icon clickable" onclick="LoadUserPrivilege('<?= $Student['user_name'] ?>', 'root')"></i>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center">No Entires</th>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>


    <div class="col-8">
        <div class="table-title font-weight-bold mb-4">Privileges</div>
        <div class="card">
            <div class="card-body" id="side-content">
                <p>Select a user to Change privileges</p>
            </div>
        </div>

    </div>
</div>

<?php $export_file_name = "Students List"; ?>
<script>
    // Event listener for the "Add Grade" button
    $(document).ready(function() {
        $('#add-new-button').click(function() {
            CreateStudent(1, 0)
        })

        $('#example').DataTable({
            responsive: true,

        });
    })
</script>