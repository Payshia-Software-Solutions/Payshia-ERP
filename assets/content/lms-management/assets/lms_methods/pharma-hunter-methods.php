<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function GetSource($sourceType)
{

    if ($sourceType == "racks") {
        $sql = "SELECT `id`, `name`, `is_active`, `created_by`, `created_at` FROM `hp_racks`";
    } else if ($sourceType == 'dosageForm') {
        $sql = "SELECT `id`, `name`, `is_active`, `created_by`, `created_at` FROM `hp_dosage_forms`";
    } else if ($sourceType == 'drugCategory') {
        $sql = "SELECT `id`, `name`, `is_active`, `created_by`, `created_at` FROM `hp_categories`";
    } else if ($sourceType == 'drugGroup') {
        $sql = "SELECT `id`, `name`, `is_active`, `created_by`, `created_at` FROM `hp_drug_types`";
    }
    global $lms_link;
    $ArrayResult = array();

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetProMedicine()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `medicine_name`, `image_url`, `dosage_form_id`, `category_id`, `drug_type_id`, `rack_id`, `is_active`, `created_by`, `created_at` FROM `hp_medicines`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetProMedicineByID($lms_link, $medicineId)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `medicine_name`, `image_url`, `dosage_form_id`, `category_id`, `drug_type_id`, `rack_id`, `is_active`, `created_by`, `created_at` FROM `hp_medicines`  WHERE `id` LIKE '$medicineId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function savedAnswers($lms_link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `index_number`, `category_id`, `medicine_id`, `rack_id`, `dosage_id`, `drug_type`, `answer_status`, `created_at`, `score`, `score_type`, `attempts` FROM `hp_save_answer`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function savedAnswersByUser($lms_link, $loggedUser)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `index_number`, `category_id`, `medicine_id`, `rack_id`, `dosage_id`, `drug_type`, `answer_status`, `created_at`, `score`, `score_type`, `attempts` FROM `hp_save_answer` WHERE `index_number` LIKE '$loggedUser'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function savedAnswersByUserMedicine($lms_link, $loggedUser, $medicine_id)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `index_number`, `category_id`, `medicine_id`, `rack_id`, `dosage_id`, `drug_type`, `answer_status`, `created_at`, `score`, `score_type`, `attempts` FROM `hp_save_answer` WHERE `index_number` LIKE '$loggedUser' AND `medicine_id` LIKE '$medicine_id'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}




function SaveAnswerToDatabase($lms_link, $LoggedUser, $MedicineID, $RackID, $DosageID, $AnswerStatus, $Mark, $AttemptCount, $ScoreType, $CategoryID, $DrugGroupID)
{
    $sql = "INSERT INTO `hp_save_answer`(`index_number`, `medicine_id`, `rack_id`, `dosage_id`, `answer_status`, `score`, `attempts`, `score_type`, `category_id`, `drug_type`) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "ssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10);

        // Set parameters
        $param_1 = $LoggedUser;
        $param_2 = $MedicineID;
        $param_3 = $RackID;
        $param_4 = $DosageID;
        $param_5 = $AnswerStatus;
        $param_6 = $Mark;
        $param_7 = $AttemptCount;
        $param_8 = $ScoreType;
        $param_9 = $CategoryID;
        $param_10 = $DrugGroupID;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            return "Answer Saved";
        } else {
            return "Answer Not Saved";
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        return "Answer Not Saved";
    }
}

function GetProAllMedicines($lms_link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `medicine_name`, `image_url`, `dosage_form_id`, `category_id`, `drug_type_id`, `rack_id`, `is_active`, `created_by`, `created_at` FROM `hp_medicines` WHERE `is_active` LIKE '1'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetProMedicines($lms_link, $CourseCode)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `CourseCode`, `MediID`, `status` FROM `hp_course_medicine` WHERE `status` LIKE 'Active' AND `CourseCode` LIKE '$CourseCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['MediID']] = $row["MediID"];
        }
    }
    return $ArrayResult;
}

function GetProSubmissions($lms_link, $CountAnswer, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT `medicine_id` FROM `hp_save_answer` WHERE `index_number` LIKE '$UserName' GROUP BY `index_number`, `medicine_id` HAVING COUNT(*) >= $CountAnswer ORDER BY COUNT(*) DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row["medicine_id"];
        }
    }
    return $ArrayResult;
}



function GetHunterProAttempts($lms_link)
{
    $ArrayResult = 0;
    $sql = "SELECT `id`, `setting`, `value` FROM `settings` WHERE `setting` LIKE 'HunterProAttempt'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row["value"];
        }
    }
    return $ArrayResult;
}


function GetAttemptResult($lms_link, $IndexNumber)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(score) AS `score` FROM `hp_save_answer` WHERE `index_number` LIKE '$IndexNumber'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row["score"];
        }
    }
    return $ArrayResult;
}
function getCurrentTimeOfDay()
{
    date_default_timezone_set("Asia/Colombo");
    $currentTime = date('H:i'); // Get the current time in the format 'HH:MM'
    $morningStart = '06:00';
    $afternoonStart = '12:00';
    $eveningStart = '17:00';
    $nightStart = '20:00';

    if ($currentTime >= $morningStart && $currentTime < $afternoonStart) {
        return 'Morning';
    } elseif ($currentTime >= $afternoonStart && $currentTime < $eveningStart) {
        return 'Afternoon';
    } elseif ($currentTime >= $eveningStart && $currentTime < $nightStart) {
        return 'Evening';
    } else {
        return 'Night';
    }
}


function GetHunterProAttemptCount($lms_link, $IndexNumber)
{
    $ArrayResult = 0;
    $sql = "SELECT COUNT(score) AS `attempt_count` FROM `hp_save_answer` WHERE `index_number` LIKE '$IndexNumber'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row["attempt_count"];
        }
    }
    return $ArrayResult;
}


function HunterMedicines()
{
    global $lms_link;

    $sql = "SELECT * FROM `hunter_medicine` WHERE `active_status` NOT LIKE 'Deleted'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function HunterSavedAnswers()
{
    global $lms_link;

    $sql = "SELECT 
                `index_number`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' THEN 1 ELSE 0 END) AS `correct_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Wrong' THEN 1 ELSE 0 END) AS `incorrect_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' AND `score_type` LIKE 'Jem' THEN  1 ELSE 0 END) AS `gem_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' AND `score_type` LIKE 'Coin' THEN 1 ELSE 0 END) AS `coin_count` 
            FROM 
                `hunter_saveanswer` 
            GROUP BY 
                `index_number`";

    $result = $lms_link->query($sql);
    $ArrayResult = array(); // Initialize the array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['index_number']] = $row;
        }
    }
    return $ArrayResult;
}

function HunterSavedAnswersByUser($studentNumber)
{
    global $lms_link;

    $sql = "SELECT 
                `index_number`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' THEN 1 ELSE 0 END) AS `correct_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Wrong' THEN 1 ELSE 0 END) AS `incorrect_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' AND `score_type` LIKE 'Jem' THEN  1 ELSE 0 END) AS `gem_count`, 
                SUM(CASE WHEN `answer_status` LIKE 'Correct' AND `score_type` LIKE 'Coin' THEN 1 ELSE 0 END) AS `coin_count` 
            FROM 
                `hunter_saveanswer` 
            WHERE
                `index_number` LIKE '$studentNumber'";

    $result = $lms_link->query($sql);
    $ArrayResult = array(); // Initialize the array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['index_number']] = $row;
        }
    }
    return $ArrayResult;
}
