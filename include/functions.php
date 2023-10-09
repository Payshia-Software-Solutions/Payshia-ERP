<?php

date_default_timezone_set("Asia/Colombo");
function GetCities($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `district_id`, `name_en`, `name_si`, `name_ta`, `sub_name_en`, `sub_name_si`, `sub_name_ta`, `postcode`, `latitude`, `longitude` FROM `cities` ORDER BY `name_en`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetGrades($link)
{

    $ArrayResult = array();
    $sql = "SELECT `company_id`, `grade_id`, `grade_name`, `is_active`, `updated_by`, `created_at` FROM `master_grades`  ORDER BY `is_active` DESC, `grade_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['grade_id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveGrade($link, $company_id, $grade_name, $is_active, $updated_by, $updateKey)
{

    if ($updateKey == 0) {
        $sql = "INSERT INTO `master_grades`(`company_id`, `grade_name`, `is_active`, `updated_by`, `created_at`) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_grades` SET `company_id` = ?, `grade_name` = ?, `is_active` = ?, `updated_by` = ?, `created_at` = ? WHERE `grade_id` LIKE '$updateKey'";
    }
    $error = array();
    $CurrentTime = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $company_id;
        $param_2 = $grade_name;
        $param_3 = $is_active;
        $param_4 = $updated_by;
        $param_5 = $CurrentTime;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Grade saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

// Subjects
function GetSubjects($link)
{

    $ArrayResult = array();
    $sql = "SELECT `company_id`, `subject_id`, `subject_name`, `is_active`, `updated_by`, `created_at` FROM `master_subjects` ORDER BY `is_active` DESC, `subject_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['subject_id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveSubject($link, $company_id, $subject_name, $is_active, $updated_by, $updateKey)
{

    if ($updateKey == 0) {
        $sql = "INSERT INTO `master_subjects`(`company_id`, `subject_name`, `is_active`, `updated_by`, `created_at`) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_subjects` SET `company_id` = ?, `subject_name` = ?, `is_active` = ?, `updated_by` = ?, `created_at` = ? WHERE `subject_id` LIKE '$updateKey'";
    }
    $error = array();
    $CurrentTime = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_4, $param_5);

        // Set parameters
        $param_1 = $company_id;
        $param_2 = $subject_name;
        $param_3 = $is_active;
        $param_4 = $updated_by;
        $param_5 = $CurrentTime;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Grade saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


// Tutor
function GetTutors($link)
{

    $ArrayResult = array();
    $sql = "SELECT `company_id`, `tutor_id`, `tutor_name`, `is_active`, `updated_by`, `created_at`, `phone_number`, `subject_id`, `address_line1`, `address_line2`, `city_id`, `img_path`, `email_address`, `tutor_profile`, `class_category` FROM `master_tutor` ORDER BY `is_active` DESC, `tutor_name`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['tutor_id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveTutor($link, $company_id, $email_address, $tutor_name, $is_active, $updated_by, $phone_number, $subject_id, $address_line1, $address_line2, $city_id, $img_path, $updateKey, $tutor_profile, $class_category)
{
    if ($updateKey == 0) {
        $sql = "INSERT INTO `master_tutor`(`company_id`,`email_address`, `tutor_name`, `is_active`, `updated_by`, `phone_number`, `subject_id`, `address_line1`, `address_line2`, `city_id`, `img_path`, `created_at`, `tutor_profile`, `class_category`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_tutor` SET `company_id` = ?, `email_address` = ?, `tutor_name` = ?, `is_active` = ?, `updated_by` = ?, `phone_number` = ?, `subject_id` = ?, `address_line1` = ?, `address_line2` = ?, `city_id` = ?, `img_path` = ?, `created_at` = ?, `tutor_profile` = ?, `class_category` = ? WHERE `tutor_id` = '$updateKey'";
    }

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14);

        // Set parameters
        $param_1 = $company_id;
        $param_2 = $email_address;
        $param_3 = $tutor_name;
        $param_4 = $is_active;
        $param_5 = $updated_by;
        $param_6 = $phone_number;
        $param_7 = $subject_id;
        $param_8 = $address_line1;
        $param_9 = $address_line2;
        $param_10 = $city_id;
        $param_11 = $img_path;
        $param_12 = $current_time;
        $param_13 = $tutor_profile;
        $param_14 = $class_category;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Tutor data saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


// Class
function GetClasses($link)
{
    $ArrayResult = array();
    $sql = "SELECT `company_id`, `class_id`, `class_name`, `start_date`, `grade_id`, `subject_id`, `class_image`, `class_description`, `class_fee`, `class_category`, `updated_by`, `created_at`, `start_time`, `end_time`, `is_active`, `tutor_id` , `location`, `closed_at` FROM `master_class` ORDER BY `is_active` DESC, `class_name`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['class_id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveClass($link, $company_id, $class_name, $start_date, $grade_id, $subject_id, $class_image, $class_description, $class_fee, $class_category, $updated_by, $start_time, $end_time, $updateKey, $is_active, $tutor_id, $location)
{
    if ($updateKey == 0) {
        $sql = "INSERT INTO `master_class`(`company_id`, `class_name`, `start_date`, `grade_id`, `subject_id`, `class_image`, `class_description`, `class_fee`, `class_category`, `updated_by`, `start_time`, `end_time`, `created_at`, `is_active`, `tutor_id`, `location`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        if ($is_active == 2) {
            $sql = "UPDATE `master_class` SET `company_id` = ?, `class_name` = ?, `start_date` = ?, `grade_id` = ?, `subject_id` = ?, `class_image` = ?, `class_description` = ?, `class_fee` = ?, `class_category` = ?, `updated_by` = ?, `start_time` = ?, `end_time` = ?, `created_at` = ?, `is_active` = ?, `tutor_id` = ?, `location` = ?, `closed_at` = ? WHERE `class_id` = '$updateKey'";
        } else {
            $sql = "UPDATE `master_class` SET `company_id` = ?, `class_name` = ?, `start_date` = ?, `grade_id` = ?, `subject_id` = ?, `class_image` = ?, `class_description` = ?, `class_fee` = ?, `class_category` = ?, `updated_by` = ?, `start_time` = ?, `end_time` = ?, `created_at` = ?, `is_active` = ?, `tutor_id` = ?, `location` = ? WHERE `class_id` = '$updateKey'";
        }
    }

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        if ($is_active == 2) {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16, $param_17);

            $param_1 = $company_id;
            $param_2 = $class_name;
            $param_3 = $start_date;
            $param_4 = $grade_id;
            $param_5 = $subject_id;
            $param_6 = $class_image;
            $param_7 = $class_description;
            $param_8 = $class_fee;
            $param_9 = $class_category;
            $param_10 = $updated_by;
            $param_11 = $start_time;
            $param_12 = $end_time;
            $param_13 = $current_time;
            $param_14 = $is_active;
            $param_15 = $tutor_id;
            $param_16 = $location;
            $param_17 = $current_time;
        } else {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16);

            $param_1 = $company_id;
            $param_2 = $class_name;
            $param_3 = $start_date;
            $param_4 = $grade_id;
            $param_5 = $subject_id;
            $param_6 = $class_image;
            $param_7 = $class_description;
            $param_8 = $class_fee;
            $param_9 = $class_category;
            $param_10 = $updated_by;
            $param_11 = $start_time;
            $param_12 = $end_time;
            $param_13 = $current_time;
            $param_14 = $is_active;
            $param_15 = $tutor_id;
            $param_16 = $location;
        }


        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Class data saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}



// Tutor
function GetStudents($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `status`, `first_name`, `last_name`, `nic`, `student_number`, `address_line_1`, `address_line_2`, `city`, `postal_code`, `sex`, `phone_number`, `email_address`, `password`, `created_at`, `user_type`, `is_active`, `card_number`, `expire_date`, `img_path` FROM `user_account` ORDER BY `is_active` DESC, `student_number`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['student_number']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveUserAccount($link, $status, $first_name, $last_name, $nic, $student_number, $address_line_1, $address_line_2, $city, $postal_code, $sex, $phone_number, $email_address, $password, $user_type, $is_active, $card_number, $expire_date, $updateKey, $company_id, $img_path)
{

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($password != "") {
        if ($updateKey == 0) {
            $sql = "INSERT INTO `user_account` (`status`, `first_name`, `last_name`, `nic`, `student_number`, `address_line_1`, `address_line_2`, `city`, `postal_code`, `sex`, `phone_number`, `email_address`, `password`, `created_at`, `user_type`, `is_active`, `card_number`, `expire_date`, `company_id`, `img_path`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $Student = GetStudents($link)[$updateKey];
            $student_number = $Student['student_number'];
            $card_number = $Student['card_number'];
            $expire_date = $Student['expire_date'];

            $sql = "UPDATE `user_account` SET `status` = ?, `first_name` = ?, `last_name` = ?, `nic` = ?, `student_number` = ?, `address_line_1` = ?, `address_line_2` = ?, `city` = ?, `postal_code` = ?, `sex` = ?, `phone_number` = ?, `email_address` = ?, `password` = ?, `created_at`, `user_type` = ?, `is_active` = ?, `card_number` = ?, `expire_date` = ?, `company_id` = ? ,`img_path` = ? WHERE `student_number` = '$updateKey'";
        }

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16, $param_17, $param_18, $param_19, $param_20);

            $param_1 = $status;
            $param_2 = $first_name;
            $param_3 = $last_name;
            $param_4 = $nic;
            $param_5 = $student_number;
            $param_6 = $address_line_1;
            $param_7 = $address_line_2;
            $param_8 = $city;
            $param_9 = $postal_code;
            $param_10 = $sex;
            $param_11 = $phone_number;
            $param_12 = $email_address;
            $param_13 = password_hash($password, PASSWORD_DEFAULT);
            $param_14 = $current_time;
            $param_15 = $user_type;
            $param_16 = $is_active;
            $param_17 = $card_number;
            $param_18 = $expire_date;
            $param_19 = $company_id;
            $param_20 = $img_path;

            if (mysqli_stmt_execute($stmt_sql)) {
                $error = array('status' => 'success', 'message' => 'User account data saved successfully');
            } else {
                $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
            }

            mysqli_stmt_close($stmt_sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
    } else {
        $sql = "UPDATE `user_account` SET `status` = ?, `first_name` = ?, `last_name` = ?, `nic` = ?, `address_line_1` = ?, `address_line_2` = ?, `city` = ?, `postal_code` = ?, `sex` = ?, `phone_number` = ?, `email_address` = ?,`created_at` = ?, `user_type` = ?, `is_active` = ?, `company_id` = ? ,`img_path` = ? WHERE `student_number` = '$updateKey'";

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_14, $param_15, $param_16, $param_19, $param_20);

            $param_1 = $status;
            $param_2 = $first_name;
            $param_3 = $last_name;
            $param_4 = $nic;
            $param_6 = $address_line_1;
            $param_7 = $address_line_2;
            $param_8 = $city;
            $param_9 = $postal_code;
            $param_10 = $sex;
            $param_11 = $phone_number;
            $param_12 = $email_address;
            $param_14 = $current_time;
            $param_15 = $user_type;
            $param_16 = $is_active;
            $param_19 = $company_id;
            $param_20 = $img_path;

            if (mysqli_stmt_execute($stmt_sql)) {
                $error = array('status' => 'success', 'message' => 'User account data saved successfully');
            } else {
                $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
            }

            mysqli_stmt_close($stmt_sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
    }




    return json_encode($error);
}


function GenerateCardNumber($link)
{
    $GeneratedNumber = str_pad(mt_rand(0, 999999999999999), 16, '0', STR_PAD_LEFT);

    $sql = "SELECT `card_number` FROM `user_account` WHERE `card_number` LIKE '$GeneratedNumber'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        GenerateCardNumber($link);
    } else {
        return $GeneratedNumber;
    }
}

function GenerateIndexNumber($link)
{
    $GeneratedNumber = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);

    $sql = "SELECT `student_number` FROM `user_account` WHERE `student_number` LIKE '$GeneratedNumber'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        GenerateIndexNumber($link);
    } else {
        return 'ST' . $GeneratedNumber;
    }
}

function GetCardExpireDate($link)
{
    $future_date = date('Y-m-d', strtotime('+5 years'));
    return $future_date;
}

// Enrollment
function GetAllEnrollments($link)
{
    $ArrayResult = array();
    $sql = "SELECT `trans_id`, `company_id`, `student_number`, `class_id`, `joined_date`, `is_active`, `update_by`, `update_at` FROM `trans_enrollment` ORDER BY `trans_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['trans_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetUserEnrollments($link, $student_number)
{
    $ArrayResult = array();
    $sql = "SELECT `trans_id`, `company_id`, `student_number`, `class_id`, `joined_date`, `is_active`, `update_by`, `update_at` FROM `trans_enrollment` WHERE `student_number` LIKE '$student_number' AND `is_active` LIKE 1 ORDER BY `trans_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['trans_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetEnrollments($link, $ClassID)
{
    $ArrayResult = array();
    $sql = "SELECT `trans_id`, `company_id`, `student_number`, `class_id`, `joined_date`, `is_active`, `update_by`, `update_at` FROM `trans_enrollment` WHERE `class_id` LIKE '$ClassID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['trans_id']] = $row;
        }
    }
    return $ArrayResult;
}


function checkEnrollment($link, $class_id, $student_number)
{
    $sql = "SELECT `trans_id`, `company_id`, `student_number`, `class_id`, `joined_date`, `is_active`, `update_by`, `update_at` FROM `trans_enrollment` WHERE `class_id` LIKE '$class_id' AND `student_number` LIKE '$student_number' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        return true;
    }
}

