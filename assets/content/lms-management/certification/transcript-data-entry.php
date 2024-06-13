<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$CourseCode = $_POST['selectedCourse'];
$indexNo = $_POST['studentNumber'];
$result_user = "";

$finalgrade = $assignment_count = $final_percentage_value = 0;
$final_percentage = $grade = 0;

$assignmentSubmissions =  GetAssignmentSubmissionsByUser($indexNo);
$CourseAssignments = GetAssignments($CourseCode);
$assignment_count = count($CourseAssignments);

if (!empty($CourseAssignments)) {
    foreach ($CourseAssignments as $selectedArray) {
        $assignment_id = $selectedArray['assignment_id'];
        if (isset($assignmentSubmissions[$assignment_id])) {
            $grade = $assignmentSubmissions[$assignment_id]['grade'];
        }

        if ($grade != "Not Graded") {
            $grade = (float) $grade;
        } else {
            $grade = 0;
        }
        $finalgrade = $finalgrade + $grade;
        $final_percentage_value = ($finalgrade / (100 * $assignment_count)) * 100;
        $final_percentage = $final_percentage_value . " %";
    }
}




$sql_inner = "SELECT `result` FROM `certificate_user_result` WHERE `index_number` LIKE '$indexNo' AND `course_code` LIKE '$CourseCode' AND `title_id` LIKE 'OverRallGrade'";
$result_inner = $lms_link->query($sql_inner);
if ($result_inner->num_rows > 0) {
    while ($row = $result_inner->fetch_assoc()) {
        $final_percentage_value = $row['result'];
    }
}


$sql = "SELECT COUNT(id) AS LoopCount FROM `certificate_course` WHERE `active_status` NOT LIKE 'Deleted' AND `course_code` LIKE '$CourseCode'";
$result = $lms_link->query($sql);
while ($row = $result->fetch_assoc()) {
    $LoopCount = $row['LoopCount'];
}
$TotalLoopCount = $LoopCount + 1;
$OptCount = 1;
?>




<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 border-bottom pb-2 ">Course Titles Complete Status</h5>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <input type="hidden" value="<?php echo $TotalLoopCount; ?>" id="LoopCount" />
            <div class="row g-2 my-2">
                <div class="col-6 mb-4">
                    OverRall Grade (%)
                </div>
                <div class="col-6">
                    <input type="hidden" class="form-control form-control-sm" value="OverRallGrade" id="optionID-<?php echo $OptCount; ?>" />
                    <input type="text" class="form-control form-control-sm" value="<?php echo $final_percentage_value; ?>" id="option-<?php echo $OptCount; ?>" />
                </div>
            </div>

            <?php

            $sql = "SELECT `id`, `title_id`, `course_code`, `active_status`, `created_at`, `created_by` FROM `certificate_course` WHERE `active_status` NOT LIKE 'Deleted' AND `course_code` LIKE '$CourseCode'";
            $result = $lms_link->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $result_user = "";
                    $course_code = $row['course_code'];
                    $title_id = $row['title_id'];
                    $title_active_status = $row['active_status'];
                    $OptCount += 1;


                    $sql_inner = "SELECT `title_name` FROM `certificate_title` WHERE `id` LIKE '$title_id'";
                    $result_inner = $lms_link->query($sql_inner);
                    if ($result_inner->num_rows > 0) {
                        while ($row = $result_inner->fetch_assoc()) {
                            $title_name = $row['title_name'];
                        }
                    }

                    $sql_inner = "SELECT `result` FROM `certificate_user_result` WHERE `index_number` LIKE '$indexNo' AND `course_code` LIKE '$CourseCode' AND `title_id` LIKE '$title_id'";
                    $result_inner = $lms_link->query($sql_inner);
                    if ($result_inner->num_rows > 0) {
                        while ($row = $result_inner->fetch_assoc()) {
                            $result_user = $row['result'];
                        }
                    }
            ?>

                    <input type="hidden" id="optionID-<?php echo $OptCount; ?>" value="<?php echo $title_id; ?>">
                    <div class="row mb-2">
                        <div class="col-6 mb-4">
                            <div class="">
                                <?php echo $title_name; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <select id="option-<?php echo $OptCount; ?>" class="form-control form-control-sm">
                                <option <?php if ($result_user == "Completed") {
                                            echo 'selected';
                                        } ?> value="Completed">Completed</option>
                                <option <?php if ($result_user != "Completed") {
                                            echo 'selected';
                                        } ?> value="Not Completed">Not Completed</option>
                            </select>
                        </div>
                    </div>



            <?php
                }
            } else {
                echo '<div class="alert alert-warning mt-2" role="alert">No Titles</div>';
            }
            ?>

            <button class="btn btn-dark btn-sm rounded-2" onclick="setAllCompleted()">Set All as Completed</button>
            <div class="row">
                <div class="col-12 mt-3 text-end">
                    <button type="button" class="btn btn-warning" onclick="SaveCertificate('<?php echo $CourseCode; ?>', '<?php echo $indexNo; ?>');">Save Certificate</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function setAllCompleted() {
        // Get all select elements with IDs starting with "option-"
        var selects = document.querySelectorAll('select[id^="option-"]');

        // Loop through the select elements and set their value to "Completed"
        selects.forEach(function(select) {
            select.value = "Completed";
        });
    }
</script>