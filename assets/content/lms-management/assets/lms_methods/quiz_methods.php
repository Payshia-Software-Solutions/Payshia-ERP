<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);




function GetQuizTopics()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = 'SELECT `quiz_topicID`, `topicName`, `active_status`, `created_at` FROM `quiz-topics`';
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['quiz_topicID']] = $row;
        }
    }

    return $ArrayResult;
}



function GetQuiz($topicId)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `lesson_id`, `course_code`, `question_id`, `question`, `created_by`, `created_at`, `question_status`, `question_content`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer` FROM `lesson_questions` WHERE `lesson_id` LIKE '$topicId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['question_id']] = $row;
        }
    }

    return $ArrayResult;
}


function GetQuestion($topicId, $questionId)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `lesson_id`, `course_code`, `question_id`, `question`, `created_by`, `created_at`, `question_status`, `question_content`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer` FROM `lesson_questions` WHERE `lesson_id` LIKE '$topicId' AND `question_id` LIKE '$questionId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['question_id']] = $row;
        }
    }

    return $ArrayResult[$questionId];
}


function GetQuizSubmissionByUser($loggedUser)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `user_id`, `question_id`, `selected_answer`, `score`, `answer_status`, `created_at` FROM `lesson_questions_submition` WHERE `user_id` LIKE '$loggedUser'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}


function GetQuizTopicsByCourse($courseCode)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `topicID`, `CourseCode`, `created_at`, `created_by`, `active_status` FROM `quiz_topic_course` WHERE `CourseCode` LIKE '$courseCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['topicID']] = $row;
        }
    }

    return $ArrayResult;
}


function GetOverallGrade($loggedUser, $courseCode)
{
    $overallGrade = 0;
    $quizTopicsByCourse = GetQuizTopicsByCourse($courseCode);

    if (!empty($quizTopicsByCourse)) {
        foreach ($quizTopicsByCourse as $selectedArray) {
            $quizId = $selectedArray['topicID'];
            $gradePerCorrectAttempt = 10;
            $gradePerInCorrectAttempt = 5;
            $quizGrade = $correctScore = $inCorrectScore = $correctAttemptCount = $inCorrectAttemptCount = 0;

            $quizSubmissions = GetQuizSubmissionByUser($loggedUser);

            // Use array_filter to filter out 'Active' Topics
            $activeTopics = array_filter($quizTopicsByCourse, function ($topic) {
                return $topic['active_status'] === 'Active';
            });

            // Use array_filter to filter out 'Active' questions
            $activeQuestions = array_filter(GetQuiz($quizId), function ($question) {
                return $question['question_status'] === 'Active';
            });

            $questionCount = count($activeQuestions);

            if (!empty($activeQuestions)) {
                foreach ($activeQuestions as $selectedArray) {

                    $questionId = $selectedArray['question_id'];

                    if (!empty($quizSubmissions)) {
                        foreach ($quizSubmissions as $selectedAnswer) {
                            $answerQuestionId = $selectedAnswer['question_id'];
                            $answerResult = $selectedAnswer['answer_status'];

                            if ($questionId == $answerQuestionId) {
                                if ($answerResult == "Correct") {
                                    $correctScore += $gradePerCorrectAttempt;
                                    $correctAttemptCount += 1;
                                } else {
                                    $inCorrectScore += $gradePerInCorrectAttempt;
                                    $inCorrectAttemptCount += 1;
                                }
                            }
                        }
                    }
                }
            }


            if ($questionCount > 0) {
                $quizGrade = number_format((($correctScore - $inCorrectScore) / ($questionCount * $gradePerCorrectAttempt)) * 100, 2);
            }
            $overallGrade += $quizGrade;
        }

        if (count($activeTopics) > 0) {
            $overallGrade = ($overallGrade / (count($activeTopics) * 100)) * 100;
        }
    }



    return $overallGrade;
}