function SaveEnrollment($link, $company_id, $student_number, $class_id, $joined_date, $is_active, $update_by, $updateKey)
{

    date_default_timezone_set("Asia/Colombo");
    if ($updateKey == 0) {
        $sql = "INSERT INTO `trans_enrollment` (`company_id`, `student_number`, `class_id`, `joined_date`, `is_active`, `update_by`, `update_at`) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    } else {
        $sql = "UPDATE `trans_enrollment` SET `company_id` = ?, `student_number` = ?, `class_id` = ?, `is_active` = ?, `update_by` = ? WHERE `trans_id` LIKE '$updateKey'";
    }

    $error = array();

    if ($stmt_sql = mysqli_prepare($link, $sql)) {

        if ($updateKey == 0) {
            mysqli_stmt_bind_param($stmt_sql, "ssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6);

            $param_1 = $company_id;
            $param_2 = $student_number;
            $param_3 = $class_id;
            $param_4 = $joined_date;
            $param_5 = $is_active;
            $param_6 = $update_by;
        } else {
            mysqli_stmt_bind_param($stmt_sql, "sssss", $param_1, $param_2, $param_3, $param_5, $param_6);

            $param_1 = $company_id;
            $param_2 = $student_number;
            $param_3 = $class_id;
            $param_5 = $is_active;
            $param_6 = $update_by;
        }
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Enrollment saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


// Schedule
function GetSchedule($link)
{
    $ArrayResult = array();
    $sql = "SELECT `ref_id`, `class_id`, `class_dates`, `held_category`, `start_time`, `end_time`, `hall`, `created_by`, `created_at`, `is_active` FROM `trans_schedule`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ref_id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveSchedule($link, $class_id, $class_dates, $held_category, $start_time, $end_time, $hall, $created_by, $updateKey, $is_active)
{
    if ($updateKey == 0) {
        $sql = "INSERT INTO `trans_schedule` (`class_id`, `class_dates`, `held_category`, `start_time`, `end_time`, `hall`, `created_by`, `created_at`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
    } else {
        $sql = "UPDATE `trans_schedule` SET `class_id` = ?, `class_dates` = ?, `held_category` = ?, `start_time` = ?, `end_time` = ?, `hall` = ?, `created_by` = ?, `is_active` = ? WHERE `ref_id` LIKE '$updateKey'";
    }

    $error = array();

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8);

        $param_1 = $class_id;
        $param_2 = $class_dates;
        $param_3 = $held_category;
        $param_4 = $start_time;
        $param_5 = $end_time;
        $param_6 = $hall;
        $param_7 = $created_by;
        $param_8 = $is_active;

        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Schedule data saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function SavePaymentRecord($link, $company_id, $class_id, $student_number, $month, $year, $category, $payment_date, $updated_by, $is_active, $payment_type, $updateKey, $payment_amount)
{

    if ($updateKey == 0) {
        $sql = "INSERT INTO `payment_records` (`company_id`, `class_id`, `student_number`, `month`, `year`, `category`, `payment_date`, `updated_by`, `updated_at`, `is_active`, `payment_type`,`payment_amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `payment_records` SET `company_id` = ?,  `class_id` = ?, `student_number` = ?, `month` = ?, `year` = ?, `category` = ?, `payment_date` = ?, `updated_by` = ?, `updated_at` = ?, `is_active` = ?, `payment_type` = ?, `payment_amount` = ? WHERE `ref_id` = '$updateKey'";
    }

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssss", $company_id, $class_id, $student_number, $month, $year, $category, $payment_date, $updated_by, $current_time, $is_active, $payment_type, $payment_amount);

        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Payment record saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}



function GetPayments($link)
{
    $ArrayResult = array();
    $sql = "SELECT `company_id`, `ref_id`, `class_id`, `student_number`, `month`, `year`, `category`, `payment_date`, `updated_by`, `updated_at`, `is_active`, `payment_type`, `payment_amount` FROM `payment_records`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ref_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetClassAttendance($link, $class_id, $date)
{
    $ArrayResult = array();
    $sql = "SELECT `company_id`, `trans_id`, `student_number`, `class_id`, `date`, `attendance`, `created_by`, `created_at`, `is_active` FROM `trans_attendance` WHERE `class_id` LIKE '$class_id' AND `date` LIKE '$date'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['trans_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetAllAttendanceByDate($link, $date)
{
    $ArrayResult = array();
    $sql = "SELECT `company_id`, `trans_id`, `student_number`, `class_id`, `date`, `attendance`, `created_by`, `created_at`, `is_active` FROM `trans_attendance` WHERE `date` LIKE '$date'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['trans_id']] = $row;
        }
    }
    return $ArrayResult;
}


function CheckAttendance($link, $StudentNumber, $company_id, $classID, $TodayDate)
{
    $sql = "SELECT `company_id`, `trans_id`, `student_number`, `class_id`, `date`, `attendance`, `created_by`, `created_at`, `is_active` FROM `trans_attendance`  WHERE `student_number` LIKE '$StudentNumber' AND `class_id` LIKE '$classID' AND `date` LIKE '$TodayDate' AND `company_id` LIKE '$company_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        return "Marked";
    } else {
        return "Absent";
    }
}


function CheckPayments($link, $StudentNumber, $Month, $Year, $company_id, $classID)
{
    $sql = "SELECT `company_id`, `ref_id`, `class_id`, `student_number`, `month`, `year`, `category`, `payment_date`, `updated_by`, `updated_at`, `is_active`, `payment_type`, `payment_amount` FROM `payment_records` WHERE `student_number` LIKE '$StudentNumber' AND `month` LIKE '$Month' AND `year` LIKE '$Year' AND `class_id` LIKE '$classID' AND `company_id` LIKE '$company_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        return "Paid";
    } else {
        return "Not Paid";
    }
}



function TotalPaymentsByDate($link, $given_date, $company_id)
{
    $TotalPayments = 0;
    $sql = "SELECT SUM(payment_amount) AS `TotalPayments` FROM `payment_records` WHERE `payment_date` LIKE '$given_date' AND `company_id` LIKE '$company_id' AND `is_active` = 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $TotalPayments = $row['TotalPayments'];
        }
    }

    return $TotalPayments;
}

