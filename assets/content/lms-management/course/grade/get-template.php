<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

$courseCode = $_POST['studentBatch'];
$batchList = getLmsBatches();
$CourseAssignments = GetAssignments($courseCode);
$userEnrollments = getAllUserEnrollmentsByCourse($courseCode);
$userDetails = GetLmsStudentsByUserId();

$importFileName = $courseCode . ' assignment-marks-import';
// var_dump($CourseAssignments);
?>
<table id="templateTable" class="table table-bordered my-2 d-none">
    <thead>
        <th>student_id</th>
        <?php
        if (!empty($CourseAssignments)) {
            foreach ($CourseAssignments as $selectedArray) {
        ?>
                <th><?= $selectedArray['assignment_id'] ?></th>
        <?php
            }
        }
        ?>
    </thead>
    <tbody>
        <?php
        if (!empty($userEnrollments)) {
            foreach ($userEnrollments as $userRecord) {
        ?>
                <tr>
                    <td><?= $userDetails[$userRecord['student_id']]['username'] ?></td>
                    <?php
                    if (!empty($CourseAssignments)) {
                        foreach ($CourseAssignments as $selectedArray) {
                    ?>
                            <td></td>
                    <?php
                        }
                    }
                    ?>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>


<script>
    $(document).ready(function() {
        $('#templateTable').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                title: '',
                filename: '<?= $importFileName ?>'
            }],
            order: false,
            searching: false, // Disable search bar
            paging: false, // Disable pagination
            info: false // Disable table information summary
        });
    });
</script>