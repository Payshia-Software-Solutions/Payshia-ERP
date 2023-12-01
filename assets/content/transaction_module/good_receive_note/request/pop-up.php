<?php
$po_number = $_POST['po_number'];
$grn_number = $_POST['grn_number'];
?>


<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3 pb-2 border-bottom">Confirmation</h3>
            <p>Do you need to perform this task?</p>
            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn btn-light" onclick="ClosePopUP()">Close</button>
                    <button class="btn btn-dark" onclick="ProcessGRN('<?= $po_number ?>', '<?= $grn_number ?>', '2')"">Process</button>
                </div>
            </div>

        </div>
    </div>
</div>