function SaveAttendance($link, $student_number, $class_id, $date, $attendance, $created_by, $is_active, $updateKey, $company_id)
{

    if ($updateKey == 0) {
        $sql = "INSERT INTO `trans_attendance` (`company_id`, `student_number`, `class_id`, `date`, `attendance`, `created_by`, `is_active`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `trans_attendance` SET `company_id` = ?`student_number` = ?, `class_id` = ?, `date` = ?, `attendance` = ?, `created_by` = ?, `is_active` = ?, `created_at` = ? WHERE `trans_id` LIKE '$updateKey'";
    }

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $company_id, $student_number, $class_id, $date, $attendance, $created_by, $is_active, $current_time);

        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Attendance saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

function AddMyChild($link, $company_id, $student_number, $parent_account, $is_active, $updated_by, $updateKey)
{

    if ($updateKey == 0) {
        $sql = "INSERT INTO `trans_own_student`( `company_id`, `student_number`, `parent_account`, `is_active`, `updated_by`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `trans_own_student` SET `company_id` = ?, `student_number` = ?, `parent_account` = ?, `is_active` = ?, `updated_by` = ?, `updated_at` = ? WHERE `ref_no` LIKE '$updateKey'";
    }
    $error = array();
    $CurrentTime = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "ssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6);

        // Set parameters
        $param_1 = $company_id;
        $param_2 = $student_number;
        $param_3 = $parent_account;
        $param_4 = $is_active;
        $param_5 = $updated_by;
        $param_6 = $CurrentTime;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Grade saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function GetOwnedStudents($link)
{

    $ArrayResult = array();
    $sql = "SELECT `company_id`, `ref_no`, `student_number`, `parent_account`, `is_active`, `updated_by`, `updated_at` FROM `trans_own_student`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['ref_no']] = $row;
        }
    }
    return $ArrayResult;
}

