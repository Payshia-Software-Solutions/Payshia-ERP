<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$templateId = $_POST['templateId'];
$LoggedUser = $_POST['LoggedUser'];
$studentBatch = $_POST['studentBatch'];

// Initialize template variables
$Template = array(
    'left_to_qr' => '',
    'top_to_qr' => '',
    'qr_width' => '',
    'left_margin' => '',
    'top_to_name' => '',
    'left_to_date' => '',
    'top_to_date' => '',
    'template_name' => ''
);

if ($templateId != 0) {
    $Template = GetTemplate($templateId)[$templateId];
}
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-light rounded-5" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <h5 class="mb-0 border-bottom pb-2 ">Template Details</h5>
        </div>

        <div class="col-md-9">
            <form id="templateForm" method="post">
                <div class="col-12">
                    <h5 class="mb-2">QR Position</h5>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label>QR Position from Left(mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['left_to_qr'] ?>" placeholder="Given QR position in mm" type="number" name="qr_position_from_left" id="qr_position_from_left">
                        </div>
                        <div class="col-md-4">
                            <label>QR Position from Top(mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['top_to_qr'] ?>" placeholder="Given QR position in mm" type="number" name="qr_position_from_top" id="qr_position_from_top">
                        </div>
                        <div class="col-md-4">
                            <label>QR width(mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['qr_width'] ?>" placeholder="Given QR position in mm" type="number" name="qr_code_width" id="qr_code_width">
                        </div>
                    </div>

                    <h5 class="mt-3">Name Position</h5>

                    <div class="row mt-2 g-2">
                        <div class="col-md-4">
                            <label>Left Margin</label>
                            <input class="form-control form-control-sm" value="<?= $Template['left_margin'] ?>" placeholder="Given position in mm" type="number" name="name_position_from_left" id="name_position_from_left">
                        </div>

                        <div class="col-md-4">
                            <label>Name Position from Top (mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['top_to_name'] ?>" placeholder="Given position in mm" type="number" name="name_position_from_top" id="name_position_from_top">
                        </div>
                    </div>

                    <h5 class="mt-3">Date Position</h5>
                    <div class="row mt-2  g-2">
                        <div class="col-md-4">
                            <label>Date Position from Left(mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['left_to_date'] ?>" placeholder="Given position in mm" type="number" name="date_position_from_left" id="date_position_from_left">
                        </div>
                        <div class="col-md-4">
                            <label>Date Position from Top(mm)</label>
                            <input class="form-control form-control-sm" value="<?= $Template['top_to_date'] ?>" placeholder="Given position in mm" type="number" name="date_position_from_top" id="date_position_from_top">
                        </div>
                    </div>

                    <div class="row mt-2 g-2">
                        <div class="col-md-8">
                            <p class="mt-3 mb-0">Template Name</p>
                            <input class="form-control form-control-sm" placeholder="Give a name to Save" type="text" name="template_name" id="template_name" value="<?= $Template['template_name'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <p class="mt-3 mb-0">Template Back</p>
                            <input class="form-control form-control-sm" placeholder="Give a name to Save" type="file" name="template_back" id="template_back" value="">
                            <input type="hidden" name="tempImage" id="tempImage" value="">
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button class="btn btn-sm btn-dark rounded-2" type="button" onclick="SaveTemplate('<?= $studentBatch ?>', '<?= $templateId ?>')">Save Template</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3">
            <img src="./assets/content/lms-management/certification/assets/images/certificate-back/<?= $Template['back_image'] ?>" class="img-fluid">
        </div>

    </div>
</div>