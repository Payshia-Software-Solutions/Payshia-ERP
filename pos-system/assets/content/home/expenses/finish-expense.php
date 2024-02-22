<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

$expenseTypes = GetExpensesTypes();
$LocationID = $_POST['LocationID'];
$expenseId = $_POST['expenseId'];

$expenseDetails = GetExpenseList()[$expenseId];

$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');

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

<div class="row">
    <div class="col-12 text-center mb-2">
        <i class="fa-solid fa-3x fa-circle-check text-success"></i>
    </div>
    <div class="col-md-6">
        <p class="my-0">EX #</p>
        <h4 class="my-0"><?= $expenseId ?></h4>
        <div class="row mt-3">
            <div class="col-6">
                <p class="my-0">Expense Amount</p>
                <h4 class="my-0">LKR <?= formatAccountBalance($expenseDetails['amount'], 2) ?></h4>
            </div>

            <div class="col-6">
                <p class="my-0">Type</p>
                <h4 class="my-0"><?= $expenseTypes[$expenseDetails['ex_type']]['type'] ?></h4>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <p class="my-0">Description</p>
                <h4 class="my-0"><?= $expenseDetails['ex_description'] ?></h4>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="row mt-3">
            <div class="col-12">
                <button type="button" class="text-white w-100 btn btn-dark hold-button btn-lg p-4"><i class="fa-solid fa-print btn-icon"></i> Print Expense Receipt</button>

            </div>


        </div>
    </div>
    <div class="col-12">
        <div class="col-12">
            <button onclick="OpenIndex()" type="button" class="text-white w-100 btn btn-success hold-button btn-lg p-4 mt-3"><i class="fa-solid fa-right-long btn-icon"></i> Next Customer</button>

        </div>
    </div>
</div>