function checkOwnedStatus($link, $student_number, $parent_account)
{
    $sql = "SELECT `company_id`, `ref_no`, `student_number`, `parent_account`, `is_active`, `updated_by`, `updated_at` FROM `trans_own_student` WHERE `parent_account` LIKE '$parent_account' AND `student_number` LIKE '$student_number' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        return true;
    }
}

function GetOwnedID($link, $student_number, $parent_account)
{
    $owned_id = 0;
    $sql = "SELECT `company_id`, `ref_no`, `student_number`, `parent_account`, `is_active`, `updated_by`, `updated_at` FROM `trans_own_student` WHERE `parent_account` LIKE '$parent_account' AND `student_number` LIKE '$student_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $owned_id = $row['ref_no'];
        }
    }

    return $owned_id;
}


function GetEnrolledStudentCount($link, $ClassID, $postMonth, $postYear)
{
    $date = new DateTime("$postMonth $postYear");
    $date->modify('last day of this month');
    $lastDate = $date->format('Y-m-d');

    $EnrolledStudentCount = 0;
    $sql = "SELECT COUNT(trans_id) AS `enrolled_count` FROM `trans_enrollment` WHERE `class_id` = '$ClassID' AND `is_active` = 1 AND joined_date <= '$lastDate'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $EnrolledStudentCount = $row['enrolled_count'];
        }
    }

    return $EnrolledStudentCount;
}


