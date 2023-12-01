<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Today = date("Y-m-d");
// $Today = "2023-10-19";

$LocationID = $_POST['LocationID'];
$Invoices = GetByInvoicesDate($link, $Today, $LocationID);
?>
<div class="card">
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-12">
                <h4 class="product-price">Today Invoice List</h4>
            </div>
            <div class="col-12">
                <div class="p-2 bg-dark text-white mb-2">
                    <div class="row">
                        <div class="col-3">
                            <h4 class="product-price mb-0">INV #</h4>
                        </div>
                        <div class="col-3">
                            <h4 class="product-price mb-0">Table</h4>
                        </div>

                        <div class="col-3">
                            <h4 class="product-price mb-0">Value</h4>
                        </div>
                        <div class="col-3">
                            <h4 class="product-price mb-0">Date</h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($Invoices)) {
                foreach ($Invoices as $SelectedArray) {
                    if ($SelectedArray['is_active'] != 1) {
                        continue;
                    }

                    if ($SelectedArray['invoice_status'] != 2) {
                        continue;
                    }

                    if ($SelectedArray['location_id'] != $LocationID) {
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
                    $CustomerID = $SelectedArray['customer_code'];

                    if ($service_charge > 0) {
                        $charge_status = 1;
                    }

                    $CustomerName = GetCustomerName($link, $CustomerID);

            ?>
                    <div class="col-12">
                        <div class="p-2 bg-light mb-2 clickable invoice-card" onclick="InvoiceFinishWindow('<?= $invoice_number ?>')">
                            <div class="row">
                                <div class="col-3">
                                    <h3 class="product-price  mb-0"><?= $CustomerName ?></h3>
                                </div>
                                <div class="col-3">
                                    <h4 class="product-price  mb-0"><?= $SelectedArray['invoice_number'] ?></h4>
                                </div>
                                <div class="col-2">
                                    <h4 class="product-price  mb-0"><?= $TableName ?></h4>
                                </div>
                                <div class="col-2 text-end">
                                    <h4 class="product-price  mb-0">LKR <?= number_format($SelectedArray['inv_amount'], 2) ?></h4>
                                </div>
                                <div class="col-2 text-end">
                                    <h4 class="product-price  mb-0"><?= $invoice_date ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <p class="mb-0">No Invoices for Today</p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>