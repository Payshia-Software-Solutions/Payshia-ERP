<?php
include __DIR__ . '/../../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


function GetSubmissionLevelCount($UserName, $batchCode)
{
    global $lms_link;

    $sql = "SELECT COUNT(DISTINCT `level_id`) AS `LevelCount` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' AND `course_code` LIKE '$batchCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $LevelCount = $row['LevelCount'];
        }
    }
    return $LevelCount;
}



function GetGames($lms_link)
{
    $ArrayResult = array();
    $sql = "SELECT `GameID`, `GameTitle`, `icon_path` FROM `games_list`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['GameID']] = $row;
        }
    }
    return $ArrayResult;
}

function GetGameByCourse($lms_link, $CourseCode, $GameID)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `CourseCode`, `GameId`, `Status` FROM `care_center_game` WHERE `CourseCode` LIKE '$CourseCode' AND `GameId` LIKE '$GameID'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['GameId']] = $row;
        }
    }
    return $ArrayResult;
}


function StudentRegisteredCourses($lms_link, $userid)
{
    $ArrayResult = array();
    $sql = "SELECT `course_code` FROM `student_course` WHERE `student_id` LIKE '$userid'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row;
        }
    }
    return $ArrayResult;
}

function GetUserData($lms_link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `status_id`, `userid`, `fname`, `lname`, `batch_id`, `username`, `phone`, `email`, `password`, `userlevel`, `status`, `created_by`, `created_at`, `batch_lock` FROM `users`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['username']] = $row;
        }
    }
    return $ArrayResult;
}

function CoursePayments($lms_link, $userid)
{
    $ArrayResult = array();
    $FinalArray = array();
    $paid_amount = $discount_amount = $due_amount = 0;

    $StudentCourses = StudentRegisteredCourses($lms_link, $userid);
    $Courses = GetCourses($lms_link);

    if (!empty($StudentCourses)) {
        foreach ($StudentCourses as $StudentCourse) {
            $paid_amount = $discount_amount = $base_amount = 0;
            $payment_status = "Not Paid";
            // Getters
            $course_code = $StudentCourse['course_code'];
            $course_name = $Courses[$StudentCourse['course_code']]['course_name'];
            $course_description = $Courses[$StudentCourse['course_code']]['course_description'];
            $course_fee = $Courses[$StudentCourse['course_code']]['course_fee'];
            $registration_fee = $Courses[$StudentCourse['course_code']]['registration_fee'];
            $course_img = $Courses[$StudentCourse['course_code']]['course_img'];

            // Calculations
            $base_amount = $registration_fee + $course_fee;
            $due_amount = $course_fee + $registration_fee;

            $sql_inner = "SELECT `img_path` FROM `img_course` WHERE `course_code` LIKE '$course_code'";
            $result_inner = $lms_link->query($sql_inner);
            while ($row = $result_inner->fetch_assoc()) {
                $img_path = $row["img_path"];
            }

            $sql = "SELECT `id`, `receipt_number`, `course_code`, `student_id`, `paid_amount`, `discount_amount`, `payment_status`, `payment_type`, `paid_date`, `created_at`, `created_by` FROM `student_payment` WHERE `student_id` LIKE '$userid' AND `course_code` LIKE '$course_code'";
            $result = $lms_link->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $payment_status = $row['payment_status'];
                    $paid_amount += $row['paid_amount'];
                    $discount_amount += $row['discount_amount'];
                    $due_amount -= ($paid_amount + $discount_amount);
                }
            }

            if ($due_amount == $base_amount) {
                $payment_status = "No Paid";
            } elseif ($due_amount > 0) {
                $payment_status = "Partially Paid";
            } else {
                $payment_status = "Paid";
            }

            if ($payment_status == "Paid") {
                $bgColor = "success";
            } elseif ($payment_status == "Partially Paid") {
                $bgColor = "danger";
            } else {
                $bgColor = "warning";
            }

            $ArrayResult["course_code"] = $course_code;
            $ArrayResult["course_description"] = $course_description;
            $ArrayResult["img_path"] = $img_path;
            $ArrayResult["course_name"] = $course_name;
            $ArrayResult["paid_amount"] = $paid_amount;
            $ArrayResult["discount_amount"] = $discount_amount;
            $ArrayResult["due_amount"] = $due_amount;
            $ArrayResult["bgColor"] = $bgColor;
            $ArrayResult["course_img"] = $course_img;
            $ArrayResult["payment_status"] = $payment_status;

            $FinalArray[$course_code] = $ArrayResult;
        }
    }
    return $FinalArray;
}

