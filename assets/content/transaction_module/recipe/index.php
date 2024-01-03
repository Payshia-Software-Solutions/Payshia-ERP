<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveStatus = 0;
$Locations = GetLocations($link);
$Products = GetProducts($link);
$ArrayCount = count($Products);

$LoggedUser = $_POST['LoggedUser'];
$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-right-left icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Products</p>
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
    <div class="col-md-7">

        <div class="row">
            <div class="col-12 mb-3">
                <div class="table-title font-weight-bold mb-4 mt-0">Product List</div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Cost Price</th>
                                        <th scope="col">Selling Price</th>
                                        <th scope="col">Profit %</th>
                                        <th scope="col">Recipe Type</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($Products)) {
                                        $RowNumber = 0;
                                        foreach ($Products as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";
                                            if ($selectedArray['active_status'] != 1 || $selectedArray['item_type'] == "Raw") {
                                                continue;
                                            }
                                            $recipeType  = $selectedArray['recipe_type'];
                                            if ($recipeType == 0) {
                                                $recipeDisplay = "None";
                                                $color = "success";
                                                continue;
                                            } else if ($recipeType == 1) {
                                                $recipeDisplay = "A La Carte";
                                                $color = "primary";
                                            } else if ($recipeType == 2) {
                                                $recipeDisplay = "Item Recipe";
                                                $color = "danger";
                                            }

                                            $cost_price = $selectedArray['cost_price'];
                                            $selling_price = $selectedArray['selling_price'];
                                            $profitRatio = number_format((($selling_price - $cost_price) / $cost_price) * 100, 2) . "%";

                                    ?>
                                            <tr>
                                                <th><?= $selectedArray['product_id'] ?></th>
                                                <td><?= $selectedArray['product_name'] ?></td>
                                                <td><?= $cost_price ?></td>
                                                <td><?= $selling_price ?></td>
                                                <td><?= $profitRatio ?></td>
                                                <td class="text-center"><span class="badge mt-2 bg-<?= $color ?>"><?= $recipeDisplay ?></span></td>
                                                <td class="text-end">
                                                    <?php
                                                    $pageID = 12;
                                                    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

                                                    if (!empty($userPrivilege)) {
                                                        $readAccess = $userPrivilege[$LoggedUser]['read'];
                                                        $writeAccess = $userPrivilege[$LoggedUser]['write'];
                                                        $AllAccess = $userPrivilege[$LoggedUser]['all'];

                                                        if ($writeAccess == 1) {
                                                    ?>
                                                            <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="OpenRecipe('<?= $selectedArray['product_id'] ?>', '<?= $recipeType ?>')"><i class="fa-solid fa-right-left"></i> BOM</button>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
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

    <div class="col-md-5 mb-3">

        <div class="table-title font-weight-bold mb-4 mt-0">Bill of Materials</div>
        <div class="card>
            <div class=" card-body" id="selected-product">
            <div class="row">
                <div class="col-12">
                    <h6 class="text-center">Please Choice one of product recipe</h6>
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