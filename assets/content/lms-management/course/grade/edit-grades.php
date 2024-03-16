<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';
$batchList = getLmsBatches();
$courseCode = $_POST['studentBatch'];


$batchList = getLmsBatches();
$CourseAssignments = GetAssignments($courseCode);
$userEnrollments = getAllUserEnrollmentsByCourse($courseCode);
$userDetails = GetLmsStudentsByUserId();

$assignmentSubmissions = GetAssignmentSubmissions();

// var_dump($CourseAssignments);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0">Edit Grades of Batch <?= $courseCode ?></h4>
                <p class="pb-2 border-bottom text-secondary">Please Note: Once you change the value and edit the database Values!</p>
                <table id="gradeTable" class="table table-bordered my-2">
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
                                $student_id = $userDetails[$userRecord['student_id']]['username'];
                        ?>
                                <tr>
                                    <td><?= $student_id ?></td>
                                    <?php
                                    if (!empty($CourseAssignments)) {
                                        foreach ($CourseAssignments as $selectedArray) {
                                            $assignment_id = $selectedArray['assignment_id']
                                    ?>
                                            <td><input onchange="ChangeGradeValue('<?= $assignment_id ?>', '<?= $student_id ?>', this.value)" class="form-control text-center" type="text" value="<?= isset($assignmentSubmissions[$assignment_id . '-' . $student_id]['grade']) ? $assignmentSubmissions[$assignment_id . '-' . $student_id]['grade'] : '' ?>"></td>
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
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#gradeTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ],
            pageLength: 20
        });
    });
</script>