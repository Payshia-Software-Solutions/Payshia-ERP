<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

// Parameters
$studentBatch = $_POST['studentBatch'];
$orderType = $_POST['orderType'];
$LoggedUser = $_POST['LoggedUser'];

$Locations = GetLocations($link);
$Deliveries = GetDeliverySetting();
$mainCourseList = GetMainCourseList();
$CourseBatches = getLmsBatches();

$ArrayCount = count($mainCourseList);
$ActiveCount = $ArrayCount;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-award icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Courses</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-9 text-end mt-4 mt-md-0">
        <button class="btn btn-dark rounded-5" type="button" onclick="AddNewCourse()"><i class="fa-solid fa-plus"></i> New Course</button>
    </div>
</div>


<div class="row mt-5">
    <div class="col-12">
        <div class="table-title font-weight-bold mb-4 mt-0">Course List</div>
    </div>
    <?php
    if (!empty($mainCourseList)) {
        foreach ($mainCourseList as $SelectArray) {
            $active_status = "Deleted";
            $color = "warning";
            if ($SelectArray['is_active'] == 1) {
                $active_status = "Active";
                $color = "primary";
            }
    ?>
            <div class="col-6 col-md-3 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body p-2 pb-2">
                        <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                        <h1 class="tutor-name mt-2"><?= $SelectArray['category_name'] ?></h1>
                        <div class="text-end mt-3">
                            <button class="mt-0 mb-1 btn btn-sm btn-dark view-button" type="button" onclick="AddNew (1, '<?= $SelectArray['id'] ?>')"><i class="fa-solid fa-pen-to-square"></i> Update</button>

                            <?php
                            if ($SelectArray['is_active'] == 1) {
                                $active_status = "Active";
                                $color = "primary";
                            ?>
                                <button class="mt-0 mb-1 btn btn-sm btn-secondary view-button" type="button" onclick="ChangeStatus(0, '<?= $SelectArray['id'] ?>')"><i class="fa-solid fa-ban"></i> Disable</button>
                            <?php
                            } else {
                            ?>
                                <button class="mt-0 mb-1 btn btn-sm btn-primary view-button" type="button" onclick="ChangeStatus(1, '<?= $SelectArray['id'] ?>')"><i class="fa-solid fa-check"></i> Active</button>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">No Entires</p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>