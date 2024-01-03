<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Parameters
$loggedUser = $_POST['LoggedUser'];
$userLevel = $_POST['UserLevel'];
$defaultLocation = $_POST['defaultLocation'];
$defaultLocationName = $_POST['defaultLocationName'];
$courseModules = GetCourseModules();

$courseName = $courseCode = $courseDescription = "";
?>

<style>
    @media only screen and (min-width: 768px) {
        .loading-popup-content {
            width: 60% !important;
        }
    }
</style>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-10">
            <h2 class="site-title mb-0">New Course</h2>
        </div>

        <div class="col-2 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h6 class="border-bottom pb-2 mb-0">Course Information</h6>
            <ul class="nav nav-underline" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Basic Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-tab-pane" type="button" role="tab" aria-controls="overview-tab-pane" aria-selected="false">Overview</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="syllabus-tab" data-bs-toggle="tab" data-bs-target="#syllabus-tab-pane" type="button" role="tab" aria-controls="syllabus-tab-pane" aria-selected="false">Syllabus</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Outcome</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Certification</button>
                </li>
            </ul>
        </div>
    </div>


    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="row mt-4">
                <div class="col-md-7">
                    <div class="row g-3 ">
                        <div class="col-md-2">
                            <label class="text-secondary">Course Code</label>
                            <input type="text" class="form-control placeholder-glow" name="courseName" id="courseName" readonly="readonly" placeholder="Do not Add input" value="<?= $courseCode ?>" ?>
                        </div>
                        <div class="col-md-10">
                            <label class="text-secondary">Course Name</label>
                            <input type="text" class="form-control" name="courseName" id="courseName" placeholder="Enter Course Name" value="<?= $courseName ?>" ?>
                        </div>

                        <div class="col-md-4">
                            <label class="text-secondary">Course Value</label>
                            <input type="number" class="form-control text-end" name="courseName" id="courseName" placeholder="12,500.00" value="<?= $courseCode ?>" ?>
                        </div>

                        <div class="col-md-4">
                            <label class="text-secondary">Registration Fee</label>
                            <input type="number" class="form-control text-end" name="courseName" id="courseName" placeholder="1000.00" value="<?= $courseName ?>" ?>
                        </div>

                        <div class="col-md-4">
                            <label class="text-secondary">Students per Batch</label>
                            <input type="number" class="form-control text-end" name="courseName" id="courseName" placeholder="150" value="<?= $courseName ?>" ?>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="text-secondary">Mode</label>
                                            <select class="form-control" name="courseMode" id="courseMode">
                                                <option class="Pro">Pro</option>
                                                <option class="Free">Free</option>
                                            </select>
                                        </div>

                                        <div class="col-md-7">
                                            <label class="text-secondary">Cover Image</label>
                                            <input type="file" class="form-control" name="courseName" id="courseName" placeholder="Enter Course Name" value="<?= $courseName ?>" ?>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="text-secondary">Duration(Months)</label>
                                            <input type="number" class="form-control text-center" name="courseName" id="courseName" placeholder="3" value="<?= $courseName ?>" ?>
                                        </div>

                                        <div class="col-md-7">
                                            <label class="text-secondary">Instructor</label>
                                            <select class="form-control" name="courseMode" id="courseMode">
                                                <option class="Pro">H. M. Fonseka</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="text-secondary">Mini Description</label>
                                            <input type="text" class="form-control" name="courseName" id="courseName" placeholder="Enter Mini Description" value="<?= $courseName ?>" ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary">Current Cover</label>
                                    <img src="https://lms.pharmacollege.lk/uploads/site_content/WhatsApp%20Image%202023-05-19%20at%2018.08.36.jpeg" class="shadow-sm rounded-3 w-100">

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-5">
                    <label class="text-secondary">Course Description</label>
                    <textarea name="courseDescription" id="courseDescription" class="form-control" spellcheck="false" placeholder="Course Description"><?= $courseDescription ?></textarea>
                </div>



            </div>
        </div>

        <div class="tab-pane fade" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
            <div class="row g-3 mt-1">
                <div class="col-3">
                    <label class="text-secondary">Lectures</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Duration(Hours)</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Assessments</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Language</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Quizzes</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Skill Level</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
                <div class="col-md-3">
                    <label class="text-secondary">Students</label>
                    <input type="text" class="form-control" placeholder="Enter Value">
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="syllabus-tab-pane" role="tabpanel" aria-labelledby="syllabus-tab" tabindex="0">
            <div class="row mt-3">
                <?php
                if (!empty($courseModules)) {
                    foreach ($courseModules as $selectedArray) {
                        $active_status = "Deleted";
                        $color = "warning";
                        if ($selectedArray['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        } else {
                            continue;
                        }

                        $isChecked = 0;

                        // $isChecked = in_array($supplier_id, $SupplierArray) ? 'checked' : '';
                ?>
                        <div class="col-12 col-md-6">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" style="width: 15px !important;" name="moduleId[]" id="moduleId" class="form-check-input" value="<?= $selectedArray['module_code']; ?>" <?= $isChecked ?>>
                                    <?= $selectedArray['module_code']; ?> - <?= $selectedArray['module_name']; ?>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0"></div>

    </div>
    <div class="row">
        <div class="col-12 mt-3 text-end">
            <button type="button" onclick="" class="text-white btn btn-dark">
                <i class="fa-solid fa-floppy-disk btn-icon"></i> Save</button>
        </div>
    </div>

</div>

<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount fullscreen',
        toolbar: 'undo redo fullscreen | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>