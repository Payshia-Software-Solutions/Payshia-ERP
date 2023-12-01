<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
?>

<div class="row">
    <div class="col-12">
        <h4 class="border-bottom pb-2">Invoice Cancellation</h4>
    </div>
    <div class="col-12 col-md-4">
        <p class="text-secondary mb-0">Invoice Number</p>
        <input type="text" class="form-control" name="invoice_number" id="invoice_number" placeholder="Invoice Number">
    </div>

    <div class="col-12 col-md-4">
        <p class="text-secondary mb-0">Action</p>
        <button onclick="RetrieveInvoice()" type="button" class="mb-0 btn action-button btn-dark view-button form-control">Search</button>
    </div>
</div>

<div class="mt-4" id="type-index"></div>