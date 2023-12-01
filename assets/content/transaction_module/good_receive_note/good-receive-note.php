<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$po_number = $_POST['po_number'];
$LoggedUser = $_POST['LoggedUser'];

$ClearResult = ClearTempGRN($link, $LoggedUser);
$location_name =  $tax_type = $supplier_id = $location_id = "";

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$grn_number = 0;

$Currency = "LKR";
$PurchaseOrder = GetPurchaseOrders($link)[$po_number];

$order_date = date('Y-m-d', strtotime($PurchaseOrder['created_at']));
$location_name = $Locations[$PurchaseOrder['location_id']]['location_name'];
$supplier_name = $Suppliers[$PurchaseOrder['supplier_id']]['supplier_name'];

$PurchaseOrderItems = GetPurchaseOrderItems($link, $po_number);
if (!empty($PurchaseOrderItems)) {
    foreach ($PurchaseOrderItems as $selectedArray) {

        $OrderDate = $selectedArray['order_rate'];
        $OrderQuantity = $selectedArray['quantity'];
        $PerRate = $selectedArray['order_rate'];
        $OrderUnit = $selectedArray['order_unit'];
        $ProductID = $selectedArray['product_id'];
        $productName = $Products[$ProductID]['product_name'];

        $grn_qty = GetGRNItemCountByPO($link, $po_number, $ProductID);
        if ($grn_qty >= $OrderQuantity) {
            continue;
        } else {
            $OrderQuantity = $OrderQuantity - $grn_qty;
        }
        $AddResult = AddToTempGRN($link, $LoggedUser, $ProductID, $OrderQuantity, $po_number, $OrderUnit, $PerRate, $OrderQuantity);
    }
}
?>
<div class="row my-4">
    <div class="col-12">
        <div class="add-class-form" id="">

            <div class="row">
                <div class="col-12 text-end"><button class="btn-warning btn" onclick="OpenGRN('<?= $po_number ?>')">
                        <i class="clickable fa-solid fa-rotate-right"></i>
                    </button>
                    <button class="btn-success btn" onclick="OpenIndex()">
                        <i class="clickable fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <h1 class="site-title">Good Receive Note</h1>
            <h4 class="mb-4 border-bottom pb-2">Order Details</h4>

            <div class="mb-3">
                <form id="action-form" method="post">
                    <input type="hidden" name="location_id" value="<?= $PurchaseOrder['location_id'] ?>">
                    <input type="hidden" name="supplier_id" value="<?= $PurchaseOrder['supplier_id'] ?>">
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

                    <div class="row mb-3">
                        <div class="col-4 col-md-2">
                            <label class="form-label">Location</label>
                        </div>
                        <div class="col-8 col-md-5 mt-3 mt-md-0">
                            <input type="text" name="location_name" id="location_name" class="form-control" value="<?= $location_name ?>" readonly>
                        </div>

                        <div class="col-4 col-md-2 mt-3 mt-md-0">
                            <label class="form-label text-md-end">Tax Type</label>
                        </div>

                        <div class="col-8 col-md-3 mt-3 mt-md-0">
                            <select class="form-select" name="tax_type" id="tax_type" required autocomplete="off">
                                <option value="Non-VAT">Non-VAT</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 col-md-2">
                            <label class="form-label">Supplier</label>
                        </div>
                        <div class="col-8 col-md-5 mt-3 mt-md-0">
                            <input type="text" name="supplier_name" id="supplier_name" class="form-control" value="<?= $supplier_name ?>" readonly>
                        </div>


                        <div class="col-4 col-md-2 mt-3 mt-md-0">
                            <label class="form-label text-md-end">Payment Status</label>
                        </div>

                        <div class="col-8 col-md-3 mt-3 mt-md-0">
                            <select class="form-select" name="payment_status" id="payment_status" required autocomplete="off">
                                <option value="Not Paid">Not Paid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>
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
                        <input type="text" class="form-control" placeholder="Add Comment here" name="remarks" id="remarks">
                    </div>
                </div>
            </div>


            <div class="row mb-3 mt-5">
                <div class="col-12 text-end">
                    <!-- 1 - Temp 2 Processed -->
                    <button class="mt-0 mb-1 btn  btn-success view-button" type="button" onclick="GetConfirmation('<?= $po_number ?>', '<?= $grn_number ?>')"><i class="fa-solid fa-check"></i> Process</button>
                </div>
            </div>


        </div>
    </div>
</div>