<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

// Parameter
$invoice_number = $_POST['invoice_number'];

$checkStatus = isInvoiceNumberExists($link, $invoice_number);
if ($checkStatus == false) { ?>
    <div class="alert alert-warning"><?= $invoice_number ?> : Invalid Invoice or Not Exist</div>
<?php
    exit;
}

$checkCancelStatus = checkCancelStatus($link, $invoice_number);
if ($checkCancelStatus) { ?>
    <div class="alert alert-warning"><?= $invoice_number ?> : Already Cancelled!</div>
<?php
    exit;
}

$netTotal = $total = 0;
$SelectedArray = GetInvoices($link)[$invoice_number];
$InvProducts = GetInvoiceItems($link, $invoice_number);
$Products = GetProducts($link);
$Units = GetUnit($link);

$inv_time = date("Y-m-d H:i:s", strtotime($SelectedArray['current_time']));
$TableID = $SelectedArray['table_id'];
if ($TableID == 0) {
    $TableName = "Take Away";
}
if ($TableID == -1) {
    $TableName = "Retail";
} else if ($TableID == -2) {
    $TableName = "Delivery";
} else {
    $TableName = GetTables($link)[$SelectedArray['table_id']]['table_name'];
}

$service_charge = $SelectedArray['service_charge'];
$discountPercentage = $SelectedArray['discount_percentage'];
$close_type = $SelectedArray['close_type'];
$tendered_amount = $SelectedArray['tendered_amount'];
$InvoiceNumber = $SelectedArray['invoice_number'];
$LocationName = GetLocations($link)[$SelectedArray['location_id']]['location_name'];
$invAmount = $SelectedArray['inv_amount'];
$grand_total = $SelectedArray['grand_total'];
$invAmount = $SelectedArray['inv_amount'];
// echo $invAmount;
$customer_code = $SelectedArray['customer_code'];
$invoice_status = $SelectedArray['invoice_status'];
$discountAmount = $grand_total * ($discountPercentage / 100);
$netTotal = $grand_total - $discountAmount;

$CustomerName = GetCustomerName($link, $customer_code);
$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];


$created_by =  $SelectedArray['created_by'];
if (!empty($created_by)) {
    $LoggedStudent = GetAccounts($link)[$created_by];
    $LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
} else {
    $LoggedName = "Unknown";
}


?>
<div class="border-bottom mb-3"></div>
<style>
    tr,
    td {
        height: 11px !important;
    }
</style>
<div class="row">
    <div class="col-md-8">
        <h5 class="pb-2 border-bottom">Invoice - <?= $invoice_number ?></h5>
        <div class="row">
            <div class="col-6">
                <p class="mb-0">Invoice Number : <?= $invoice_number ?></p>
                <p class="mb-0">Customer : <?= $CustomerName ?></p>
                <p class="mb-0">Date : <?= $inv_time ?></p>
            </div>
            <div class="col-6">
                <p class="mb-0">Cashier : <?= $LoggedName ?></p>
                <p class="mb-0">Table : <?= $TableName ?></p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <th>#</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Amount</th>
            </thead>
            <tbody>
                <?php
                if (!empty($InvProducts)) {
                    foreach ($InvProducts as $SelectRecord) {
                        $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                        $print_name = $Products[$SelectRecord['product_id']]['print_name'];
                        $name_si = $Products[$SelectRecord['product_id']]['name_si'];
                        $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                        $selling_price = $SelectRecord['item_price'];
                        $item_quantity = $SelectRecord['quantity'];
                        $item_discount = $SelectRecord['item_discount'];
                        $product_id = $SelectRecord['product_id'];

                        $line_total = ($selling_price - $item_discount) * $item_quantity;
                        $total += $line_total;

                ?>
                        <tr>
                            <td><?= $SelectRecord['product_id'] ?></td>
                            <td><?= $display_name ?></td>
                            <td class="text-end"><?= $item_unit ?></td>
                            <td class="text-end"><?= number_format($selling_price, 2) ?></td>
                            <td class="text-end"><?= number_format($line_total, 2) ?></td>
                        </tr>
                <?php
                    }
                }
                ?>

                <tr>
                    <td colspan="4" class="text-end">No of Items</td>
                    <th class="text-end"><?= count($InvProducts) ?></th>
                </tr>
                <tr>
                    <td colspan="4" class="text-end">Sub Total</td>
                    <th class="text-end"><?= number_format($total, 2) ?></th>
                </tr>

                <tr>
                    <td colspan="3" class="text-end">Discount</td>
                    <td class="text-end"><?= number_format($discountPercentage, 2) ?>%</td>
                    <th class="text-end"><?= number_format($discountAmount, 2) ?></th>
                </tr>

                <tr>
                    <td colspan="4" class="text-end">Charge</td>
                    <th class="text-end"><?= number_format($service_charge, 2) ?></th>
                </tr>

                <tr>
                    <th colspan="4" class="text-end">Gross Total</th>
                    <th class="text-end"><?= number_format($total - $discountAmount + $service_charge, 2) ?></th>
                </tr>



            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h5 class="pb-2 border-bottom">Receipt</h5>
        <table class="table table-bordered">
            <thead>
                <th>#</th>
                <th>Type</th>
                <th>Amount</th>
            </thead>
            <tbody>
                <?php
                $receiptList =  GetReceiptsByInvoice($link, $invoice_number);

                if (!empty($receiptList)) {
                    foreach ($receiptList as $selectedArray) {
                        $rec_number = $selectedArray['rec_number'];
                        $Receipt =  GetReceiptByNumber($link, $rec_number)[$rec_number];

                        $type = $Receipt['type'];
                        $is_active = $Receipt['is_active'];
                        $date = $Receipt['date'];
                        $current_time = $Receipt['current_time'];
                        $amount = $Receipt['amount'];
                        $created_by = $Receipt['created_by'];
                        $ref_id = $Receipt['ref_id'];
                        $location_id = $Receipt['location_id'];
                ?>
                        <tr>
                            <td><?= $rec_number ?></td>
                            <td><?= $PaymentTypes[$type]['text'] ?></td>
                            <td class="text-end"><?= $amount ?></td>
                        </tr>

                <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <button type="button" onclick="CancelInvoice('<?= $invoice_number ?>')" class="btn btn-dark p-2 w-100">Cancel Receipt & Invoice</button>
    </div>
</div>