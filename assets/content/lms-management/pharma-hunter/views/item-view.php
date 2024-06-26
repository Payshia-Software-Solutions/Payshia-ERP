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
?>


<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Medicine Details</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenMedicine('<?= $medicineId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-6 offset-3">
            <img src="<?= $medicineInfo['file_path'] ?>" alt="Image for <?= $medicineInfo['medicine_name'] ?>" srcset="">
        </div>
        <div class="col-12">
            <div class="row">

                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">Category Name</p>
                            <h4 class="mb-0"><?= $category_list[$medicineInfo['category_id']]['category_name'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">Rack Name</p>
                            <h4 class="mb-0"><?= $category_list[$medicineInfo['category_id']]['category_name'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">Dosage Form</p>
                            <h4 class="mb-0"><?= $category_list[$medicineInfo['category_id']]['category_name'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">Drug Type</p>
                            <h4 class="mb-0"><?= $category_list[$medicineInfo['category_id']]['category_name'] ?></h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>