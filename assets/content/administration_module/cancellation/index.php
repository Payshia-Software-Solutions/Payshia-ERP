<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';

$ArrayCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-chart-bar icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Tickets</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>

</div>
<style>
    #order-table tr {
        height: auto !important
    }

    .recent-po-container {
        max-height: 70vh;
        overflow: auto;
    }
</style>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="table-title font-weight-bold mb-4 mt-0">Type</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Category</th>
                                        <th scope="col" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Invoice</td>
                                        <td class="text-end">
                                            <button class="mb-0 btn btn-sm btn-success view-button" type="button" onclick="OpenInvoiceCancellation()"><i class="fa-solid fa-eye"></i> Open</button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="table-title font-weight-bold mb-4 mt-0">Cancellation Index</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body" id="card-index">
                        <p class="mb-0">Open a type</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>


<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            ordering: false,
            lengthChange: false,
            info: false, // Disable information display
            // searching: false, // Disable search input
        });
    });
</script>