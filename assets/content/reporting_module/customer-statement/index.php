<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
$location_id = $_POST['default_location'];
$Locations = GetLocations($link);
?>
<style>
    .action-button {
        height: 45px;
    }
</style>
<h4 class="border-bottom pb-2">Customer Statement</h4>
<form id="report-form" action="post">
    <div class="row">

        <div class="col-6 col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" name="from-date-input" id="from-date-input" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="col-6 col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" name="to-date-input" id="to-date-input" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label>Select Customer</label>
            <select class="form-control" name="customerId" id="customerId" required autocomplete="off">
                <option value="">Select Customer</option>
                <?php
                $CustomerList = GetActiveCustomers($link);
                if (!empty($CustomerList)) {
                    foreach ($CustomerList as $SelectArray) {

                        $CustomerName = $SelectArray['customer_first_name'] . " " . $SelectArray['customer_last_name'];
                ?>
                        <option value="<?= $SelectArray['customer_id'] ?>"><?= $SelectArray['customer_first_name'] ?> <?= $SelectArray['customer_last_name'] ?> - <?= $SelectArray['phone_number'] ?></option>

                <?php
                    }
                }
                ?>
            </select>
        </div>



        <div class="col-12 col-md-2 text-end">
            <p class="mb-0">Action</p>
            <button class="mb-0 btn action-button btn-dark view-button" type="button" onclick="PrintCustomerStatement()"><i class="fa-solid fa-print"></i> Print</button>
        </div>

    </div>
</form>

<script>
    $('#customerId').select2()
</script>