function SaveLevel($lms_link, $course_code, $level_name, $created_by)
{
    $error = "";
    $CurrentTime = date("Y-m-d H:i:s");
    $is_active = 1;
    $sql = "INSERT INTO `win_pharma_level`(`course_code`, `level_name`, `is_active`, `created_at`, `created_by`) VALUES (?, ?, ?, ?, ?)";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $course_code;
        $param_2 = $level_name;
        $param_3 = $is_active;
        $param_4 = $CurrentTime;
        $param_5 = $created_by;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Level saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function UpdateLevel($lms_link, $course_code, $level_name, $created_by, $LevelCode, $is_active)
{
    $error = "";
    $CurrentTime = date("Y-m-d H:i:s");
    $sql = "UPDATE `win_pharma_level` SET `course_code` = ?, `level_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `level_id` LIKE '$LevelCode'";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $course_code;
        $param_2 = $level_name;
        $param_3 = $is_active;
        $param_4 = $CurrentTime;
        $param_5 = $created_by;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Level Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function GetLevels($lms_link, $CourseCode)
{
    $ArrayResult = array();
    $sql = "SELECT `level_id`, `course_code`, `level_name`, `is_active`, `created_at`, `created_by` FROM `win_pharma_level` WHERE `course_code` LIKE '$CourseCode' ORDER BY `level_id`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['level_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetTasks($lms_link, $LevelCode)
{
    $ArrayResult = array();
    $sql = "SELECT `resource_id`, `level_id`, `resource_title`, `resource_data`, `created_by`, `task_cover`, `is_active` FROM `win_pharma_level_resources` WHERE `level_id` LIKE '$LevelCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['resource_id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveTask($lms_link, $level_id, $resource_title, $resource_data, $created_by, $task_cover)
{
    $error = "";
    $CurrentTime = date("Y-m-d H:i:s");
    $is_active = 1;
    $sql = "INSERT INTO `win_pharma_level_resources`(`level_id`, `resource_title`, `resource_data`, `created_by`, `task_cover`)  VALUES (?, ?, ?, ?, ?)";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $level_id;
        $param_2 = $resource_title;
        $param_3 = $resource_data;
        $param_4 = $created_by;
        $param_5 = $task_cover;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Level Task saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function UpdateTask($lms_link, $level_id, $resource_title, $resource_data, $created_by, $task_cover, $resource_id)
{
    $error = "";
    $CurrentTime = date("Y-m-d H:i:s");
    $is_active = 1;
    $sql = "UPDATE `win_pharma_level_resources` SET `level_id` = ?, `resource_title` = ?, `resource_data` = ?, `created_by` = ?, `task_cover` = ? WHERE `resource_id` LIKE '$resource_id'";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $level_id;
        $param_2 = $resource_title;
        $param_3 = $resource_data;
        $param_4 = $created_by;
        $param_5 = $task_cover;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Level Task Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function GetWinpharmaSubmissions($lms_link, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`, `reason`, `update_by`, `update_at` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetWinpharmaSubmissionsByID($lms_link, $submission_id)
{
    $ArrayResult = array();
    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`, `reason`, `update_by`, `update_at` FROM `win_pharma_submission` WHERE `submission_id` LIKE '$submission_id'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetWinpharmaSubmissionsByCourse($lms_link, $CourseCode)
{
    $ArrayResult = array();
    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at`  FROM `win_pharma_submission` WHERE `course_code` LIKE '$CourseCode' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id ASC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetWinpharmaSubmissionsID($lms_link, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' ORDER BY `submission_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['submission_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetAttemptCount($lms_link, $UserName, $resource_id)
{
    $sql = "SELECT COUNT(submission_id) AS `AttemptCount` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' AND `resource_id` LIKE '$resource_id'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $AttemptCount = $row['AttemptCount'];
        }
    }
    return $AttemptCount;
}

function GetTopLevel($lms_link, $UserName, $CourseCode)
{
    $level_id = -1;
    $sql = "SELECT `level_id` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' AND `course_code` LIKE '$CourseCode' ORDER BY `level_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $level_id = $row['level_id'];
            break;
        }
    }
    return $level_id;
}



function GetTopLevelAllUsers($lms_link, $CourseCode)
{
    $topLevels = []; // Array to store top levels for all users
    $sql = "SELECT `index_number`, MAX(`level_id`) AS `top_level` FROM `win_pharma_submission` WHERE `course_code` = '$CourseCode' GROUP BY `index_number`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $indexNumber = $row['index_number'];
            $topLevel = $row['top_level'];
            $topLevels[$indexNumber] = $topLevel; // Store top level for each user
        }
    }
    return $topLevels;
}

function GetTopLevelAllUsersCompleted($lms_link, $CourseCode)
{
    $topLevels = []; // Array to store top levels for all users
    $sql = "SELECT `index_number`, MAX(`level_id`) AS `top_level` FROM `win_pharma_submission` WHERE `course_code` = '$CourseCode' AND `grade_status` LIKE 'Completed' GROUP BY `index_number`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $indexNumber = $row['index_number'];
            $topLevel = $row['top_level'];
            $topLevels[$indexNumber] = $topLevel; // Store top level for each user
        }
    }
    return $topLevels;
}

function GetCourseTopLevel($lms_link, $course_code)
{
    $level_id = -1;
    $sql = "SELECT `level_id`, `course_code`, `level_name`, `is_active`, `created_at`, `created_by` FROM `win_pharma_level` WHERE `course_code` LIKE '$course_code' ORDER BY `level_id` LIMIT 1";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $level_id = $row['level_id'];
            break;
        }
    }

    return $level_id;
}



