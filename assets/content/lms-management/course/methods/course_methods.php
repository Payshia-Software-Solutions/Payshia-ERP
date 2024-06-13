<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);


function SaveCourse($courseData)
{
    global $lms_pdo;

    try {
        if ($courseData['updateStatus'] == 0) {
            $stmt = $lms_pdo->prepare("INSERT INTO `parent_main_course` (`course_name`, `course_code`, `instructor_id`, `course_description`, `course_duration`, `course_fee`, `registration_fee`, `created_at`, `created_by`, `display`, `course_img`, `certification`, `mini_description`, `is_active`, `lecture_count`, `hours_per_lecture`, `assessments`, `language`, `quizzes`, `skill_level`, `head_count`, `module_list`) VALUES (:course_name, :course_code, :instructor_id, :course_description, :course_duration, :course_fee, :registration_fee, :created_at, :created_by, :display, :course_img, :certification, :mini_description, :is_active, :lecture_count, :hours_per_lecture, :assessments, :language, :quizzes, :skill_level, :head_count, :module_list)");
        } else {
            $stmt = $lms_pdo->prepare("UPDATE `parent_main_course` SET `course_name`= :course_name, `course_code`= :course_code, `instructor_id`= :instructor_id, `course_description`= :course_description, `course_duration`= :course_duration, `course_fee`= :course_fee, `registration_fee`= :registration_fee, `created_at`= :created_at, `created_by`= :created_by, `display`= :display, `course_img`= :course_img, `certification`= :certification, `mini_description`= :mini_description, `is_active`= :is_active, `lecture_count`= :lecture_count, `hours_per_lecture`= :hours_per_lecture, `assessments`= :assessments, `language`= :language, `quizzes`= :quizzes, `skill_level`= :skill_level, `head_count`= :head_count, `module_list`= :module_list WHERE `course_code` = :course_code");
        }

        $stmt->bindParam(':course_name', $courseData['course_name']);
        $stmt->bindParam(':course_code', $courseData['course_code']);
        $stmt->bindParam(':instructor_id', $courseData['instructor_id']);
        $stmt->bindParam(':course_description', $courseData['course_description']);
        $stmt->bindParam(':course_duration', $courseData['course_duration']);
        $stmt->bindParam(':course_fee', $courseData['course_fee']);
        $stmt->bindParam(':registration_fee', $courseData['registration_fee']);
        $stmt->bindParam(':created_at', $courseData['created_at']);
        $stmt->bindParam(':created_by', $courseData['created_by']);
        $stmt->bindParam(':display', $courseData['display']);
        $stmt->bindParam(':course_img', $courseData['course_img']);
        $stmt->bindParam(':certification', $courseData['certification']);
        $stmt->bindParam(':mini_description', $courseData['mini_description']);
        $stmt->bindParam(':is_active', $courseData['is_active']);
        $stmt->bindParam(':lecture_count', $courseData['lecture_count']);
        $stmt->bindParam(':hours_per_lecture', $courseData['hours_per_lecture']);
        $stmt->bindParam(':assessments', $courseData['assessments']);
        $stmt->bindParam(':language', $courseData['language']);
        $stmt->bindParam(':quizzes', $courseData['quizzes']);
        $stmt->bindParam(':skill_level', $courseData['skill_level']);
        $stmt->bindParam(':head_count', $courseData['head_count']);
        $stmt->bindParam(':module_list', $courseData['module_list']);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Course saved successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}

function GetParentCourses()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `parent_main_course` ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row;
        }
    }
    return $ArrayResult;
}


function GenerateNewCourseCode()
{
    $courseList = GetParentCourses();
    return 'CS' . str_pad(count($courseList) + 1, 4, '0', STR_PAD_LEFT);
}
