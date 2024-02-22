<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/settings_functions.php';

$expenseTypes = GetExpensesTypes();
$LocationID = $_POST['LocationID'];

$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');
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
        <h4 class="mb-0 fw-bold  border-bottom pb-2">Enter Details of Expense</h4>
    </div>

    <div class="inner-popup-container mt-2">
        <form action="#" method="post" id="expense-form">
            <div class="row g-2 g-md-3">

                <div class="col-md-6">
                    <label class="form-label">Select Type</label>
                    <select class="form-control form-control-lg" name="expenseType" id="expenseType" required autocomplete="off">
                        <option value="">Select Type</option>
                        <?php
                        if (!empty($expenseTypes)) {
                            foreach ($expenseTypes as $selectedArray) {
                        ?>
                                <option value="<?= $selectedArray['id'] ?>"><?= $selectedArray['type'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <input required onclick="this.select()" class="form-control form-control-lg text-end" type="number" name="expenseAmount" id="expenseAmount" value="0.00" placeholder="Enter Amount">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <input class="form-control form-control-lg" type="text" name="exDescription" id="exDescription" value="" placeholder="Enter Description for Expense" required>
                </div>

                <div class="col-12 text-end">
                    <button onclick="SaveExpense('<?= $receiptPrinterStatus ?>', '<?= $receiptPrintMethod ?>')" type="button" class="btn btn-dark"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </div>

            </div>
        </form>
    </div>
</div>