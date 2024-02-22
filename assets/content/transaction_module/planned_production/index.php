<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveStatus = 0;
$Locations = GetLocations($link);
$productionNotes = GetBatchProduction();
$ArrayCount = count($productionNotes);

$LoggedUser = $_POST['LoggedUser'];
$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Production Notes</p>
                <h1><?= $ArrayCount ?></h1>
            </div>
        </div>
    </div>
    <?php
    $pageID = 15;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-9 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="NewProductionSheet()"><i class="fa-solid fa-plus"></i> New Batch</button>
            </div>
    <?php
        }
    }
    ?>
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
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Production Sheets</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class=" table-responsive">

                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Batch #</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($productionNotes)) {
                                        $RowNumber = 0;
                                        foreach ($productionNotes as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";
                                            if ($selectedArray['is_active'] == 1) {
                                                $active_status = "Active";
                                                $color = "primary";
                                            }
                                            $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                            $production_date = $selectedArray['production_date'];
                                            $production_cost =  $selectedArray['production_cost'];

                                            $batch_number = $selectedArray['batch_number'];
                                            $RowNumber++;


                                            $production_date = date("Y-m-d", strtotime($production_date));
                                    ?>
                                            <tr>
                                                <th><?= $batch_number ?></th>
                                                <td><?= $production_date ?></td>
                                                <td><?= $LocationName ?></td>
                                                <td><?= $production_cost ?></td>
                                                <td class="text-end"><span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span></td>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="PrintBatchSheet('<?= $batch_number ?>')"><i class="fa-solid fa-print"></i> Print</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });
    });
</script>