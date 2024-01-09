<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$sectionId = $_POST['sectionId'];
$selectedDepartment = $_POST['selectedDepartment'];
$selectedCategory = $_POST['selectedCategory'];

$departmentList = GetDepartmentsBySection($link, $sectionId);
?>

<option value="">Select Department</option>
<?php
if (!empty($departmentList)) {
    foreach ($departmentList as $selected_array) {
        if ($selected_array['is_active'] != 1) {
            continue;
        }
?>
        ?>
        <option <?= ($selected_array['id'] == $selectedDepartment) ? 'selected' : '' ?> value="<?= $selected_array['id'] ?>"><?= $selected_array['department_name'] ?></option>
<?php
    }
}
?>