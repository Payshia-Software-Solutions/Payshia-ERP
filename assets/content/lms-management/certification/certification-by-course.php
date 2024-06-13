<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$LoggedUser = $_POST['LoggedUser'];
$selectedCourse = $_POST['selectedCourse'];

$accountDetails = GetAccounts($link);
$Locations = GetLocations($link);
$selectedBatch = getLmsBatches()[$selectedCourse];
$studentEnrollCounts = GetStudentEnrollCounts();
$userEnrollments = getAllUserEnrollmentsByCourse($selectedCourse);

$studentCount = $studentEnrollCounts[$selectedCourse];

$batchStudents =  GetLmsStudentsByUserId();
$certificateTemplates = GetCertificateTemplates();
$certificateDefaults = GetTemplateConfig($selectedCourse);
$templateCount = count($certificateTemplates);

$defaultTemplateId = $CompleteDate = $convocationDate = $convocationPlace = $transcriptBackImage = '';
if (isset($certificateDefaults[$selectedCourse])) {
    $defaultTemplateId = $certificateDefaults[$selectedCourse]['defaultTemplate'];
    $CompleteDate = $certificateDefaults[$selectedCourse]['CompleteDate'];
    $convocationDate = $certificateDefaults[$selectedCourse]['convocation_date'];
    $convocationPlace = $certificateDefaults[$selectedCourse]['convocation_place'];
    $transcriptBackImage = $certificateDefaults[$selectedCourse]['transcript_back'];
}
?>

<div class="row mt-5">

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Students</p>
                <h1><?= $studentCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-certificate icon-card"></i>
            </div>
            <div class="card-body">
                <p>Templates</p>
                <h1><?= $templateCount ?></h1>
            </div>
        </div>
    </div>


    <div class="col-md-6 text-end">
        <button type="button" onclick="TemplateIndex('<?= $selectedCourse ?>')" class="btn btn-dark">
            <i class="fa-solid fa-plus"></i> Templates
        </button>
    </div>



</div>

<div class="row g-3">

    <div class="col-12">
        <h5 class="table-title"><?= $selectedBatch['course_name'] ?> - <?= $selectedBatch['course_code'] ?></h5>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-fixed" id="student-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Index</th>
                                <th rowspan="2">Name</th>
                                <th colspan="3" class="text-center">Status</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th scope="col">Certificate</th>
                                <th scope="col">Transcript</th>
                                <th scope="col">Workshop</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($userEnrollments)) {
                                foreach ($userEnrollments as $selectedArray) {

                                    $studentId = $selectedArray['student_id'];
                                    if (!isset($batchStudents[$studentId])) {
                                        continue;
                                    }
                                    $studentDetailsArray = $batchStudents[$studentId];
                                    $studentNumber = $studentDetailsArray['username'];

                                    $certificateStatus = $transcriptStatus =  $workshopStatus = "";
                                    $certificatePrintStatus = $transcriptPrintStatus = $workshopPrintStatus = "Not Printed";
                                    $certificateBackground = $transcriptBackground = $workshopBackground = "danger";
                                    $certification = CertificatePrintStatusByCourseStudent($selectedCourse, $studentNumber);
                                    if (!empty($certification)) {
                                        if (isset($certification['Transcript'])) {

                                            $transcriptPrintStatus = "Printed";
                                            $transcriptBackground = "success";
                                        }

                                        if (isset($certification['Certificate'])) {
                                            $certificatePrintStatus = "Printed";
                                            $certificateBackground = "success";
                                        }

                                        if (isset($certification['Workshop-Certificate'])) {
                                            $workshopPrintStatus = "Printed";
                                            $workshopBackground = "success";
                                        }
                                    }
                            ?>
                                    <tr>
                                        <td><?= $studentDetailsArray['username'] ?></td>
                                        <td><?= $studentDetailsArray['first_name'] ?> <?= $studentDetailsArray['last_name'] ?></td>
                                        <td><span class="badge bg-<?= $certificateBackground ?>"><?= $certificatePrintStatus ?></span></td>
                                        <td><span class="badge bg-<?= $transcriptBackground ?>"><?= $transcriptPrintStatus ?></span></td>
                                        <td><span class="badge bg-<?= $workshopBackground ?>"><?= $workshopPrintStatus ?></span></td>
                                        <td>
                                            <button onclick="OpenTranscriptDataEntry('<?= $studentNumber ?>', '<?= $selectedCourse ?>')" class="btn btn-success btn-sm rounded-2" type="button"><i class="fa-solid fa-file-lines"></i></button>
                                            <button onclick="OpenEditProfileDialogue('<?= $studentNumber ?>', '<?= $selectedCourse ?>')" class="btn btn-primary btn-sm rounded-2" type="button"><i class="fa-solid fa-user-pen"></i></button>
                                            <button onclick="PrintDialogOpen('<?= $selectedCourse ?>', '<?= $studentNumber ?>')" class="btn btn-dark btn-sm rounded-2" type="button"><i class="fa-solid fa-print"></i></button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="border-bottom pb-2">Course Certificate Configuration</h4>
                <form action="#" id="config-form">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="defaultTemplate">Select Default Template</label>
                            <select name="defaultTemplate" id="defaultTemplate" class="form-control" required>
                                <?php
                                if (!empty($certificateTemplates)) {
                                    foreach ($certificateTemplates as $selectedTemplate) {
                                ?>
                                        <option <?= ($defaultTemplateId == $selectedTemplate['template_id']) ? 'selected' : '' ?> value="<?= $selectedTemplate['template_id'] ?>"><?= $selectedTemplate['template_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="EndDate">Issued Date</label>
                            <input class="form-control" type="date" name="EndDate" id="EndDate" value="<?= $CompleteDate ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="EndDate">Convocation Date</label>
                            <input class="form-control" type="date" name="convocationDate" id="convocationDate" value="<?= $convocationDate ?>" required>
                        </div>


                        <div class="col-md-6 text-end">
                            <label for="EndDate">Transcript Back</label>
                            <input class="form-control" type="file" name="TranscriptBack" id="TranscriptBack">
                            <input type="hidden" name="TranscriptBackTemp" id="TranscriptBackTemp" value="<?= $transcriptBackImage ?>">
                            <?php
                            if ($transcriptBackImage != "") {
                            ?>
                                <a target="_blank" class="text-primary" href="./assets/content/lms-management/certification/assets/images/transcript-back/<?= $transcriptBackImage ?>">Download</a>
                            <?php
                            } else {
                                echo 'Not Uploaded Yet!';
                            }
                            ?>

                        </div>


                        <div class="col-md-12">
                            <label for="EndDate">Convocation Place</label>
                            <input class="form-control" type="text" name="convocationPlace" id="convocationPlace" value="<?= $convocationPlace ?>" required>
                        </div>

                        <div class="col-12 text-end">
                            <button onclick="SaveCertificateConfiguration('<?= $selectedCourse ?>')" type="button" class="btn btn-sm btn-dark"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                        </div>

                </form>
            </div>

        </div>
    </div>

</div>



</div>
<script>
    $(document).ready(function() {
        $('#student-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [1, 'desc'],
                [0, 'asc']
            ]
        });
    });
</script>