<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/settings_functions.php';

$invoiceNumber = $_POST['invoiceNumber'];
$LocationID = $_POST['location_id'];

$invoiceItems = GetInvoiceItemsPrint($link, $invoiceNumber);
$Products = GetProducts($link);
$Units = GetUnit($link);
$returnList = GetReturns();
$invoiceReturnItemList = GetReturnItemsByInvoice($invoiceNumber);
$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
$receiptPrintMethod  = GetSetting($link, $LocationID, 'receiptPrintMethod');
$totalAmount = 0;
?>
<h6 class="fw-bold">Returnable Item List for <?= $invoiceNumber ?></h6>

<div class="table-responsive" id="return-items">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $buttonStatus = 0;
            $rtnListStatus = 0;
            if (!empty($invoiceItems)) {
                foreach ($invoiceItems as $selectedArray) {
                    $productId = $selectedArray['product_id'];
                    $itemPrice = $selectedArray['item_price'];
                    $itemQty = $selectedArray['quantity'];
                    $item_discount = $selectedArray['item_discount'];

                    $display_name = $Products[$selectedArray['product_id']]['display_name'];
                    $amount = ($itemPrice - $item_discount) * $itemQty;

                    if ($Products[$productId]['recipe_type'] == 1) {
                        continue;
                    }

                    if (isset($invoiceReturnItemList[$productId])) {
                        $rtnListStatus = 1;
                        if ($invoiceReturnItemList[$productId]['item_qty'] == $itemQty) {
                            continue;
                        }

                        $itemQty = $itemQty - $invoiceReturnItemList[$productId]['item_qty'];
                    }

                    $buttonStatus = 1;
                    $totalAmount += $amount;
            ?>
                    <tr>
                        <td><?= $productId ?></td>
                        <td class="itemName"><?= $display_name ?></td>
                        <td class='text-center qty'><span class="editable clickable bg-light fw-bold rounded-3 p-1 px-2" onclick="openEditModal(this)"><?= $itemQty ?></span></td>
                        <td class='text-end'><?= number_format($itemPrice - $item_discount, 2) ?></td>
                        <td class='text-end amount'><?= number_format($amount, 2) ?></td>
                        <td class='text-center'><button class="btn btn-danger btn-sm mb-0" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total</th>
                <th colspan="2" class='text-center' id="totalAmount"><?= number_format($totalAmount, 2) ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<?php
if ($buttonStatus == 1) {
?>
    <div class="row">
        <div class="col-12 text-end">
            <button onclick="SaveReturn('<?= $receiptPrinterStatus ?>', '<?= $receiptPrintMethod ?>')" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Save Return</button>
            <!-- <button class="btn btn-warning"><i class="fa-solid fa-money-bill-trend-up"></i> Return & Refund</button> -->
        </div>
    </div>
<?php
} else {
?>
    <div class="alert alert-warning text-center fw-bold">No Returnable Items</div>
<?php
} ?>


<?php
if ($rtnListStatus == 1) {
?>

    <h6 class="fw-bold">Returned Item List for <?= $invoiceNumber ?></h6>
    <div class="table-responsive" id="returned-items">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php

                $totalAmount = 0;
                if (!empty($invoiceItems)) {
                    foreach ($invoiceItems as $selectedArray) {
                        $productId = $selectedArray['product_id'];
                        $itemPrice = $selectedArray['item_price'];
                        $itemQty = $selectedArray['quantity'];

                        $display_name = $Products[$selectedArray['product_id']]['display_name'];
                        $amount = $itemPrice * $itemQty;

                        $totalAmount += $amount;

                        if (!isset($invoiceReturnItemList[$productId])) {
                            continue;
                        }

                        $itemQty = $invoiceReturnItemList[$productId]['item_qty'];

                ?>

                        <tr>
                            <td><?= $productId ?></td>
                            <td class="itemName"><?= $display_name ?></td>
                            <td class='text-center qty'><span class="editable" onclick="openEditModal(this)"><?= $itemQty ?></span></td>
                            <td class='text-end'><?= number_format($itemPrice, 2) ?></td>
                            <td class='text-end amount'><?= number_format($amount, 2) ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total</th>
                    <th class='text-end' id="totalAmount"><?= number_format($totalAmount, 2) ?></th>

                </tr>
            </tfoot>
        </table>
    </div>
<?php
}
?>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    @media (max-width: 600px) {
        .modal-content {
            width: 80%;
        }
    }
</style>

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="text-end">
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <h4 class="mb-0">Edit Quantity</h4>
        <p><span id="itemName"></span></p>
        <input class="form-control text-center" type="number" oninput="ValidateInputMaxValue()" onclick="this.select()" id="quantityInput">
        <p class="text-end text-secondary mt-0 mb-3">Max Value : <span id="maxValue"></span></p>
        <button class="btn btn-dark" id="submitBtn">Save</button>
    </div>
</div>


<script>
    // Open the edit modal
    function openEditModal(element) {
        var modal = document.getElementById("editModal");
        var quantityInput = document.getElementById("quantityInput");
        var itemName = element.closest("tr").querySelector("td:nth-child(2)").textContent.trim(); // Get the item name from the second <td> in the row

        quantityInput.value = element.textContent.trim();

        modal.querySelector("#maxValue").textContent = quantityInput.value; // Set item name in modal
        modal.querySelector("#itemName").textContent = itemName; // Set item name in modal
        modal.style.display = "block";
        quantityInput.focus()
        quantityInput.select()

        // Pass a reference to the clicked element to the saveQuantity function
        document.getElementById("submitBtn").onclick = function() {
            saveQuantity(element);
        };
    }

    function ValidateInputMaxValue() {
        var maxValue = parseFloat(document.getElementById("maxValue").textContent.trim())
        var newValue = parseFloat(quantityInput.value);
        // alert(newValue)

        // Check if the new value is greater than 0 and less than or equal to the max value
        if (isNaN(newValue) || newValue <= 0) {
            // If the input is empty or less than or equal to 0, set it to 1
            quantityInput.value = 1;
        } else if (newValue > maxValue) {
            // If the new value is greater than the max value, set it to the max value
            quantityInput.value = maxValue;
        }
    }

    // Close the edit modal
    function closeEditModal() {
        var modal = document.getElementById("editModal");
        modal.style.display = "none";
    }

    // Save the edited quantity
    function saveQuantity(element) {
        var modal = document.getElementById("editModal");
        var quantityInput = document.getElementById("quantityInput");
        var newValue = parseFloat(quantityInput.value.trim());
        newValue = newValue.toFixed(3);


        // Update the text content of the clicked element
        element.textContent = newValue;

        // Close the modal
        modal.style.display = "none";
    }
</script>