<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';

$Products = GetProducts($link);
$LocationID = $_POST['LocationID'];
$removalItems = GetRemovalNotices($link);
?>


<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- <div class="col-12 mb-3">
                <button type="button" onclick="OpenIndex()" class="w-100 btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate btn-icon"></i> Return</button>
            </div> -->
            <div class="col-12">
                <h4 class="product-price">Last 50 Removal Item List</h4>
            </div>

            <?php
            if (!empty($removalItems)) {
                $rowCount = 1;
                foreach ($removalItems as $SelectedArray) {

                    if ($SelectedArray['location_id'] != $LocationID) {
                        continue;
                    }

                    $rowCount++;
                    if ($rowCount > 50) {
                        break;
                    }

                    $productName = 'null';
                    if ($SelectedArray['product_id'] != "") {
                        $productName = $Products[$SelectedArray['product_id']]['product_name'];
                    }

                    if ($SelectedArray['product_id'] == '0') {
                        $productName = 'null';
                    }

                    $refName = "Unknown";
                    $refuserId = $SelectedArray['user_id'];
                    if ($refuserId !== '0') {
                        $refUser = GetAccounts($link)[$refuserId];
                        $refName = $refUser['first_name'] . " " . $refUser['last_name'];
                    }


                    $refCashierName = "Unknown";
                    $refCashierId = $SelectedArray['created_by'];
                    if ($refuserId !== '0') {
                        $refCashier = GetAccounts($link)[$refCashierId];
                        $refCashierName = $refCashier['first_name'] . " " . $refCashier['last_name'];
                    }

                    $invoiceNumber = $SelectedArray['ref_id'];
                    if ($invoiceNumber === 0) {
                        $invoiceNumber = "Initial";
                    }


            ?>
                    <div class="col-12 col-md-6 mb-3 d-flex">
                        <div class="card table-card flex-fill shadow-sm clickable">
                            <div class="card-body p-2 pb-2">
                                <h5 class="mb-0"><?= $productName  ?></h5>
                                <p class="mb-0"><?= $SelectedArray['remark'] ?></p>
                                <span class="badge bg-danger mb-0">Steward - <?= $refName ?></span>
                                <span class="badge bg-secondary mb-0">Cashier - <?= $refCashierName ?></span>
                                <span class="badge bg-primary mb-0">Invoice - <?= $invoiceNumber ?></span>
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
                            <p class="mb-0">No Notices</p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>

    </div>
</div>