function GetGradeByQuiz($quizId, $loggedUser)
{
    $gradePerCorrectAttempt = 10;
    $gradePerInCorrectAttempt = 5;
    $quizGrade = $correctScore = $inCorrectScore = $correctAttemptCount = $inCorrectAttemptCount = 0;

    $quizSubmissions = GetQuizSubmissionByUser($loggedUser);

    // Use array_filter to filter out 'Active' questions
    $activeQuestions = array_filter(GetQuiz($quizId), function ($question) {
        return $question['question_status'] === 'Active';
    });

    $questionCount = count($activeQuestions);

    if (!empty($activeQuestions)) {
        foreach ($activeQuestions as $selectedArray) {
            $questionId = $selectedArray['question_id'];

            if (!empty($quizSubmissions)) {
                foreach ($quizSubmissions as $selectedAnswer) {
                    $answerQuestionId = $selectedAnswer['question_id'];

                    if ($questionId == $answerQuestionId) {
                        $answerResult = $selectedAnswer['answer_status'];
                        if ($answerResult == "Correct") {
                            $correctScore += $gradePerCorrectAttempt;
                            $correctAttemptCount += 1;
                        } else {
                            $inCorrectScore += $gradePerInCorrectAttempt;
                            $inCorrectAttemptCount += 1;
                        }
                    }
                }
            }
        }
    }

    if ($questionCount > 0) {
        $quizGrade = number_format((($correctScore - $inCorrectScore) / ($questionCount * $gradePerCorrectAttempt)) * 100, 2);
    }

    $resultArray = array('quizGrade' => $quizGrade, 'correctScore' => $correctScore, 'inCorrectScore' => $inCorrectScore, 'correctAttemptCount' => $correctAttemptCount, 'inCorrectAttemptCount' => $inCorrectAttemptCount);

    return $resultArray;
}


function GetQuestionGrade($questionId, $loggedUser)
{
    $resultArray = array();

    $gradePerCorrectAttempt = 10;
    $gradePerInCorrectAttempt = 5;
    $questionGrade = $correctScore = $inCorrectScore = $correctAttemptCount = $inCorrectAttemptCount = 0;

    $quizSubmissions = GetQuizSubmissionByUser($loggedUser);

    if (!empty($quizSubmissions)) {
        foreach ($quizSubmissions as $selectedAnswer) {
            $answerQuestionId = $selectedAnswer['question_id'];

            if ($questionId == $answerQuestionId) {
                $answerResult = $selectedAnswer['answer_status'];
                if ($answerResult == "Correct") {
                    $correctScore += $gradePerCorrectAttempt;
                    $correctAttemptCount += 1;
                } else {
                    $inCorrectScore += $gradePerInCorrectAttempt;
                    $inCorrectAttemptCount += 1;
                }
            }
        }
    }

    $questionGrade = number_format($correctScore - $inCorrectScore, 2);
    $resultArray = array('questionGrade' => $questionGrade, 'correctScore' => $correctScore, 'inCorrectScore' => $inCorrectScore, 'correctAttemptCount' => $correctAttemptCount, 'inCorrectAttemptCount' => $inCorrectAttemptCount);

    return $resultArray;
}


function SaveQuizAnswer($quizId, $questionId, $loggedUser, $selectedAnswer)
{
    global $lms_link;
    $questionDetails = GetQuestion($quizId, $questionId);
    $quizSubmissions = GetQuizSubmissionByUser($loggedUser);
    $correctAnswer = $questionDetails['correct_answer'];

    if ($correctAnswer === $selectedAnswer) {
        $answerStatus = "Correct";
        $answerGrade = 10;
    } else {
        $answerStatus = "In-Correct";
        $answerGrade = -5;
    }

    $sql = "SELECT `question_id` FROM `lesson_questions_submition` WHERE `question_id` LIKE '$questionId' AND `user_id` LIKE '$loggedUser' AND `answer_status` LIKE 'Correct'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        $error = array('status' => 'error', 'message' => 'Answer already saved', 'answerStatus' => $answerStatus, 'answerGrade' => $answerGrade);
    } else {
        $sql = "INSERT INTO `lesson_questions_submition`( `user_id`, `question_id`, `selected_answer`, `score`, `answer_status`) VALUES  (?, ?, ?, ?, ?)";
        if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt_sql, "sssss", $loggedUser, $questionId, $selectedAnswer, $answerGrade, $answerStatus);

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

    return json_encode($error);
}


function CheckCorrectAnswerSubmission($questionId, $loggedUser)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `lesson_questions_submition` WHERE `question_id` LIKE '$questionId' AND `user_id` LIKE '$loggedUser' AND `answer_status` LIKE 'Correct'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}


function GetAllAnswerSubmission($questionId, $loggedUser)
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `lesson_questions_submition` WHERE `question_id` LIKE '$questionId' AND `user_id` LIKE '$loggedUser'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}

