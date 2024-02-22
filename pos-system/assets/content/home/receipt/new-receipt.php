<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';
?>


<style>
    .inner-popup-container {
        max-height: calc(100vh - 350px);
        overflow-y: auto;
    }


    @media (max-width: 600px) {
        .inner-popup-container {
            max-height: calc(100vh - 150px);
        }
    }

    .itemName {
        min-width: 250px;
    }
</style>

<div class="row mt-3">
    <div class="col-12">
        <h4 class="mb-0 fw-bold  border-bottom pb-2">Receipt Information</h4>
    </div>

    <div class="inner-popup-container mt-2">
        <div class="row">
            <div class="col-md-12">
                <label class="form-label">Select Customer</label>
                <select onchange="getInvoiceLIstByCustomerReceipts(this.value)" class="form-control" name="customer_select" id="customer_select" required autocomplete="off">
                    <option value="">Select Customer</option>
                </select>
            </div>

            <div class="col-md-12" id="due-invoices"></div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {

        $('#customer_select').select2({
            width: 'resolve'
        });

        $('#select_invoice').select2({
            width: 'resolve'
        });

        GetCustomerList()
    });
</script>