function NoCards($link, $ClassID, $CardStatus, $company_id, $postMonth, $postYear)
{
    $CardCount = 0;
    $sql = "SELECT COUNT(payment_amount) AS `CardCount` FROM `payment_records` WHERE `category` LIKE '$CardStatus' AND `class_id` LIKE '$ClassID' AND `company_id` LIKE '$company_id' AND `is_active` = 1  AND `month` LIKE '$postMonth' AND `year` LIKE '$postYear'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $CardCount = $row['CardCount'];
        }
    }

    return $CardCount;
}

function NoCardsPayments($link, $ClassID, $CardStatus, $company_id, $postMonth, $postYear, $AllStatus)
{
    $Payment = 0;

    if ($AllStatus == 1) {
        $sql = "SELECT SUM(payment_amount) AS `Payment` FROM `payment_records` WHERE `category` LIKE '$CardStatus' AND `class_id` LIKE '$ClassID' AND `company_id` LIKE '$company_id' AND `is_active` = 1 AND `month` LIKE '$postMonth' AND `year` LIKE '$postYear'";
    } else {
        $sql = "SELECT SUM(payment_amount) AS `Payment` FROM `payment_records` WHERE `category` LIKE '$CardStatus' AND `class_id` LIKE '$ClassID' AND `company_id` LIKE '$company_id' AND `is_active` = 1";
    }


    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Payment = $row['Payment'];
        }
    }

    return $Payment;
}

