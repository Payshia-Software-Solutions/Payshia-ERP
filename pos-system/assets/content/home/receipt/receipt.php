<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';


$Suppliers = GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);

// Parameters
$location_id = $_POST['location_id'];
$loggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$invoiceNumber = $_POST['invoiceNumber'];
$invoiceStatus = $_POST['invoiceStatus'];
$grandTotal = $_POST['grandTotal'];
$customerId = $_POST['customerId'];
$customerBalance = getCustomerBalance($link, $customerId);
$creditLimit = GetCustomerCreditLimit($link, $customerId);
$availableLimit = $creditLimit - $customerBalance;
if ($availableLimit < 0) {
    $availableLimit = 0;
}
$minPayment = $grandTotal - $availableLimit;
if ($minPayment < 0) {
    $minPayment = 0;
}

$PaymentTypes = GetPaymentTypes();


?>
<style>
    .due-amount {
        font-size: 50px;
    }
</style>

<div class="row g-3">
    <div class="col-md-12">
        <h3 class="mb-0">Payment Receipt for <?= $invoiceNumber ?></h3>
        <p class="border-bottom pb-2"></p>
    </div>
    <div class="col-12 mb-2">
        <p class="text-secondary text-center mb-0">Due Amount</p>
        <h1 class="text-center due-amount border-bottom pb-2">LKR <?= number_format($grandTotal, 2) ?></h1>
    </div>
    <div class="col-6 col-md-3">
        <p class="text-secondary mb-0">Customer</p>
        <h5 class=""><?= GetCustomerName($link, $customerId) ?></h5>
    </div>
    <div class="col-6 col-md-3">
        <p class="text-secondary mb-0">Customer Balance</p>
        <h5 class="">LKR <?= number_format($customerBalance, 2) ?></h5>
    </div>
    <div class="col-6 col-md-3">
        <p class="text-secondary mb-0">Credit Limit</p>
        <h5 class="">LKR <?= number_format($availableLimit, 2) ?></h5>
    </div>
    <div class="col-6 col-md-3">
        <p class="text-secondary mb-0">Minimum Payment</p>
        <h5 class="">LKR <?= number_format($minPayment, 2) ?></h5>
    </div>
</div>

<div class="p-3 border border-2 bg-light rounded-4 mt-4" id="product-selector">
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label">Select Payment Method</label>
            <select class="form-control" name="payment_type" id="payment_type" required autocomplete="off">
                <option value="">Select Payment Method</option>
                <?php
                if (!empty($PaymentTypes)) {
                    foreach ($PaymentTypes as $SelectedArray) {
                ?>
                        <option value="<?= $SelectedArray['id'] ?>"><?= $SelectedArray['text'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>



        <div class="col-12 col-md-4" id="amountSet">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control text-end" name="payment_amount" id="payment_amount" value="<?= $grandTotal ?>" placeholder="0.0">
        </div>


        <div class="col-md-2">
            <label class="form-label">Action</label>
            <button type="button" onclick="ProcessReceipt('<?= $invoiceNumber ?>', '<?= $customerId ?>', '<?= $location_id ?>')" class="btn btn-dark w-100 form-control">Proceed</button>
        </div>

    </div>
</div>