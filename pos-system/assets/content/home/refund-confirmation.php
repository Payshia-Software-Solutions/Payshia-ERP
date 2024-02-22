<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';

$LocationID = $_POST['LocationID'];
$rtnNumber = $_POST['rtnNumber'];

$selectedArray = GetReturns()[$rtnNumber];
$Products = GetProducts($link);
$Units = GetUnit($link);

$rtnNumber = $selectedArray['rtn_number'];
$returnAmount = $selectedArray['return_amount'];
$customerId = $selectedArray['customer_id'];
$rtnLocationId = $selectedArray['location_id'];
$rtnTimestamp = $selectedArray['created_at'];
$rtnReason = $selectedArray['reason'];
$refundId = $selectedArray['refund_id'];
$refInvoice = $selectedArray['ref_invoice'];

$rtnProducts = GetReturnItemsPrint($rtnNumber);
$CustomerName = GetCustomerName($link, $customerId);
$rtnTime = date("Y-m-d H:i:s", strtotime($rtnTimestamp));

$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');
$reprintStatus = 0;
if ($_POST['closeButtonStatus'] == 0) {
?>
    <style>
        .x-button {
            display: none;
        }
    </style>
<?php
}
?>

<style>
    .inner-popup-container {
        max-height: 100vh;
        overflow-y: auto;
    }


    @media (max-width: 600px) {
        .inner-popup-container {
            max-height: calc(100vh - 250px);
        }
    }

    .itemName {
        min-width: 250px;
    }
</style>

<div class="row mt-3">
    <div class="col-12">
        <h4 class="mb-0 fw-bold  border-bottom pb-2">Refund Confirmation</h4>
    </div>

    <div class="inner-popup-container mt-2">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card flex-fill border-0" onclick="">
                            <div class="card-body p-2 pb-2">

                                <span class="badge text-light bg-dark"><?= $CustomerName ?></span>
                                <h4 class="mb-0"><?= $rtnNumber ?></h4>
                                <h2 class="tutor-name mb-0"><?= number_format($returnAmount, 2) ?></h2>
                                <span class="badge text-light mt-2 bg-success"><?= $rtnTime ?></span>

                                <table class="table table-bordered table-hover mt-3">
                                    <tbody>
                                        <?php
                                        if (!empty($rtnProducts)) {
                                            $total = 0;
                                            foreach ($rtnProducts as $SelectRecord) {
                                                $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                                                $print_name = $Products[$SelectRecord['product_id']]['print_name'];
                                                $name_si = $Products[$SelectRecord['product_id']]['name_si'];
                                                $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                                                $selling_price = $SelectRecord['item_rate'];
                                                $item_quantity = $SelectRecord['item_qty'];
                                                $product_id = $SelectRecord['product_id'];

                                                $line_total = ($selling_price) * $item_quantity;
                                                $total += $line_total;
                                        ?>
                                                <tr>
                                                    <td colspan="4"><?= $print_name; ?> - <?= number_format($selling_price, 2); ?></td>
                                                </tr>
                                                <tr class="selected">
                                                    <td><?= $item_quantity; ?></td>
                                                    <td class="text-right"><?= number_format($selling_price, 2); ?></td>
                                                    <td class="text-right"><?= number_format($line_total, 2); ?></td>
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
            <div class="col-12 col-md-6">
                <div class="card bg-light rounded-3">
                    <form id="refund-form" method="post">
                        <input type="hidden" id="rtnNumber" name="rtnNumber" value="<?= $rtnNumber ?>">
                        <input type="hidden" id="rtnAmount" name="rtnAmount" value="<?= $returnAmount ?>">
                        <input type="hidden" id="rtnCustomer" name="rtnCustomer" value="<?= $customerId ?>">
                        <input type="hidden" id="rtnLocation" name="rtnLocation" value="<?= $rtnLocationId ?>">

                        <div class="card-body">
                            <h4>Enter PIN</h4>
                            <input autocomplete="off" class="form-control text-center p-3 fw-bold" style="font-size: 20px;" type="password" name="pinDigits" id="pinDigits" maxlength="4" required>
                            <button onclick="SaveRefund('<?= $receiptPrinterStatus ?>', '<?= $receiptPrintMethod ?>', '<?= $reprintStatus ?>')" type="button" class="btn btn-dark w-100 mt-2 p-3" style="font-size: 20px;"> <i class="fa-solid fa-money-bill-trend-up"></i> Refund</button>
                        </div>

                    </form>
                </div>

            </div>


        </div>
    </div>
</div>