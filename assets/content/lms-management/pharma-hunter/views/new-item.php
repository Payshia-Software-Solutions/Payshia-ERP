<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);
$medicineId = $_POST['medicineId'];

include_once '../classes/LmsDatabase.php';
include_once '../classes/Medicines.php';
include_once '../classes/Categories.php';
include_once '../classes/DosageForm.php';
include_once '../classes/DrugGroup.php';
include_once '../classes/Rack.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$medicines = new Medicines($db);
$categories = new Categories($db);
$dosage_form = new DosageForm($db);
$drug_group = new DrugGroup($db);
$rack = new Rack($db);

$medicineInfo = $medicines->fetchById($medicineId);
$category_list = $categories->fetchAll();
$dosage_forms = $dosage_form->fetchAll();
$drug_groups = $drug_group->fetchAll();
$racks = $rack->fetchAll();
?>

<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Medicine Info</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="CreateMedicine('<?= $medicineId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUP(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-2 my-2"></div>
        </div>
    </div>

    <form action="#" method="post" id="medicine-form">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="medicine_name">Product Code</label>
                <input type="text" class="form-control" name="product_code" id="product_code" placeholder="Medicine Name" readonly>
            </div>
            <div class="col-md-6">
                <label for="medicine_name">Medicine Name</label>
                <input type="text" class="form-control" name="medicine_name" id="medicine_name" placeholder="Medicine Name">
            </div>
            <div class="col-md-4">
                <label for="medicine_category">Medicine Category</label>
                <select class="form-control" name="medicine_category" id="medicine_category">
                    <?php foreach ($category_list as $category) : ?>
                        <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="medicine_category">Medicine Dosage</label>
                <select class="form-control" name="dosage_form" id="dosage_form">
                    <?php foreach ($dosage_forms as $selected_array) : ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['dosageForm'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="medicine_category">Drug Group</label>
                <select class="form-control" name="drug_group" id="drug_group">
                    <?php foreach ($drug_groups as $selected_array) : ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['drug_group'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="medicine_category">Drug Rack</label>
                <select class="form-control" name="rack_name" id="rack_name">
                    <?php foreach ($racks as $selected_array) : ?>
                        <option value="<?= $selected_array['id'] ?>"><?= $selected_array['rack_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="medicine_category">Item Image</label>
                <input type="file" class="form-control" name="item_image" id="item_image">
            </div>
        </div>

        <div class="col-12 text-end">
            <button type="button" class="btn btn-dark btn-sm" onclick="SaveMedicine()"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        </div>
</div>
</form>
</div>