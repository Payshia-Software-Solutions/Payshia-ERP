<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);
$location_name =  $tax_type = $supplier_id = $location_id = "";
$Currency = "LKR";
$order_date = date('Y-m-d');
$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];
$po_number = 0;

$ClearResult = ClearTempPO($link, $LoggedUser);
// echo ($ClearResult);

$TempOrder =  GetTempPO($link, $LoggedUser);
if (!empty($TempOrder)) {
    $order_date = date('Y-m-d');
    $Currency = 'LKR';
    $location_name = $Locations[$TempOrder[0]['location_id']]['location_name'];
    $tax_type = $TempOrder[0]['tax_type'];
    $supplier_id = $TempOrder[0]['supplier_id'];
    $location_id = $TempOrder[0]['location_id'];
}



?>
<div class="row my-4">
    <div class="col-12">
        <div class="add-class-form" id="">

            <div class="row">
                <div class="col-12 text-end"><button class="btn-warning btn" onclick="NewPurchaseOrder()">
                        <i class="clickable fa-solid fa-rotate-right"></i>
                    </button>
                    <button class="btn-success btn" onclick="OpenIndex()">
                        <i class="clickable fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <h1 class="site-title">Purchase Order</h1>
            <h4 class="mb-4 border-bottom pb-2">Order Details</h4>

            <div class="mb-3">
                <form id="action-form" method="post">
                    <div class="row mb-3">
                        <div class="col-4 col-md-2">
                            <label class="form-label">Date</label>
                        </div>
                        <div class="col-8 col-md-5">
                            <input type="date" class="form-control" id="po-date" name="po-date" value="<?= $order_date ?>" readonly>
                        </div>

                        <div class="col-4 col-md-2 mt-3 mt-md-0">
                            <label class="form-label text-md-end">Currency</label>
                        </div>
                        <div class="col-8 col-md-3 mt-3 mt-md-0">
                            <input type="text" name="currency" id="currency" class="form-control" value="<?= $Currency ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-4 col-md-2">
                            <label class="form-label">Location</label>
                        </div>
                        <div class="col-8 col-md-5">
                            <select class="form-select" name="location_id" id="location_id" required autocomplete="off">
                                <option value="">Select Location</option>
                                <?php
                                if (!empty($Locations)) {
                                    foreach ($Locations as $SelectedArray) {
                                        if ($SelectedArray['is_active'] != 1) {
                                            continue;
                                        }
                                ?>

                                        <option <?= ($SelectedArray['location_id'] == $location_id) ? 'selected' : '' ?> value="<?= $SelectedArray['location_id'] ?>"><?= $SelectedArray['location_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-4 col-md-2 mt-3 mt-md-0">
                            <label class="form-label text-md-end">Tax Type</label>
                        </div>
                        <div class="col-8 col-md-3 mt-3 mt-md-0">
                            <select class="form-select" name="tax_type" id="tax_type" required autocomplete="off">
                                <option <?= ($SelectedArray['location_id'] == 'Non-VAT') ? 'selected' : '' ?>value="Non-VAT">Non-VAT</option>
                                <option <?= ($SelectedArray['location_id'] == 'VAT') ? 'selected' : '' ?>value="VAT">VAT</option>
                                <option <?= ($SelectedArray['location_id'] == 'sVAT') ? 'selected' : '' ?> value="sVAT">sVAT</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 col-md-2">
                            <label class="form-label">Supplier</label>
                        </div>
                        <div class="col-8 col-md-5">
                            <select class="form-select" name="supplier_id" id="supplier_id" required autocomplete="off" onchange="FilterProductList(this.value)">
                                <option value="">Select Supplier</option>
                                <?php
                                if (!empty($Suppliers)) {
                                    foreach ($Suppliers as $Supplier) {
                                        $active_status = "Deleted";
                                        $color = "warning";
                                        if ($Supplier['is_active'] == 1) {
                                            $active_status = "Active";
                                            $color = "primary";
                                        } else {
                                            continue;
                                        }
                                ?>

                                        <option <?= ($Supplier['supplier_id'] == $supplier_id) ? 'selected' : '' ?> value="<?= $Supplier['supplier_id'] ?>"><?= $Supplier['supplier_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="p-3 border border-2 bg-light rounded-4 mt-4" id="product-selector"></div>
                </form>


                <div class="row mb-2">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover  border-top border-3 mt-4" id="order-table"></table>
                        </div>
                    </div>
                </div>
                <div id="order-totals"></div>

                <div class="row mb-3 mt-3">
                    <div class="col-4 col-md-2 mt-3 mt-md-0">
                        <label class="form-label">Remark</label>
                    </div>
                    <div class="col-8 col-md-10 mt-3 mt-md-0">
                        <input type="text" class="form-control" placeholder="Add Comment and Instruction here" name="remarks" id="remarks">
                    </div>
                </div>
            </div>


            <div class="row mb-3 mt-5">
                <div class="col-12 text-end">
                    <button class="mt-0 mb-1 btn  btn-success view-button" type="button" onclick="ProcessPurchaseOrder('<?= $po_number ?>', '2')"><i class="fa-solid fa-check"></i> Process</button>
                </div>
            </div>


        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#location_id').select2({
            width: 'resolve'
        });

        $('#supplier_id').select2({
            width: 'resolve'
        });
    });
</script>