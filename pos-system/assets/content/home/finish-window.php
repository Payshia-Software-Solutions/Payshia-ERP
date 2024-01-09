<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';
include '../../../../include/finance-functions.php';

$invoice_number = $_POST['InvoiceNumber'];
$SelectedArray = GetInvoices($link)[$invoice_number];

$service_charge = $SelectedArray['service_charge'];
$discountPercentage = $SelectedArray['discount_percentage'];
$close_type = $SelectedArray['close_type'];
$tendered_amount = $SelectedArray['tendered_amount'];
$InvoiceNumber = $SelectedArray['invoice_number'];
$LocationName = GetLocations($link)[$SelectedArray['location_id']]['location_name'];
$invAmount = $SelectedArray['inv_amount'];
$customer_code = $SelectedArray['customer_code'];
$invoice_status = $SelectedArray['invoice_status'];
$discountAmount = $invAmount * ($discountPercentage / 100);
$netTotal = $invAmount - $discountAmount;
$LocationID = $SelectedArray['location_id'];
$receipts = GetReceiptsByInvoice($link, $invoice_number);
$CustomerName = GetCustomerName($link, $customer_code);

$kotPrintStatus = GetSetting($link, $LocationID, 'kot_printer');
$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');

$kotPrintMethod = GetSetting($link, $LocationID, 'kotPrintMethod');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');


$service_charge = $SelectedArray['service_charge'];
$discountPercentage = $SelectedArray['discount_percentage'];
$tendered_amount = $SelectedArray['tendered_amount'];
$grand_total = $SelectedArray['grand_total'];
$invAmount = $SelectedArray['inv_amount'];
$discountAmount = $grand_total * ($discountPercentage / 100);
$netTotal = $grand_total - $discountAmount;
?>

<style>
    .x-button {
        display: none;
    }
</style>
<div class="row">
    <div class="col-12 text-center mb-2">
        <i class="fa-solid fa-3x fa-circle-check text-success"></i>
    </div>
    <div class="col-md-6">
        <p class="my-0">INV # / INT #</p>
        <h4 class="my-0"><?= $invoice_number ?></h4>
        <?php if (strpos($invoice_number, 'INV') === 0) { ?>
            <div class="row mt-3">
                <div class="col-6">
                    <p class="my-0">Tender Amount</p>
                    <h4 class="my-0">LKR <?= formatAccountBalance($tendered_amount, 2) ?></h4>
                </div>

                <div class="col-6">
                    <p class="my-0">Invoice Amount</p>
                    <h4 class="my-0">LKR <?= formatAccountBalance($grand_total, 2) ?></h4>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <p class="my-0">Change Amount</p>
                    <h1 class="my-0">LKR <?= formatAccountBalance($tendered_amount - $grand_total, 2) ?></h1>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <p class="my-0">Customer</p>
                    <h2 class="my-0"><?= $CustomerName ?></h2>
                </div>
            </div>

        <?php  } ?>
    </div>

    <div class="col-md-6">
        <div class="row mt-3">
            <div class="col-12">
                <?php if (strpos($invoice_number, 'INV') === 0) { ?>
                    <button onclick="PrintInvoice ('<?= $invoice_number ?>', '<?= $receiptPrinterStatus ?>', '<?= $LocationID ?>', 1, '<?= $receiptPrintMethod ?>')" class="text-white w-100 btn btn-dark hold-button btn-lg p-4"><i class="fa-solid fa-print btn-icon"></i> Reprint Invoice</button>
                <?php  } else { ?>
                    <button onclick="PrintKOT ('<?= $invoice_number ?>', '<?= $kotPrintStatus ?>', '<?= $LocationID ?>', 1, 1, '<?= $kotPrintMethod ?>')" class="text-white w-100 btn btn-dark hold-button btn-lg p-4 mt-2"><i class="fa-solid fa-print btn-icon"></i> Reprint Full KOT</button>
                <?php  } ?>
            </div>


            <?php
            if (!empty($receipts)) {
                foreach ($receipts as $SelectedArray) {
            ?>
                    <div class="col-12">
                        <button onclick="PrintPaymentReceipt('<?= $SelectedArray['rec_number'] ?>','<?= $invoice_number ?>', '<?= $receiptPrinterStatus ?>', '<?= $LocationID ?>', '<?= $receiptPrintMethod ?>')" class="text-white w-100 btn btn-secondary hold-button btn-lg p-4 mt-3"><i class="fa-solid fa-receipt btn-icon"></i> Payment Receipt</button>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="col-12">
        <div class="col-12">
            <a href="?last_invoice=false&display_invoice_number=0&location_id=<?= $LocationID ?>">
                <button type="button" class="text-white w-100 btn btn-success hold-button btn-lg p-4 mt-3"><i class="fa-solid fa-right-long btn-icon"></i> Next Customer</button>
            </a>
        </div>
    </div>
</div>

<hr>

<?php if (strpos($invoice_number, 'INV') === 0) { ?>
    <div class="row mt-3">
        <div class="col-md-12 text-center">
            <h1>Thank You!</h1>
        </div>
    </div>
<?php  } ?>