function SaveTopic($topicId, $topicName, $activeStatus)
{
    global $lms_link;
    $error = "";
    $currentTime = date("Y-m-d H:i:s");

    if ($topicId == 0) {
        $sql = "INSERT INTO `quiz-topics`( `topicName`, `active_status`, `created_at`) VALUES  (?, ?, ?)";
    } else {
        $sql = "UPDATE `quiz-topics` SET  `topicName` = ?, `active_status` = ?, `created_at` = ? WHERE `quiz_topicID` LIKE '$topicId'";
    }

    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sss", $topicName, $activeStatus, $currentTime);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Topic Saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error,);
    }

    return json_encode($error);
}



function SaveQuestion($topicId, $courseCode, $questionId, $question, $createdBy, $questionStatus, $questionContent, $answer1, $answer2, $answer3, $answer4, $correctAnswer)
{
    global $lms_link;
    $error = "";
    $currentTime = date("Y-m-d H:i:s");
    // echo $questionId;
    if ($questionId == '0') {
        $sql = "SELECT * FROM `lesson_questions`";
        $result = $lms_link->query($sql);
        $questionCount = $result->num_rows;

        $questionId = 'QEST' . $questionCount;
        $sql = "INSERT INTO `lesson_questions` (`lesson_id`, `course_code`, `question_id`, `question`, `created_by`, `created_at`, `question_status`, `question_content`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `lesson_questions` SET  `lesson_id` = ?, `course_code` = ?, `question_id` = ?, `question` = ?, `created_by` = ?, `created_at` = ?,  `question_status` = ?, `question_content` = ?, `answer_1` = ?, `answer_2` = ?, `answer_3` = ?, `answer_4` = ?, `correct_answer` = ? WHERE `lesson_id` LIKE '$topicId' AND `question_id` LIKE '$questionId'";
    }
    mysqli_set_charset($lms_link, "utf8mb4");


    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssssssssssss", $topicId, $courseCode, $questionId, $question, $createdBy, $currentTime, $questionStatus, $questionContent, $answer1, $answer2, $answer3, $answer4, $correctAnswer);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Question Saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error,);
    }

    return json_encode($error);
}

function updateQuestionStatus($topicId, $questionId, $questionStatus, $loggedUser)
{
    global $lms_link;
    $error = "";
    $currentTime = date("Y-m-d H:i:s");

    $sql = "UPDATE `lesson_questions` SET  `created_by` = ?, `created_at` = ?,  `question_status` = ? WHERE `lesson_id` LIKE '$topicId' AND `question_id` LIKE '$questionId'";

    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sss", $loggedUser, $currentTime, $questionStatus);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Question disabled successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error,);
    }

    return json_encode($error);
}


function SaveCourseTopic($topicId, $courseCode, $loggedUser)
{
    global $lms_link;
    $error = "";
    $currentTime = date("Y-m-d H:i:s");
    $activeStatus = 'Active';

    $sql = "SELECT *  FROM `quiz_topic_course` WHERE `CourseCode` LIKE '$courseCode' AND `topicId` LIKE '$topicId'";
    $result = $lms_link->query($sql);
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO `quiz_topic_course`( `topicID`, `CourseCode`, `created_at`, `created_by`, `active_status`) VALUES  (?, ?, ?, ?, ?)";
        $result_prefix = "Added";
    } else {

        while ($row = $result->fetch_assoc()) {
            $currentStatus = $row['active_status'];
            if ($currentStatus == "Active") {
                $activeStatus = "Deleted";
            }
        }

        $sql = "UPDATE `quiz_topic_course` SET  `topicID` = ?, `CourseCode` = ?, `created_at` = ?, `created_by` = ?, `active_status` = ? WHERE `CourseCode` LIKE '$courseCode' AND `topicId` LIKE '$topicId'";
        $result_prefix = "Updated";
    }

    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sssss", $topicId, $courseCode, $currentTime, $loggedUser, $activeStatus);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Topic ' . $result_prefix . ' successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error);
        }

        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $lms_link->error,);
    }

    return json_encode($error);
}



function GetQuizSubmissionByAllUser()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `user_id`, `question_id`, `selected_answer`, `score`, `answer_status`, `created_at` FROM `lesson_questions_submition`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}

function GetAllQuizzes()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `id`, `lesson_id`, `course_code`, `question_id`, `question`, `created_by`, `created_at`, `question_status`, `question_content`, `answer_1`, `answer_2`, `answer_3`, `answer_4`, `correct_answer` FROM `lesson_questions`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['lesson_id'] . "-" . $row['question_id']] = $row;
        }
    }

    return $ArrayResult;
}