function GetAccountsByTypes($link, $AccountType)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `status`, `first_name`, `last_name`, `nic`, `student_number`, `address_line_1`, `address_line_2`, `city`, `postal_code`, `sex`, `phone_number`, `email_address`, `password`, `created_at`, `user_type`, `is_active`, `card_number`, `expire_date`, `img_path` FROM `user_account` WHERE `user_type` LIKE '$AccountType' ORDER BY `is_active` DESC, `student_number`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['student_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetTutorPayments($link)
{
    $ArrayResult = array();
    $sql = "SELECT `ref_no`, `transaction_id`, `account_number`, `amount`, `payment_type`, `date_time`, `created_by`, `created_at`, `is_active`, `current_status` FROM `trans_tutor_payments` ORDER BY `ref_no` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['transaction_id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetPreviousPayments($link, $StudentNumber)
{
    $amount = 0;
    $sql = "SELECT COALESCE(SUM(amount), 0) AS `pre_payments` FROM `trans_tutor_payments` WHERE `account_number` LIKE '$StudentNumber' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $amount = $row['pre_payments'];
        }
    } else {
        $amount = 0;
    }
    return $amount;
}


function GetTutorPaymentsByTutor($link, $account_number)
{
    $ArrayResult = array();
    $sql = "SELECT `ref_no`, `transaction_id`, `account_number`, `amount`, `payment_type`, `date_time`, `created_by`, `created_at`, `is_active`, `current_status` FROM `trans_tutor_payments` WHERE `account_number` LIKE '$account_number' ORDER BY `ref_no`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['transaction_id']] = $row;
        }
    }
    return $ArrayResult;
}


