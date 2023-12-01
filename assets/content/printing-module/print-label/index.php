<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveCount = 0;
// Calculate the date three months from today
$threeMonthsLater = date('Y-m-d', strtotime('+3 months'));
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-location-dot icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Templates</p>
                <h1><?= $ActiveCount ?></h1>
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
    <div class="col-md-7">
        <div class="table-title font-weight-bold mb-4 mt-0">Setup Label</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <form id="label-form" method="post" target="_blank" enctype="multipart/form-data">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Label</label>
                                    <select class="form-control" name="label" id="label">
                                        <option value="0">20 LKR Thilina Bite</option>
                                        <option value="1">50 LKR Thilina Bite</option>
                                        <option value="2">20 LKR Parippu Bite</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Batch Code</label>
                                    <input value="1118" type="number" name="batch_code" id="batch_code" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Manufacture Date</label>
                                    <input value="<?= date('Y-m-d') ?>" type="date" name="manufacture_date" id="manufacture_date" class="form-control" required>
                                </div>


                                <div class="col-md-6">
                                    <label>Expire Date</label>
                                    <input value="<?= $threeMonthsLater ?>" type="date" name="expire_date" id="expire_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>File</label>
                                    <input type="file" name="label_back" id="label_back" class="form-control" required>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <button class="btn btn-dark" type="button" name="submit_button" onclick="submitForm() ">View Label</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="table-title font-weight-bold mb-4 mt-0">Saved Templates</div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });

        $('#grn-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
                // 'colvis'
            ],
            order: [
                [0, 'desc']
            ]
        });
    });
</script>