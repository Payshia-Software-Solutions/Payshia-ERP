<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

include_once 'classes/LmsDatabase.php';
include_once 'classes/Medicines.php';
include_once 'classes/Categories.php';
include_once 'classes/DosageForm.php';
include_once 'classes/DrugGroup.php';
include_once 'classes/Rack.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$medicines = new Medicines($db);
$categories = new Categories($db);
$dosage_form = new DosageForm($db);
$drug_group = new DrugGroup($db);
$rack = new Rack($db);

$medicine_list = $medicines->fetchAll();
$category_list = $categories->fetchAll();
?>
<div id="total-counters"></div>

<div class="row">
    <div class="col-12 text-end">
        <button onclick="CreateMedicine()" type="button" class="btn btn-dark btn-sm">Add New Medicine</button>
    </div>
</div>

<div id="medicine-list">

    <div class="row g-3">

        <div class="col-md-8">
            <h5 class="table-title mb-4">Medicine List</h5>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="medicine-items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Category</th>
                                    <th>Medicine Code</th>
                                    <th>Medicine Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($medicine_list)) : ?>
                                    <?php foreach ($medicine_list as $medicineItem) :
                                        $statusBadgeColor = ($medicineItem['active_status'] == "Active") ? 'bg-primary' : 'bg-danger';
                                    ?>
                                        <tr>
                                            <td><?= $medicineItem['id'] ?></td>
                                            <td>
                                                <button class="btn btn-dark btn-sm" type="button" onclick="OpenMedicine('<?= $medicineItem['id'] ?>')"><i class="fa-solid fa-eye"></i> Open</button>
                                            </td>
                                            <td><?= $category_list[$medicineItem['category_id']]['category_name'] ?></td>
                                            <td><?= $medicineItem['product_code'] ?></td>
                                            <td><?= $medicineItem['medicine_name'] ?></td>
                                            <td><img src="<?= $medicineItem['file_path'] ?>" alt="Image for <?= $medicineItem['medicine_name'] ?>"></td>

                                            <td>
                                                <div class="badge <?= $statusBadgeColor ?>">
                                                    <?= $medicineItem['active_status'] ?>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-4">
            <h5 class="table-title">Other Tasks</h5>
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-dark w-100">Racks</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dark w-100">Dosage Form</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dark w-100">Drug Group</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dark w-100">Categories</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#medicine-items-table').DataTable({
            pageLength: 25
        });

    });
</script>