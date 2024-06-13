<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/finance-functions.php';
include '../../../include/reporting-functions.php';
include '../../../include/lms-functions.php';

include '../../../assets/content/lms-management/assets/lms_methods/quiz_methods.php';

$location_id = $_GET['locationId'];

$gradePerCorrectAttempt = 10;
$gradePerInCorrectAttempt = 5;

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$location_name = $Locations[$location_id]['location_name'];

$studentBatch = $_GET['studentBatch'];
$userId = $_GET['userId'];

$userList = getAllUserEnrollmentsByCourse($studentBatch);
$userDetails =  GetLmsStudentsByUserId();

// Report Detail
$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

$QuizTopicDetails = GetQuizTopics();
$QuizAnswers = GetQuizSubmissionByAllUser();
$QuizTopics = GetQuizTopicsByCourse($studentBatch);
$AllQuizzes = GetAllQuizzes();

$activeTopics = array_filter($QuizTopics, function ($topic) {
    return $topic['active_status'] === 'Active';
});


$overallGrade = 0;
$pageTitle = "Quiz Assessment Report - " . $studentBatch;;
$reportTitle = "Quiz Assessment Report";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../../../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../../../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../../../assets/css/report-viewer.css">

</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                    <tr>
                        <th>Batch Code</th>
                        <td class="text-end"><?= $studentBatch ?></td>
                    </tr>

                </table>
            </div>

        </div>


        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table id="grade-table" class="display compact" style="width:100% !important">
                <thead>
                    <tr>
                        <th>Index No</th>
                        <th>Name</th>
                        <?php
                        if (!empty($activeTopics)) {
                            foreach ($activeTopics as $selectedQuiz) {
                        ?>
                                <th><?= $QuizTopicDetails[$selectedQuiz['topicID']]['topicName'] ?></th>
                        <?php
                            }
                        }
                        ?>
                        <th>Overall Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($userList)) {
                        foreach ($userList as $selectedArray) {
                            $overallGrade = 0;

                            $selectedStudent = $selectedArray['student_id'];
                            $selectedUsername =  $userDetails[$selectedStudent]['username'];

                            // Regular expression pattern for keys starting with "1-" followed by any characters
                            $filteredSubmissions = array_filter($QuizAnswers, function ($submission) use ($selectedUsername) {
                                // Filter elements where the lesson_id matches the pattern
                                return $submission['user_id'] == $selectedUsername;
                            });
                    ?>
                            <tr>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['username'] ?></td>
                                <td class="border-bottom"><?= $userDetails[$selectedStudent]['name_on_certificate'] ?></td>
                                <?php
                                foreach ($activeTopics as $SelectedTopic) {
                                    $pattern = $SelectedTopic['topicID'];
                                    $activeQuestions = array_filter($AllQuizzes, function ($quiz) use ($pattern) {
                                        return $quiz['lesson_id'] == $pattern && $quiz['question_status'] == 'Active';
                                    });

                                    $quizGrade = $correctScore = $inCorrectScore = $correctAttemptCount = $inCorrectAttemptCount = 0;
                                    $questionCount = count($activeQuestions);

                                    if (!empty($activeQuestions)) {
                                        foreach ($activeQuestions as $selectedQuestion) {

                                            $questionId = $selectedQuestion['question_id'];
                                            $questionGrade = $correctScore = $inCorrectScore = $correctAttemptCount = $inCorrectAttemptCount = 0;

                                            if (!empty($filteredSubmissions)) {
                                                foreach ($filteredSubmissions as $selectedAnswer) {
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
                                            $questionGrade = $correctScore - $inCorrectScore;
                                            $quizGrade += $questionGrade;
                                        }
                                    }

                                    if ($questionCount > 0) {
                                        $quizGrade = (($quizGrade) / ($questionCount * $gradePerCorrectAttempt)) * 100;
                                    }

                                    $overallGrade += $quizGrade;
                                ?>
                                    <td class="border-bottom text-center"><?= number_format($quizGrade, 2) ?></td>
                                <?php
                                }

                                if (count($activeTopics) > 0) {
                                    $overallGrade = ($overallGrade / (count($activeTopics) * 100)) * 100;
                                }
                                ?>
                                <td class="border-bottom text-center"><?= number_format($overallGrade, 2) ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>