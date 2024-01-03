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
    <div class="col-md-4 text-end mt-4 mt-md-0">
        <button class="btn btn-dark" type="button" id="add-new-button"><i class="fa-solid fa-plus"></i> Add New User</button>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="table-title font-weight-bold mb-4">User Accounts</div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-light my-0 table-hover" id="example">
                        <thead>
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Status</th>
                                <th scope="col">Email</th>
                                <th scope="col">Account #</th>
                                <th scope="col">Phone No</th>
                                <th scope="col">Type</th>
                                <th scope="col">Account Name</th>
                                <th scope="col">Address</th>
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

                                    if ($Student['user_name'] == "Admin") {
                                        continue;
                                    }

                                    if ($Student['user_status'] == 1) {
                                        $active_status = "Active";
                                        $color = "info";
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
                                        <td class="text-center"><img src="./assets/images/student/<?= $Student['img_path'] ?>" class="student-image"></th>
                                        <td>
                                            <span class="badge bg-<?= $color ?>"><?= $active_status ?></span>
                                        </td>
                                        <td><?= $Student['email'] ?></th>
                                        <td><?= $Student['user_name'] ?></th>
                                        <td><?= $Student['PNumber'] ?></th>
                                        <td><span class="badge bg-<?= $type_color ?>"><?= $Student['acc_type'] ?></span></th>
                                        <td><?= $Student['first_name'] ?> <?= $Student['last_name'] ?></td>
                                        <td><?= $Student['addressl1'] ?>, <?= $Student['addressl2'] ?>, <?= $Student['city']  ?></td>

                                        <td>
                                            <i class="fa-solid fa-pencil menu-icon clickable" onclick="CreateStudent(1, '<?= $Student['user_name'] ?>')"></i>
                                            <i class="fa-solid fa-trash menu-icon clickable" onclick="CreateStudent(0, '<?= $Student['user_name'] ?>')"></i>
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
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    filename: '<?= $export_file_name ?>'
                },
                {
                    extend: 'csv',
                    filename: '<?= $export_file_name ?>'
                },
                {
                    extend: 'excel',
                    filename: '<?= $export_file_name ?>'
                },
                {
                    extend: 'pdf',
                    filename: '<?= $export_file_name ?>'
                },
                {
                    extend: 'print',
                    filename: '<?= $export_file_name ?>'
                }
            ]
        });
    })
</script>