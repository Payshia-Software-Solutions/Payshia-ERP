<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function GetReaderPrescriptions()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `pres_name`, `difficulty`, `created_at`, `created_by`, `image_path`, `active_status`, `PresHelp`, `prescription_question`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer` FROM `reader_medicine`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetPrescriptionSubmissions($userId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `reader_attempts` WHERE `user_id` LIKE '$userId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetPrescriptionCorrectSubmissions($userId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `reader_attempts` WHERE `user_id` LIKE '$userId' AND `answer_status` LIKE 'Correct'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetPrescriptionsIdList()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `pres_name`, `difficulty`, `created_at`, `created_by`, `image_path`, `active_status`, `PresHelp` FROM `reader_medicine` WHERE `active_status` LIKE 'Active'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row['id'];
        }
    }
    return $ArrayResult;
}


function GetPrescriptionSubmissionsIdList($userId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `pres_id`, `user_id`, `created_at`, `difficulty` FROM `reader_attempts` WHERE `user_id` LIKE '$userId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row['pres_id'];
        }
    }
    return $ArrayResult;
}

function GetPrescriptionCorrectSubmissionsIdList($userId)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `pres_id`, `user_id`, `created_at`, `difficulty` FROM `reader_attempts` WHERE `user_id` LIKE '$userId'  AND `answer_status` LIKE 'Correct'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row['pres_id'];
        }
    }
    return $ArrayResult;
}


function SaveNewPrescription($pres_name, $difficulty, $created_by, $image_path, $active_status, $PresHelp, $prescription_question, $answer_1, $answer_2, $answer_3, $answer_4, $correct_answer, $prescriptionId)
{
    global $lms_link;
    global $pdo;

    try {
        $current_time = date("Y-m-d H:i:s");

        if ($prescriptionId == 0) {
            $stmt = $pdo->prepare("INSERT INTO reader_medicine (`pres_name`, `difficulty`, `created_at`, `created_by`, `image_path`, `active_status`, `PresHelp`, `prescription_question`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer`) VALUES (:pres_name, :difficulty, :created_at, :created_by, :image_path, :active_status, :PresHelp, :prescription_question, :answer_1, :answer_2, :answer_3, :answer_4, :correct_answer)");
        } else {
            $stmt = $pdo->prepare("UPDATE reader_medicine  SET `pres_name` = :pres_name, `difficulty`= :difficulty, `created_at`= :created_at, `created_by`= :created_by, `image_path`= :image_path, `active_status`= :active_status, `PresHelp`= :PresHelp, `prescription_question`= :prescription_question, `answer_1`= :answer_1, `answer_2`= :answer_2, `answer_3`= :answer_3, `answer_4`= :answer_4, `correct_answer`= :correct_answer WHERE `id` = $prescriptionId");
        }

        $stmt->bindParam(':pres_name', $pres_name);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':created_at', $current_time);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':active_status', $active_status);
        $stmt->bindParam(':PresHelp', $PresHelp);
        $stmt->bindParam(':prescription_question', $prescription_question);
        $stmt->bindParam(':answer_1', $answer_1);
        $stmt->bindParam(':answer_2', $answer_2);
        $stmt->bindParam(':answer_3', $answer_3);
        $stmt->bindParam(':answer_4', $answer_4);
        $stmt->bindParam(':correct_answer', $correct_answer);

        $stmt->execute();

        return array('status' => 'success', 'message' => ' Prescription Saved successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function ValidateAnswer($prescriptionId, $loggedUser, $selectedAnswer)
{
    global $lms_link;
    $questionDetails = GetReaderPrescriptions()[$prescriptionId];
    $userSubmissions = GetPrescriptionSubmissions($loggedUser);
    $correctAnswer = $questionDetails['correct_answer'];
    $difficultyMode = $questionDetails['difficulty'];

    if ($correctAnswer === $selectedAnswer) {
        $answerStatus = "Correct";
        $answerGrade = 10;
    } else {
        $answerStatus = "In-Correct";
        $answerGrade = -5;
    }

    $sql = "SELECT `pres_id` FROM `reader_attempts` WHERE `pres_id` LIKE '$prescriptionId' AND `user_id` LIKE '$loggedUser' AND `answer_status` LIKE 'Correct'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        $error = array('status' => 'error', 'message' => 'Answer already saved', 'answerStatus' => $answerStatus, 'answerGrade' => $answerGrade);
    } else {
        $sql = "INSERT INTO `reader_attempts`( `user_id`, `pres_id`, `selected_answer`, `score`, `answer_status`, `difficulty`) VALUES  (?, ?, ?, ?, ?, ?)";
        if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt_sql, "ssssss", $loggedUser, $prescriptionId, $selectedAnswer, $answerGrade, $answerStatus, $difficultyMode);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt_sql)) {
                $error = array('status' => 'success', 'message' => 'Answer Saved successfully', 'answerStatus' => $answerStatus, 'answerGrade' => $answerGrade);
            } else {
                $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error, 'answerStatus' => $answerStatus, 'answerGrade' => $answerGrade);
            }

            // Close statement
            mysqli_stmt_close($stmt_sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error, 'answerStatus' => $answerStatus, 'answerGrade' => $answerGrade);
        }
    }

    return $error;
}


function GetReaderOverallGrade($loggedUser)
{

    $GradeArray = array();
    $overallGrade = 0;
    $gradeForCorrect = 10;
    $gradeForInCorrect = -5;

    $prescriptionList = GetReaderPrescriptions();
    $answerSubmissions = GetPrescriptionSubmissions($loggedUser);

    // var_dump($answerSubmissions);
    // Use array_filter to filter out 'Active' questions
    $activePrescriptions = array_filter($prescriptionList, function ($prescription) {
        return $prescription['active_status'] === 'Active';
    });

    $correctAnswers = array_filter($answerSubmissions, function ($answerSubmission) {
        return $answerSubmission['answer_status'] === 'Correct';
    });

    $InCorrectAnswers = array_filter($answerSubmissions, function ($answerSubmission) {
        return $answerSubmission['answer_status'] === 'In-Correct';
    });


    $totalAvailableCount = count($activePrescriptions);
    $correctCount = count($correctAnswers);
    $inCorrectCount = count($InCorrectAnswers);

    $correctScore = $correctCount * $gradeForCorrect;
    $incorrectScore = $inCorrectCount * $gradeForInCorrect;
    $finalScore = $correctCount - $incorrectScore;
    $totalPossibleGrade = $totalAvailableCount * $gradeForCorrect;

    if ($totalAvailableCount > 0) {
        $overallGrade = ($finalScore / $totalPossibleGrade) * 100;
    }

    $GradeArray = array(
        'correctScore' => $correctScore,
        'incorrectScore' => $incorrectScore,
        'finalScore' => $finalScore,
        'totalPossibleGrade' => $totalPossibleGrade,
        'overallGrade' => $overallGrade
    );

    return $GradeArray;
}


function GetAllUserReaderCounts()
{

    $arrayResult = array();
    global $lms_link;

    $sql = "SELECT * FROM `reader_attempts` GROUP BY `user_id`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $arrayResult[$row['user_id']] = $row;
        }
    }

    return $arrayResult;
}
