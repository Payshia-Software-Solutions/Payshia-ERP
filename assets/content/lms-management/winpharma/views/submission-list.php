<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

include_once '../classes/LmsDatabase.php';
include_once '../classes/Submissions.php';
include_once '../classes/Levels.php';
include_once '../classes/Tasks.php';


$LoggedUser = $_POST['LoggedUser'];
$requestStatus = $_POST['requestStatus'];
$UserLevel = $_POST['UserLevel'];
$defaultCourse = $_POST['defaultCourse'];


$specialAccounts = GetLmsAccounts();
// Integration with employee
include_once '../../../employee_management/classes/Database.php';
include_once '../../../employee_management/classes/Employee.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$database2 = new LmsDatabase($config_file);
$db2 = $database2->getConnection();

// Create a new Employee object
$employee = new Employee($database);
$submission = new Submissions($database2);
$level = new Levels($database2);

// Accounts Link Check
$userAccountLink = $employee->CheckAccountLinkByUser($LoggedUser, 1);
$employeeId = $userAccountLink['employee_id'];
$lmsAccountLink = $employee->CheckLMSAccountLinkByEmployee($employeeId, 2);
$linkedAccount = $lmsAccountLink['user_id'];

if (isset($specialAccounts[$linkedAccount])) {
    $accountInfo = $specialAccounts[$linkedAccount];
    $updatedBy = $accountInfo['username'];
}

if (!isset($updatedBy)) {
    return json_encode(['status' => 'error', 'message' => 'Please contact Administrator to Link LMS Account.']);
    exit;
}

$Levels = $level->getLevels($defaultCourse);
$submissions = $submission->fetchAllByCondition($defaultCourse, $requestStatus);

$allSubmissions = $submission->fetchAllByCondition($defaultCourse, 'All');
$specialPendingSubmissions = $submission->fetchAllByCondition($defaultCourse, 'Sp-Pending');
$pendingSubmissions = $submission->fetchAllByCondition($defaultCourse, 'Pending');
$completedSubmissions = $submission->fetchAllByCondition($defaultCourse, 'Completed');
$tryAgainSubmissions = $submission->fetchAllByCondition($defaultCourse, 'Try Again');
$reCorrectionSubmissions = $submission->fetchAllByCondition($defaultCourse, 'Re-Correction');

$CourseBatches = getLmsBatches();
?>

<div class="row g-3">
    <div class="col-12">
        <h5 class="table-title mb-4"><?= $defaultCourse ?> - <?= $CourseBatches[$defaultCourse]['course_name'] ?> | Winpharma Submissions</h5>
        <div class="row g-2 mb-4">
            <div class="col-6 col-md-2">
                <div class="card bg-black text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'All')">

                    <div class="card-body">
                        <p class="mb-0 text-white">All</p>
                        <h1><?= count($allSubmissions) ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="card bg-warning text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'Pending')">

                    <div class="card-body">
                        <p class="mb-0 text-white">Pending</p>
                        <h1><?= count($pendingSubmissions) ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="card bg-danger text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'Sp-Pending')">

                    <div class="card-body">
                        <p class="mb-0 text-white">Sp-Pending</p>
                        <h1><?= count($specialPendingSubmissions) ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="card bg-info text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'Re-correction')">

                    <div class="card-body">
                        <p class="mb-0 text-white">Re-correction</p>
                        <h1><?= count($reCorrectionSubmissions) ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="card bg-success text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'Completed')">

                    <div class="card-body">
                        <p class="mb-0 text-white">Completed</p>
                        <h1><?= count($completedSubmissions) ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="card bg-secondary text-white clickable" onclick="GetWinpharmaSubmissions('<?= $defaultCourse ?>', 'Try Again')">

                    <div class="card-body">
                        <p class="mb-0 text-white">Try Again</p>
                        <h1><?= count($tryAgainSubmissions) ?></h1>
                    </div>
                </div>
            </div>


        </div>
        <div class="card shadow-lg">
            <div class="card-body">
                <?php if (empty($submissions)) : ?>
                    <p class="mb-0 mt-2">No <?= $requestStatus ?> submissions found.</p>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hovered table-striped" id="submission-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Index Number</th>
                                    <th>Level</th>
                                    <th>Action</th>
                                    <th>Time</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Checked By</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $selectedArray) :
                                    $LevelCode = $selectedArray['level_id'];


                                    if (isset($Levels[$LevelCode]['course_code'])) :

                                        $Level = $Levels[$LevelCode];
                                        $CourseCode = $Levels[$LevelCode]['course_code'];
                                        if ($defaultCourse != $CourseCode) {
                                            continue;
                                        }

                                        if ($selectedArray['grade_status'] == "Pending") {
                                            $bg_color = "warning";
                                        } else if ($selectedArray['grade_status'] == "Sp-Pending") {
                                            $bg_color = "danger";
                                        } else if ($selectedArray['grade_status'] == "Completed") {
                                            $bg_color = "primary";
                                        } else if ($selectedArray['grade_status'] == "Try Again") {
                                            $bg_color = "secondary";
                                        } else if ($selectedArray['grade_status'] == "Re-Correction") {
                                            $bg_color = "info";
                                        }

                                        $date = new DateTime($selectedArray["date_time"]);
                                        $formattedDate = $date->format('Y-m-d H:i:s');

                                        $UpdatedTime = new DateTime($selectedArray["update_at"]);
                                        $formattedUpdatedDate = $UpdatedTime->format('Y-m-d H:i:s');

                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($selectedArray['submission_id']) ?></td>
                                            <td><?= htmlspecialchars($selectedArray['index_number']) ?></td>
                                            <td><?= $Level['level_name'] ?></td>
                                            <td class="text-center">
                                                <?php if ($selectedArray['update_by'] == $updatedBy || $selectedArray['update_by'] == '' || $UserLevel == 'Admin') : ?>
                                                    <button onclick="OpenSubmission('<?= $selectedArray['submission_id'] ?>', '<?= $requestStatus ?>')" class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-eye"></i> View</button>
                                                <?php endif ?>
                                            </td>
                                            <td><?= $formattedDate ?></td>
                                            <td><?= $selectedArray['grade'] ?>%</td>
                                            <td><span class="badge bg-<?= $bg_color ?>"><?= $selectedArray['grade_status'] ?></span></td>
                                            <td><?= $selectedArray['update_by'] ?></td>
                                            <td><?= ($selectedArray['update_by'] == "") ? '' : $formattedUpdatedDate ?></td>
                                        </tr>
                                <?php
                                    endif;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#submission-table').DataTable({
            order: false,
            pageLength: 20
        });

    });
</script>