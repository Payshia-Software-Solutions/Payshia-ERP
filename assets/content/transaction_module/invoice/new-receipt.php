<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);

// Parameters
$loggedUser = $_POST['LoggedUser'];
$UserLevel = $_POST['UserLevel'];
$invoiceNumber = $_POST['invoiceNumber'];
$invoiceStatus = $_POST['invoiceStatus'];
$grandTotal = $_POST['grandTotal'];
$customerId = $_POST['customerId'];
$customerBalance = getCustomerBalance($link, $customerId);
$creditLimit = GetCustomerCreditLimit($link, $customerId);
$availableLimit = $creditLimit - $customerBalance;
$minPayment = $grandTotal - $availableLimit;

if ($minPayment < 0) {
    $minPayment = 0;
}

$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];


?>
<style>
    .due-amount {
        font-size: 50px;
    }
</style>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Payment Receipt</h3>
            <p class="border-bottom pb-2"></p>
        </div>
        <div class="col-12 mb-2">
            <p class="text-secondary text-center mb-0">Due Amount</p>
            <h1 class="text-center due-amount border-bottom pb-2">LKR <?= number_format($grandTotal, 2) ?></h1>
        </div>
        <div class="col-3">
            <p class="text-secondary mb-0">Customer</p>
            <h5 class=""><?= GetCustomerName($link, $customerId) ?></h5>
        </div>
        <div class="col-3">
            <p class="text-secondary mb-0">Customer Balance</p>
            <h5 class="">LKR <?= number_format($customerBalance, 2) ?></h5>
        </div>
        <div class="col-3">
            <p class="text-secondary mb-0">Credit Limit</p>
            <h5 class="">LKR <?= number_format($availableLimit, 2) ?></h5>
        </div>
        <div class="col-3">
            <p class="text-secondary mb-0">Minimum Payment</p>
            <h5 class="">LKR <?= number_format($minPayment, 2) ?></h5>
        </div>
    </div>

    <div class="p-3 border border-2 bg-light rounded-4 mt-4" id="product-selector">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Select Payment Method</label>
                <select class="form-control" name="payment_type" id="payment_type" required autocomplete="off" onchange="goToValue(this.value)">
                    <option value="">Select Payment Method</option>
                    <option value="-1"><?= "Credit" ?></option>
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



            <div class="col-6 col-md-4" id="amountSet">
                <label class="form-label">Amount</label>
                <input type="number" step="0.01" onchange="validateMinAmount(this.value, '<?= $minPayment ?>', '<?= $grandTotal ?>')" min='<?= $minPayment ?>' max="<?= $grandTotal ?>" value="<?= $grandTotal ?>" class="form-control text-end" name="payment_amount" id="payment_amount" placeholder="0.0">
            </div>


            <div class="col-md-2">
                <label class="form-label">Action</label>
                <button type="button" onclick="ProcessInvoice(0, 2)" class="btn btn-dark w-100" style="height: 44px;">Proceed</button>
            </div>

        </div>
    </div>
</div>

<script>
    function goToValue(value) {
        if (value == -1) {
            // $('#amountSet').hide();
            $('#payment_amount').val(0)
        }
        $('#payment_amount').focus();
        $('#payment_amount').select()
    }
</script>