function GetLevelCount($lms_link, $UserName, $CourseCode)
{
    $sql = "SELECT COUNT(DISTINCT `level_id`) AS `LevelCount` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' AND `course_code` LIKE '$CourseCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $LevelCount = $row['LevelCount'];
        }
    }
    return $LevelCount;
}

function GetSubmitionResult($lms_link, $UserName, $resource_id)
{
    $ArrayResult = array();
    $sql = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `date_time`, `attempt`, `course_code`, `reason`, `update_by`, `update_at` FROM `win_pharma_submission` WHERE `index_number` LIKE '$UserName' AND `resource_id` LIKE '$resource_id' ORDER BY `submission_id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetButtonCounts($lms_link, $CourseCode)
{
    $ArrayResult = array();
    $sql = "SELECT `grade_status`, COUNT(`submission_id`) AS `EntriesCount` FROM `win_pharma_submission` WHERE `course_code` LIKE '$CourseCode' GROUP BY `grade_status`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['grade_status']] = $row;
        }
    }
    return $ArrayResult;
}

function RequestReCorrection($lms_link, $index_number, $submission_id, $grade, $grade_status)
{
    $error = "";

    $sql = "SELECT `recorrection_count` FROM `win_pharma_submission` WHERE `submission_id` LIKE '$submission_id'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recorrection_count = $row['recorrection_count'];
        }
    }
    $recorrection_count += 1;

    $sql = "UPDATE `win_pharma_submission` SET `grade` = ?, `grade_status` = ?, `recorrection_count` = ? WHERE `index_number` LIKE '$index_number' AND `submission_id` = '$submission_id'";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sss", $param_1, $param_2, $param_3);

        // Set parameters
        $param_1 = $grade;
        $param_2 = $grade_status;
        $param_3 = $recorrection_count;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Recorrection Submitted successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function SaveGrade($lms_link, $index_number, $submission_id, $grade, $grade_status, $reason, $update_by)
{
    $error = "";
    date_default_timezone_set("Asia/Colombo");
    $update_at = date("Y-m-d H:i:s");
    $sql = "UPDATE `win_pharma_submission` SET `grade` = ?, `grade_status` = ?, `reason` = ?, `update_by` = ?, `update_at` = ? WHERE `index_number` LIKE '$index_number' AND `submission_id` = '$submission_id'";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $grade;
        $param_2 = $grade_status;
        $param_3 = $reason;
        $param_4 = $update_by;
        $param_5 = $update_at;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Grade Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}

function AddSubmission($lms_link, $index_number, $level_id, $resource_id, $submission, $attempt, $course_code)
{
    date_default_timezone_set("Asia/Colombo");
    $error = "";
    $CurrentTime = date("Y-m-d H:i:s");
    $grade = 0;
    $sql = "INSERT INTO `win_pharma_submission`(`index_number`, `level_id`, `resource_id`, `submission`, `grade`, `date_time`, `attempt`, `course_code`)  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8);

        // Set parameters
        $param_1 = $index_number;
        $param_2 = $level_id;
        $param_3 = $resource_id;
        $param_4 = $submission;
        $param_5 = $grade;
        $param_6 = $CurrentTime;
        $param_7 = $attempt;
        $param_8 = $course_code;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Submission successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
    }

    return $error;
}
