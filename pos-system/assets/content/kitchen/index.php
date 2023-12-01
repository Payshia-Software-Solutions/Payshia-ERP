<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Sections = GetSections($link);
$Departments = GetDepartments($link);
$Categories = GetCategories($link);

$LoggedUser = $_POST['LoggedUser'];
$Invoices = GetHoldInvoices($link);
$LocationID = $_POST['LocationID'];


$netTotal = $total = 0;
$Products = GetProducts($link);
$Units = GetUnit($link);

$CountHold = count($Invoices);

?>


<div id="page-content-wrapper"><!-- Your Page Content Goes Here -->
    <input type="hidden" name="hold_count" id="hold_count" value="<?= $CountHold ?>">

    <div class="row">

        <div class="col-md-12 px-1">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-12 text-end">
                                    <div class="lds-ellipsis">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="product-price">Order List</h4>
                                </div>

                                <?php
                                if (!empty($Invoices)) {
                                    foreach ($Invoices as $SelectedArray) {
                                        if ($SelectedArray['invoice_status'] != 1) {
                                            continue;
                                        }

                                        if ($SelectedArray['location_id'] != $LocationID) {
                                            continue;
                                        }

                                        if ($SelectedArray['order_ready_status'] != 0) {
                                            continue;
                                        }




                                        $invoice_date = date("Y-m-d", strtotime($SelectedArray['invoice_date']));

                                        $charge_status = 1;
                                        $TableID = $SelectedArray['table_id'];
                                        if ($TableID == 0) {
                                            $TableName = "Take Away";
                                        } else if ($TableID == -1) {
                                            $TableName = "Retail";
                                        } else if ($TableID == -2) {
                                            $TableName = "Delivery";
                                        } else {
                                            $TableName = GetTables($link)[$SelectedArray['table_id']]['table_name'];
                                        }
                                        $service_charge = $SelectedArray['service_charge'];
                                        $discount_rate = $SelectedArray['discount_percentage'];
                                        $close_type = $SelectedArray['close_type'];
                                        $tendered_amount = $SelectedArray['tendered_amount'];
                                        $invoice_number = $SelectedArray['invoice_number'];

                                        if ($service_charge > 0) {
                                            $charge_status = 1;
                                        }
                                        $InvProducts = GetInvoiceItems($link, $invoice_number);

                                ?>

                                        <div class="col-6 col-md-4 col-xl-3 d-flex mb-3">
                                            <div class="card table-card flex-fill shadow-sm clickable" onclick=" GetLatestInvoice('<?= $SelectedArray['invoice_number'] ?>', 0)">
                                                <div class="card-body p-2 pb-2">

                                                    <span class="badge text-dark mt-2 bg-light"><?= $SelectedArray['invoice_number'] ?></span>
                                                    <span class="badge mt-2 bg-primary"><?= $TableName ?></span>
                                                    <h1 class="tutor-name mt-2">LKR <?= number_format($SelectedArray['inv_amount'], 2) ?></h1>
                                                    <span class="tutor-name text-dark  bg-light badge mt-2"><?= $invoice_date ?></span>
                                                    <hr>
                                                    <?php
                                                    if (!empty($InvProducts)) {
                                                        foreach ($InvProducts as $SelectRecord) {
                                                            $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                                                            $print_name = $Products[$SelectRecord['product_id']]['print_name'];
                                                            $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                                                            $selling_price = $SelectRecord['item_price'];
                                                            $item_quantity = $SelectRecord['quantity'];
                                                            $item_discount = $SelectRecord['item_discount'];
                                                            $product_id = $SelectRecord['product_id'];

                                                            $line_total = ($selling_price - $item_discount) * $item_quantity;
                                                            $total += $line_total;
                                                    ?>

                                                            <p class="mb-0"><?php echo $print_name; ?></p>
                                                            <p class="text-end">@ <?php echo $item_quantity; ?></p>

                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>


                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <p class="mb-0">No Hold Invoices</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class=" item-container" id="item-container">
            </div>

        </div>
        <div class="col-md-4  px-1" id="bill-container"></div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <p class="my-0 text-secondary">Powered By payshia.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>