<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$sectionId = $_POST['sectionId'];
$departmentId = $_POST['departmentId'];
$selectedCategory = $_POST['selectedCategory'];

$categoryList = GetCategoryBySectionDepartment($link, $sectionId, $departmentId)
?>

<option value="">Select Category</option>
<?php
if (!empty($categoryList)) {
    foreach ($categoryList as $selected_array) {
        if ($selected_array['is_active'] != 1) {
            continue;
        }
?>
        ?>
        <option <?= ($selected_array['id'] == $selectedCategory) ? 'selected' : '' ?> value="<?= $selected_array['id'] ?>"><?= $selected_array['category_name'] ?></option>
<?php
    }
}
?>