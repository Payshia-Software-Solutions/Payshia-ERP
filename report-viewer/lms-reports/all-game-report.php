<?php
require_once('../../include/config.php');
include '../../include/function-update.php';
include '../../include/finance-functions.php';
include '../../include/reporting-functions.php';
include '../../include/lms-functions.php';

// Game Methods
include '../../assets/content/lms-management/assets/lms_methods/d-pad-methods.php';
include '../../assets/content/lms-management/assets/lms_methods/quiz_methods.php';
include '../../assets/content/lms-management/assets/lms_methods/win-pharma-functions.php';
include '../../assets/content/lms-management/assets/lms_methods/pharma-hunter-methods.php';
include '../../assets/content/lms-management/assets/lms_methods/pharma-reader-methods.php';

$studentBatch = $_GET['studentBatch'];
$userId = $_GET['userId'];

$userList = getAllUserEnrollmentsByCourse($studentBatch);
$userDetails =  GetLmsStudentsByUserId();

// Winpharma
$winpharmaLevels = GetLevels($lms_link, $studentBatch);
$courseTopLevel = GetCourseTopLevel($lms_link, $studentBatch);
$winpharmaTopLevels =  GetTopLevelAllUsers($lms_link, $studentBatch);



?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.3/dist/bootstrap-table.min.css">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body class="p-2">

    <table class="table table-striped table-bordered" border="1" style="width:100%;">
        <thead>
            <tr>
                <th>Index No</th>
                <th>Name</th>
                <th>WinPharma</th>
                <th>Quiz (%)</th>
                <th>Q-Meter (%)</th>
                <th>D-Pad (%)</th>
                <th>Care Center <br>(R-D-Q)</th>
                <th>Pharma Hunter <br>(C-P-W-G-C)</th>
                <th>Pharma Reader (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($userList)) {
                foreach ($userList as $selectedArray) {

                    $selectedStudent = $selectedArray['student_id'];
                    $selectedUsername =  $userDetails[$selectedStudent]['username'];

                    if (isset($winpharmaTopLevels[$selectedUsername])) {
                        $winpharmaCurrentTopLevel = $winpharmaTopLevels[$selectedUsername];
                    } else {
                        $winpharmaCurrentTopLevel = $courseTopLevel;
                    }
            ?>
                    <tr>
                        <td><?= $userDetails[$selectedStudent]['username'] ?></td>
                        <td><?= $userDetails[$selectedStudent]['first_name'] ?> <?= $userDetails[$selectedStudent]['last_name'] ?></td>
                        <td class="text-center"><?= $winpharmaLevels[$winpharmaCurrentTopLevel]['level_name'] ?></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
            <?php
                }
            }
            ?>

        </tbody>
    </table>
</body>

</html>