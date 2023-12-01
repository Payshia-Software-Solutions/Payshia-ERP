<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$department_name = $section_id = $pos_display = "";
if ($UpdateKey > 0) {
    $Department = GetDepartments($link)[$UpdateKey];
    $section_id = $Department['section_id'];
    $department_name = $Department['department_name'];
    $pos_display = $Department['pos_display'];
}


$Sections = GetSections($link);
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Department Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="location-form" method="post">
                <div class="row">
                    <div class="col-3 mb-2">

                        <h6 class="taxi-label">Section</h6>
                        <select class="form-control" name="section_id" id="section_id">
                            <?php
                            if (!empty($Sections)) {
                                foreach ($Sections as $select_array) {
                            ?>
                                    <option <?= ($select_array['id'] == $section_id) ? 'selected' : '' ?> value="<?= $select_array['id'] ?>"><?= $select_array['section_name'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Department Name</h6>
                        <input type="text" class="form-control" value="<?= $department_name ?>" placeholder="Enter Department Name" id="department_name" name="department_name" required>
                    </div>
                    <div class="col-3 mb-2">
                        <h6 class="taxi-label">POS Display</h6>
                        <select class="form-control" name="pos_display" id="pos_display">
                            <option <?= ($pos_display == 1) ? 'selected' : '' ?> value="1">Display</option>
                            <option <?= ($pos_display == 0) ? 'selected' : '' ?> value="0">Not Display</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="Save (1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>