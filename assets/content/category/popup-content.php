<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$Sections = GetSections($link);
$Departments = GetDepartments($link);
$Categories = GetCategories($link);


$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$category_name = $section_id = $department_id = $pos_display = "";
if ($UpdateKey > 0) {
    $Category = GetCategories($link)[$UpdateKey];
    $department_id = $Category['department_id'];
    $section_id = $Category['section_id'];
    $category_name = $Category['category_name'];
    $pos_display = $Category['pos_display'];
}


?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Category Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="location-form" method="post">
                <div class="row">
                    <div class="col-6 mb-2">

                        <h6 class="taxi-label">Section</h6>
                        <select onchange="SelectDepartments(this.value)" class="form-control" name="section_id" id="section_id">
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

                        <h6 class="taxi-label">Department</h6>
                        <select class="form-control" name="department_id" id="department_id">

                        </select>
                    </div>
                    <div class="col-8 mb-2">
                        <h6 class="taxi-label">Category Name</h6>
                        <input type="text" class="form-control" value="<?= $category_name ?>" placeholder="Enter Category Name" id="category_name" name="category_name" required>
                    </div>
                    <div class="col-4 mb-2">
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

<script>
    SelectDepartments(document.getElementById('section_id').value, '<?= $department_id ?>')
</script>