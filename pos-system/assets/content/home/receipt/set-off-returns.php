<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

$locationId = $_POST['LocationID'];
$customerId = $_POST['customerId'];
$CustomerInvoices =  GetInvoicesByCustomer($link, $customerId);

$detailedBalances = getCustomerBalanceDetailed($link, $customerId);
$customerBalance = $detailedBalances['customerBalance'];
$returnValue = $detailedBalances['returnValue'];
$returnValue = $detailedBalances['returnValue'];
$GetSettlementTotal = $detailedBalances['GetSettlementTotal'];
$unsettledReturnBalance = $returnValue - $GetSettlementTotal;
$unsettledReturns = GetUnsettledReturns($customerId);


?>


<div class="row g-2">

    <div class="col-md-12">
        <div class="text-center bg-light rounded-3 p-2 mb-3">
            <label for="totalDue">Unsettled Return Balance</label>
            <h4 class="mb-0"><?= number_format($unsettledReturnBalance, 2) ?></h4>
        </div>
    </div>

</div>


<div class="row g-3">
    <?php
    if (!empty($unsettledReturns)) {
        foreach ($unsettledReturns as $selectedArray) {

            $rtnNumber = $selectedArray['rtn_number'];
            $returnAmount = $selectedArray['return_amount'];
            $customerId = $selectedArray['customer_id'];
            $rtnLocationId = $selectedArray['location_id'];
            $rtnTimestamp = $selectedArray['created_at'];
            $rtnReason = $selectedArray['reason'];
            $refundId = $selectedArray['refund_id'];
            $refInvoice = $selectedArray['ref_invoice'];

            if ($refundId != "") {
                continue;
            }

            $refundItemStatus = 1;

            $InvProducts = GetReturnItemsPrint($rtnNumber);
            $CustomerName = GetCustomerName($link, $customerId);
            $rtnTime = date("Y-m-d H:i:s", strtotime($rtnTimestamp));
            $settledAmount = GetSettledAmount($rtnNumber);

            $unsettledAmount = $returnAmount - $settledAmount;
            if ($unsettledAmount <= 0) {
                continue;
            }

    ?>
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <div class="card table-card flex-fill shadow-sm clickable" onclick="OpenSettlementInvoices('<?= $customerId ?>', '<?= $rtnNumber ?>')">
                    <div class="card-body p-2 pb-2">

                        <span class="badge text-light bg-dark"><?= $CustomerName ?></span>
                        <h4 class="mb-0"><?= $rtnNumber ?></h4>
                        <h2 class="tutor-name mb-0"><?= number_format($unsettledAmount, 2) ?></h2>
                        <h5 class="tutor-name mb-0 fw-normal">Return Amount : <?= number_format($returnAmount, 2) ?></h5>
                        <span class="badge text-light mt-2 bg-success"><?= $rtnTime ?></span>

                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>