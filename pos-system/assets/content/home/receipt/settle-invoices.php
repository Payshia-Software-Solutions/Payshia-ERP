<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

$locationId = $_POST['LocationID'];

$customerId = $_POST['customerId'];
$rtnNumber = $_POST['rtnNumber'];

$selectedReturn = GetReturns()[$rtnNumber];
$settledAmount = GetSettledAmount($rtnNumber);

$returnAmount = $selectedReturn['return_amount'];
$unsettledReturnBalance = $returnAmount - $settledAmount;

$CustomerInvoices =  GetInvoicesByCustomer($link, $customerId);
?>

<div class="row g-2">

    <div class="col-md-12">
        <div class="text-center bg-light rounded-3 p-2 mb-3">
            <label for="totalDue">Unsettled Return Balance of <?= $rtnNumber ?></label>
            <h4 class="mb-0"><?= number_format($unsettledReturnBalance, 2) ?></h4>
        </div>
    </div>

</div>

<div class="row g-3">
    <?php
    $totalDue = 0;
    if (!empty($CustomerInvoices)) {
        foreach ($CustomerInvoices as $selectedArray) {

            if ($selectedArray['is_active'] != 1) {
                continue;
            }

            if ($selectedArray['location_id'] != $locationId) {
                continue;
            }

            if ($selectedArray['invoice_status'] != '2') {
                continue;
            }

            $invNumber = $selectedArray['invoice_number'];
            $CustomerName = GetCustomerName($link, $customerId);
            $invoiceTime = date("Y-m-d H:i:s", strtotime($selectedArray['current_time']));
            $invAmount = $selectedArray['inv_amount'];

            $paymentValue = GetReceiptsValueByInvoice($link, $invNumber);
            $InvoiceSettlement =  GetInvoiceSettlement($invNumber);
            $balanceAmount = $invAmount - $paymentValue - $InvoiceSettlement;

            if ($paymentValue >= $invAmount) {
                continue;
            }

            $totalDue += $balanceAmount;

            if ($balanceAmount <= 0) {
                continue;
            }
    ?>
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <div onclick="SettleReturn('<?= $invNumber ?>', '<?= $customerId ?>', '<?= $rtnNumber ?>')" class="card table-card flex-fill shadow-sm clickable">
                    <div class="card-body p-2 pb-2">

                        <span class="badge text-light bg-dark"><?= $CustomerName ?></span>
                        <h4 class="mb-0"><?= $invNumber ?></h4>
                        <h2 class="tutor-name mb-0"><?= number_format($balanceAmount, 2) ?></h2>
                        <h5 class="tutor-name mb-0 fw-normal">Payments : <?= number_format($paymentValue, 2) ?></h5>
                        <span class="badge text-light mt-2 bg-success"><?= $invoiceTime ?></span>

                    </div>
                </div>
            </div>

    <?php
        }
    }
    ?>
</div>