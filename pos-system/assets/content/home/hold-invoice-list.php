<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';
include '../../../../include/finance-functions.php';

$LocationID = $_POST['LocationID'];
$LoggedUser = $_POST['LoggedUser'];

if ($LoggedUser == "Admin") {
    $Invoices = GetHoldInvoicesByLocation($link, $LocationID);
} else {
    $Invoices = GetHoldInvoicesByLocationUser($link, $LocationID, $LoggedUser);
}
$stewards = GetStewards($link);
$closeButtonStatus = $_POST['closeButtonStatus'];

$kotPrintStatus = GetSetting($link, $LocationID, 'kot_printer');
$kotPrintMethod = GetSetting($link, $LocationID, 'kotPrintMethod');

$Accounts = GetAccounts($link);
if ($closeButtonStatus == 0) {
?>
    <style>
        .x-button {
            display: none;
        }
    </style>
<?php
}
?>


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 text-end">
                <button type="button" onclick="OpenIndex()" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
        </div>

        <div class="row mt-2">
            <!-- <div class="col-12 mb-3">
                <button type="button" onclick="OpenIndex()" class="w-100 btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate btn-icon"></i> Return</button>
            </div> -->
            <div class="col-12">
                <h4 class="product-price">Hold Invoice List</h4>
            </div>

            <?php
            if (!empty($Invoices)) {
                foreach ($Invoices as $SelectedArray) {
                    if ($SelectedArray['is_active'] != 1) {
                        continue;
                    }

                    $stewardName = "Default";
                    if ($SelectedArray['invoice_status'] != 1) {
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

                    $stewardId = $SelectedArray['steward_id'];
                    if ($stewardId === "0") {
                        $stewardName = "Default";
                    } else {
                        $stewardName = $stewards[$stewardId]['first_name'] . " " . $stewards[$stewardId]['last_name'];
                    }

                    $service_charge = $SelectedArray['service_charge'];
                    $discount_rate = $SelectedArray['discount_percentage'];
                    $close_type = $SelectedArray['close_type'];
                    $tendered_amount = $SelectedArray['tendered_amount'];
                    $invoice_number = $SelectedArray['invoice_number'];
                    $CustomerID = $SelectedArray['customer_code'];
                    $created_by =  $SelectedArray['created_by'];

                    if ($service_charge > 0) {
                        $charge_status = 1;
                    }

                    $createdAccount = $Accounts[$created_by];
                    $createdAccountName = $createdAccount['first_name'] . " " . $createdAccount['last_name'];
                    $CustomerName = GetCustomerName($link, $CustomerID);

            ?>

                    <div class="col-12 col-md-6 col-xl-4 mb-3">

                        <div class="row">
                            <div class="col-12 d-flex">
                                <div class="card table-card flex-fill shadow-sm clickable" onclick="TakeHoldToCart('<?= $TableName ?>', '<?= $TableID ?>', '<?= $charge_status ?>', '<?= $discount_rate ?>', '<?= $close_type ?>', '<?= $tendered_amount ?>', '<?= $invoice_number ?>', '<?= $CustomerID ?>', '<?= $stewardName ?>', '<?= $stewardId ?>')">
                                    <div class="card-body p-2 pb-2">

                                        <span class="badge text-dark mt-2 bg-light"><?= $CustomerName ?></span>
                                        <span class="badge text-light mt-2 bg-primary"><?= $TableName ?></span>
                                        <h4 class="mb-0"><?= $SelectedArray['invoice_number'] ?></h4>
                                        <h2 class="tutor-name mb-0"><?= number_format($SelectedArray['inv_amount'], 2) ?></h2>
                                        <span class="badge text-light mt-2 bg-success"><?= $invoice_date ?></span>
                                        <span class="badge text-light mt-2 bg-danger"><?= $stewardName ?></span>
                                        <?php
                                        if ($LoggedUser == "Admin") {
                                        ?>
                                            <span class="badge text-light mt-2 bg-info"><?= $createdAccountName ?></span>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <button onclick="PrintKOT ('<?= $SelectedArray['invoice_number'] ?>', '<?= $kotPrintStatus ?>', '<?= $LocationID ?>', 1, 1, '<?= $kotPrintMethod ?>')" class="text-white w-100 btn btn-dark hold-button btn-lg mt-2"><i class="fa-solid fa-print btn-icon"></i> Reprint Full KOT</button>
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