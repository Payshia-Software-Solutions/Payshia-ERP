<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include './methods/course_methods.php';

// Parameters
$loggedUser = $_POST['LoggedUser'];
$userLevel = $_POST['UserLevel'];
$defaultLocation = $_POST['defaultLocation'];
$defaultLocationName = $_POST['defaultLocationName'];
$courseCode = $_POST['courseCode'];
$parentCourses = GetParentCourses();
$courseModules = GetCourseModules();

$module_list = array();

$courseName = $instructor_id = $course_description = $course_duration = $course_fee = $registration_fee = $display = $course_img = $courseMode = $certification = $mini_description = $is_active = $lecture_count = $hours_per_lecture = $assessments = $language = $quizzes = $skill_level = $head_count =  '';


if ($courseCode != 0) {
    $courseDetails = $parentCourses[$courseCode];

    $courseName = $courseDetails['course_name'];
    $instructor_id = $courseDetails['instructor_id'];
    $course_description = $courseDetails['course_description'];
    $course_duration = $courseDetails['course_duration'];
    $course_fee = $courseDetails['course_fee'];
    $registration_fee = $courseDetails['registration_fee'];
    $display = $courseDetails['display'];
    $course_img = $courseDetails['course_img'];
    $courseMode = $courseDetails['course_mode'];
    $certification = $courseDetails['certification'];
    $mini_description = $courseDetails['mini_description'];
    $is_active = $courseDetails['is_active'];
    $lecture_count = $courseDetails['lecture_count'];
    $hours_per_lecture = $courseDetails['hours_per_lecture'];
    $assessments = $courseDetails['assessments'];
    $language = $courseDetails['language'];
    $quizzes = $courseDetails['quizzes'];
    $skill_level = $courseDetails['skill_level'];
    $head_count = $courseDetails['head_count'];

    $module_list = explode(",", $courseDetails['module_list']);
}
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


    <form class="" id="course-form">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="row mt-4">
                    <div class="col-md-7">
                        <div class="row g-3 ">
                            <div class="col-md-2">
                                <label class="text-secondary">Course Code</label>
                                <input type="text" class="form-control placeholder-glow" name="course_code" id="course_code" readonly="readonly" placeholder="Do not Add input" value="<?= $courseCode ?>">
                            </div>
                            <div class="col-md-10">
                                <label class="text-secondary">Course Name</label>
                                <input type="text" class="form-control" name="course_name" id="course_name" placeholder="Enter Course Name" value="<?= $courseName ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="text-secondary">Course Value</label>
                                <input type="number" class="form-control text-end" name="course_fee" id="course_fee" placeholder="12,500.00" value="<?= $course_fee ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="text-secondary">Registration Fee</label>
                                <input type="number" class="form-control text-end" name="registration_fee" id="registration_fee" placeholder="1000.00" value="<?= $registration_fee ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="text-secondary">Students per Batch</label>
                                <input type="number" class="form-control text-end" name="head_count" id="head_count" placeholder="150" value="<?= $head_count ?>" required>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="text-secondary">Mode</label>
                                                <select class="form-control" name="courseMode" id="courseMode" required>
                                                    <option <?= ($courseMode == 'Pro') ? 'selected' : '' ?> class="Pro">Pro</option>
                                                    <option <?= ($courseMode == 'Free') ? 'selected' : '' ?> class="Free">Free</option>
                                                </select>
                                            </div>

                                            <div class="col-md-7">
                                                <label class="text-secondary">Cover Image</label>
                                                <input type="file" class="form-control" name="course_img" id="course_img" placeholder="Enter Course Name" value="">
                                            </div>

                                            <div class="col-md-5">
                                                <label class="text-secondary">Duration(Months)</label>
                                                <input type="number" class="form-control text-center" name="course_duration" id="course_duration" placeholder="3" value="<?= $course_duration ?>" required>
                                            </div>

                                            <div class="col-md-7">
                                                <label class="text-secondary">Instructor</label>
                                                <select class="form-control" name="instructor_id" id="instructor_id" required>
                                                    <option <?= ($instructor_id == 'Dilip Fonseka') ? 'selected' : '' ?> class="Dilip Fonseka">Dilip Fonseka</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="text-secondary">Mini Description</label>
                                                <input type="text" class="form-control" name="mini_description" id="mini_description" placeholder="Enter Mini Description" value="<?= $mini_description ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-secondary">Current Cover</label>
                                        <img src="./assets/content/lms-management/assets/images/course-img/<?= $courseCode ?>/<?= $course_img ?>" alt="" class="shadow-sm rounded-3 w-100">
                                        <input type="hidden" name="item_image_tmp" value="<?= $course_img ?>">

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="text-secondary">Course Description</label>
                        <textarea name="courseDescription" id="courseDescription" class="form-control" spellcheck="false" placeholder="Course Description"><?= $course_description ?></textarea>
                    </div>



                </div>
            </div>

            <div class="tab-pane fade" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
                <div class="row g-3 mt-1">
                    <div class="col-3">
                        <label class="text-secondary">Lectures</label>
                        <input name="lecture_count" value="<?= $lecture_count ?>" id="lecture_count" type="text" class="form-control" placeholder="Enter Value" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-secondary">Duration(Hours)</label>
                        <input name="hours_per_lecture" value="<?= $hours_per_lecture ?>" id="hours_per_lecture" type="text" class="form-control" placeholder="Enter Value" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-secondary">Assessments</label>
                        <input name="assessments" value="<?= $assessments ?>" id="assessments" type="text" class="form-control" placeholder="Enter Value" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-secondary">Language</label>
                        <input name="language" value="<?= $language ?>" id="language" type="text" class="form-control" placeholder="Enter Value" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-secondary">Quizzes</label>
                        <input name="quizzes" value="<?= $quizzes ?>" id="quizzes" type="text" class="form-control" placeholder="Enter Value" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-secondary">Skill Level</label>
                        <input name="skill_level" value="<?= $skill_level ?>" id="skill_level" type="text" class="form-control" placeholder="Enter Value" required>
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

                            $isChecked = in_array($selectedArray['module_code'], $module_list) ? 'checked' : '';
                    ?>
                            <div class="col-12 col-md-6">
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" style="width: 15px !important;" name="module_list[]" id="module_list" class="form-check-input" value="<?= $selectedArray['module_code']; ?>" <?= $isChecked ?>>
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
                <button type="button" onclick="SaveCourse('<?= $courseCode ?>', 1)" class="text-white btn btn-dark">
                    <i class="fa-solid fa-floppy-disk btn-icon"></i> Save</button>
            </div>
        </div>
    </form>
</div>

<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount fullscreen',
        toolbar: 'undo redo fullscreen | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>