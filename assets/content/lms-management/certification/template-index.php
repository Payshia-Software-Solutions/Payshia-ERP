<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';
$selectedCourse = $_POST['studentBatch'];
$certificateTemplates = GetCertificateTemplates();
?>
<div class="loading-popup-content">
    <div class="row mb-3">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
        <div class="col-12">
            <h5 class="table-title">Templates</h5>
        </div>
    </div>

    <div class="row g-2">
        <?php
        if (!empty($certificateTemplates)) {
            foreach ($certificateTemplates as $selectedTemplate) {
        ?>
                <div class="col-md-4">
                    <div class="card clickable shadow-sm" onclick="OpenNewTemplate('<?= $selectedCourse ?>', '<?= $selectedTemplate['template_id'] ?>')">
                        <div class="card-body">
                            <h4 class="mb-0"><?= $selectedTemplate['template_name'] ?></h4>
                            <p class="mb-0 text-secondary"><?= $selectedTemplate['template_id'] ?></p>

                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

</div>