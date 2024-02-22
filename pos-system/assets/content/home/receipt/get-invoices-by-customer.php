<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$locationID = $_POST['location_id'];
$customerId = $_POST['customerId'];
$CustomerInvoices =  GetInvoicesByCustomer($link, $customerId);

$detailedBalances = getCustomerBalanceDetailed($link, $customerId);
$customerBalance = $detailedBalances['customerBalance'];
$returnValue = $detailedBalances['returnValue'];
$GetSettlementTotal = $detailedBalances['GetSettlementTotal'];
$unsettledReturnBalance = $returnValue - $GetSettlementTotal;


$CustomerName = GetCustomerName($link, $customerId);
?>


<div class="row g-md-3">
    <div class="col-12">
        <h5 class="mb-0">Customer : <?= $CustomerName ?></h5>
    </div>

    <div class="col-md-4">
        <div class="text-center bg-light rounded-3 p-2 mb-3">
            <label for="totalDue">Total Due Balance</label>
            <h4 class="mb-0"><?= number_format($customerBalance - $GetSettlementTotal, 2) ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center bg-light rounded-3 p-2 mb-3 clickable" onclick="OpenSetoffInvoice('<?= $customerId ?>')">
            <label for="totalDue">Unsettled Return Balance</label>
            <h4 class="mb-0"><?= number_format($unsettledReturnBalance, 2) ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center bg-light rounded-3 p-2 mb-3">
            <label for="totalDue">Customer Balance</label>
            <h4 class="mb-0"><?= number_format($customerBalance - $returnValue, 2) ?></h4>
        </div>
    </div>
</div>


<div class="row g-md-3">
    <?php
    $totalDue = 0;
    if (!empty($CustomerInvoices)) {
        foreach ($CustomerInvoices as $selectedArray) {

            if ($selectedArray['is_active'] != 1) {
                continue;
            }

            if ($selectedArray['location_id'] != $locationID) {
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
            $returnSettlement =  GetInvoiceSettlement($invNumber);

            $settlement = $paymentValue + $returnSettlement;
            $balanceAmount = $invAmount - $settlement;

            if ($paymentValue >= $invAmount) {
                continue;
            }

            if ($balanceAmount <= 0) {
                continue;
            }

            $totalDue += $balanceAmount;

    ?>
            <div class="col-12 col-md-6 col-xl-4">

                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card table-card flex-fill shadow-sm clickable" onclick="CreateReceipt('<?= $invNumber ?>', 1, '<?= $balanceAmount ?>', '<?= $customerId ?>',  '<?= $locationID ?>')">
                            <div class="card-body p-2 pb-2">

                                <span class="badge text-light bg-dark"><?= $CustomerName ?></span>
                                <h4 class="mb-0"><?= $invNumber ?></h4>
                                <h2 class="tutor-name mb-0"><?= number_format($balanceAmount, 2) ?></h2>
                                <h5 class="tutor-name mb-0 fw-normal">Settled : <?= number_format($settlement, 2) ?></h5>
                                <span class="badge text-light mt-2 bg-success"><?= $invoiceTime ?></span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

    <?php
        }
    }
    ?>
</div>