function saveTransaction($link, $accountNumber, $amount, $paymentType, $createdBy, $isActive, $currentStatus)
{
    $transactionId = time();

    // Prepare the SQL statement
    $sql = "INSERT INTO trans_tutor_payments (transaction_id, account_number, amount, payment_type, date_time, created_by, created_at, is_active, current_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $error = array();
    $CurrentTime = date("Y-m-d H:i:s");

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssssssss", $transactionId, $accountNumber, $amount, $paymentType, $CurrentTime, $createdBy, $CurrentTime, $isActive, $currentStatus);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Payment saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function formatNumber($number, $numerator)
{
    if ($number >= 10000) {
        return number_format($number / 1000, $numerator) . 'k';
    } else {
        return number_format($number, 2);
    }
}


function processClassDates($closingDate, $monthStartDate, $classStartDate)
{
    // Convert dates to timestamps
    $closingTimestamp = strtotime($closingDate);
    $classStartTimestamp = strtotime($classStartDate);

    // Extract month and year
    $closingMonth = date('m', $closingTimestamp);
    $closingYear = date('Y', $closingTimestamp);
    // $monthStartMonth = date('m', $monthStartTimestamp);
    // $monthStartYear = date('Y', $monthStartTimestamp);
    $classStartMonth = date('m', $classStartTimestamp);
    $classStartYear = date('Y', $classStartTimestamp);

    $classStartTimestamp = strtotime($classStartYear . '-' . $classStartMonth . '-01');
    $monthStartTimestamp = strtotime($monthStartDate);

    $closingTimestamp = strtotime($closingYear . '-' . $closingMonth . '-01');
    $monthStartTimestamp = strtotime($monthStartDate);
    if ($closingDate != "") {
        return ($classStartTimestamp <= $monthStartTimestamp && $closingTimestamp >= $monthStartTimestamp);
    } else {
        return ($classStartTimestamp <= $monthStartTimestamp);
    }
}


function GetClassDates($link, $ClassID)
{
    $ArrayResult = array();
    $sql = "SELECT `class_id`, `date`, `is_active` FROM `trans_attendance` WHERE `class_id` LIKE '$ClassID' AND `is_active` LIKE 1 GROUP BY `date`  ORDER BY `date` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function SaveResource($link, $resource_id, $company_id, $class_id, $class_month, $type, $main_topic, $content, $topic, $is_active, $created_by)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($resource_id == 0) {
        $sql = "INSERT INTO `class_resoucres` (`compnay_id`, `class_id`, `class_month`, `type`, `main_topic`, `content`, `topic`, `is_active`, `created_by`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `class_resoucres` SET `compnay_id` = ?, `class_id` = ?, `class_month` = ?, `type` = ?, `main_topic` = ?, `content` = ?, `topic` = ?, `is_active` = ?, `created_by` = ?, `created_at` = ? WHERE `resource_id` = ?";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        if ($resource_id == 0) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssss", $company_id, $class_id, $class_month, $type, $main_topic, $content, $topic, $is_active, $created_by, $current_time);
        } else {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $company_id, $class_id, $class_month, $type, $main_topic, $content, $topic, $is_active, $created_by, $current_time, $resource_id);
        }

        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Resource saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function createFolderIfNotExists($folderPath)
{
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath, 0777, true)) {
            return true; // Folder created successfully
        } else {
            return false; // Failed to create folder
        }
    } else {
        return true; // Folder already exists
    }
}


function GetClassResources($link, $ClassID)
{
    $ArrayResult = array();
    $sql = "SELECT `resource_id`, `compnay_id`, `class_id`, `class_month`, `type`, `main_topic`, `content`, `topic`, `is_active`, `created_by`, `created_at` FROM `class_resoucres` WHERE `class_id` LIKE '$ClassID' ORDER BY `resource_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function GetClassResourceMonths($link, $ClassID)
{
    $ArrayResult = array();
    $sql = "SELECT `resource_id`, `compnay_id`, `class_id`, `class_month`, `type`, `main_topic`, `content`, `topic`, `is_active`, `created_by`, `created_at` FROM `class_resoucres` WHERE `class_id` LIKE '$ClassID' GROUP BY `class_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}
