<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$closeButtonStatus = $_POST['closeButtonStatus'];

$returnList = GetReturns();
$Products = GetProducts($link);
$Units = GetUnit($link);

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
        <h4 class="mb-0 fw-bold">Select Return to Make Refund</h4>
        <p class="mb-0 text-secondary border-bottom pb-2">Note : A La Carte Items cannot be Returned of Refunded!</p>
    </div>

    <div class="inner-popup-container mt-2">
        <div class="row g-3">
            <?php
            $refundItemStatus = 0;
            if (!empty($returnList)) {
                foreach ($returnList as $selectedArray) {

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
            ?>
                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="row">
                            <div class="col-12 d-flex">
                                <div class="card table-card flex-fill shadow-sm clickable" onclick="OpenRefundConfirmation('<?= $rtnNumber ?>', <?= $closeButtonStatus ?>)">
                                    <div class="card-body p-2 pb-2">

                                        <span class="badge text-light bg-dark"><?= $CustomerName ?></span>
                                        <h4 class="mb-0"><?= $rtnNumber ?></h4>
                                        <h2 class="tutor-name mb-0"><?= number_format($returnAmount, 2) ?></h2>
                                        <span class="badge text-light mt-2 bg-success"><?= $rtnTime ?></span>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php
                }
            }

            if ($refundItemStatus == 0) { ?>
                <div class="col-12">
                    <div class="alert alert-warning fw-bold">No Refundable Returns</div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>