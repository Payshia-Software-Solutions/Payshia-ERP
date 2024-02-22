<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';

$Suppliers =  GetSupplier($link);
$Locations = GetLocations($link);
$Products = GetProducts($link);
$location_name =  $tax_type = $supplier_id = $location_id = "";
$Currency = "LKR";
$order_date = date('Y-m-d');
$Units = GetUnit($link);
$LoggedUser = $_POST['LoggedUser'];
$po_number = 0;

$ClearResult = ClearTempPO($link, $LoggedUser);
// echo ($ClearResult);

$location_id = GetUserDefaultValue($link, $LoggedUser, 'defaultLocation');
$TempOrder =  GetTempPO($link, $LoggedUser);
if (!empty($TempOrder)) {
    $order_date = date('Y-m-d');
    $Currency = 'LKR';
    $location_name = $Locations[$TempOrder[0]['location_id']]['location_name'];
    $tax_type = $TempOrder[0]['tax_type'];
    $supplier_id = $TempOrder[0]['supplier_id'];
    $location_id = $TempOrder[0]['location_id'];
}

?>
<div class="row my-4">
    <div class="col-12">
        <div class="card" id="">
            <div class="card-body">


                <div class="row">
                    <div class="col-12 text-end"><button class="btn-warning btn" onclick="NewInvoice()">
                            <i class="clickable fa-solid fa-rotate-right"></i>
                        </button>
                        <button class="btn-success btn" onclick="OpenIndex()">
                            <i class="clickable fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <h1 class="card-title border-bottom pb-2" style="font-size: 40px;">Invoice</h1>

                <div class="mb-3">
                    <form id="action-form" method="post">
                        <div class="row mb-3">
                            <div class="col-4 col-md-2">
                                <label class="form-label">Date</label>
                            </div>
                            <div class="col-8 col-md-5">
                                <input type="date" class="form-control" id="po-date" name="po-date" value="<?= $order_date ?>" readonly>
                            </div>

                            <div class="col-4 col-md-2 mt-3 mt-md-0">
                                <label class="form-label text-md-end">Currency</label>
                            </div>
                            <div class="col-8 col-md-3 mt-3 mt-md-0">
                                <input type="text" name="currency" id="currency" class="form-control" value="<?= $Currency ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-4 col-md-2">
                                <label class="form-label">Location</label>
                            </div>
                            <div class="col-8 col-md-5">
                                <select class="form-select" name="location_id" id="location_id" required autocomplete="off" onchange="GetCustomerList() ">
                                    <option value="">Select Location</option>
                                    <?php
                                    if (!empty($Locations)) {
                                        foreach ($Locations as $SelectedArray) {
                                            if ($SelectedArray['is_active'] != 1) {
                                                continue;
                                            }
                                    ?>

                                            <option <?= ($SelectedArray['location_id'] == $location_id) ? 'selected' : '' ?> value="<?= $SelectedArray['location_id'] ?>"><?= $SelectedArray['location_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="col-4 col-md-2 mt-3 mt-md-0">
                                <label class="form-label  text-md-end">Due Date</label>
                            </div>
                            <div class="col-8 col-md-3 mt-3 mt-md-0">
                                <input type="date" class="form-control" id="po-date" name="po-date" value="<?= $order_date ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 col-md-2">
                                <label class="form-label">Select Customer</label>
                            </div>
                            <div class="col-8 col-md-5">
                                <select class="form-control" name="customer_select" id="customer_select" required autocomplete="off">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>


                            <div class="col-4 col-md-2 mt-3 mt-md-0">
                                <label class="form-label  text-md-end">Invoice Type</label>
                            </div>
                            <div class="col-8 col-md-3 mt-3 mt-md-0">
                                <select class="form-control" name="invoice_type" id="invoice_type" required autocomplete="off">
                                    <option value="-1">Retail</option>
                                    <option value="-2">Delivery</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-3 border border-2 bg-light rounded-4 mt-4" id="product-selector"></div>
                    </form>


                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  border-top border-3 mt-4" id="order-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Item/Service</th>
                                            <th class="text-center" scope="col">Quantity</th>
                                            <th class="text-center" scope="col">Unit</th>
                                            <th class="text-end" scope="col">Per Unit Rate</th>
                                            <th class="text-end" scope="col">Amount</th>
                                            <th class="text-center" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inv_content">


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end" scope="col">Sub Total</th>
                                            <th colspan="2" class="text-end" scope="col" id="sub_total_value">0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-end" scope="col">Discount (%)</th>
                                            <th class="text-center" scope="col" id="discount">
                                                <input onchange="updateDiscount()" class="border-secondary rounded-3 text-center" style="max-width: 120px;" type="number" step="0.01" id="dis_percentage" name="dis_percentage" placeholder="0.00"> %
                                            </th>
                                            <th colspan="2" class="text-end" scope="col" id="discount_value">0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-end" scope="col">Grand Total</th>
                                            <th colspan="2" class="text-end" scope="col" id="grand_total_value">0.00</th>
                                        </tr>


                                        <input type="hidden" id="tax_rate_hidden" value="0">
                                        <input type="hidden" id="tax_amount_hidden" value="0">
                                        <input type="hidden" id="sub_total_hidden" value="">
                                        <input type="hidden" id="discount_percentage_hidden" value="">
                                        <input type="hidden" id="discount_value_hidden" value="">
                                        <input type="hidden" id="grand_total_hidden" value="">
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3 mt-3">
                        <div class="col-4 col-md-2 mt-3 mt-md-0">
                            <label class="form-label">Remark</label>
                        </div>
                        <div class="col-8 col-md-10 mt-3 mt-md-0">
                            <input type="text" class="form-control" placeholder="Add Comment and Instruction here" name="remarks" id="remarks">
                        </div>
                    </div>
                </div>


                <div class="row mb-3 mt-5">
                    <div class="col-12 text-end">
                        <button class="mt-0 mb-1 btn  btn-success view-button" type="button" onclick="CreateReceipt(0,2)"><i class="fa-solid fa-check"></i> Proceed to Payment</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {


        $('#location_id').select2({
            width: 'resolve'
        });

        $('#supplier_id').select2({
            width: 'resolve'
        });
        $('#customer_select').select2({
            width: 'resolve'
        });

    });

    // Define a variable to keep track of the row number
    var rowCount = 1;

    // Function to add a new row to the table
    function AddToInvoice() {
        // Get selected product details
        var selectedProduct = document.getElementById('select_product');
        var productID = selectedProduct.value
        var productInfo = selectedProduct.options[selectedProduct.selectedIndex].text.split(' - ');

        // Get other input values
        var quantity = document.getElementById('new_quantity').value;
        var unit = document.getElementById('order_Unit').value;
        var rate = parseFloat(document.getElementById('new_rate').value);
        var stockBalance = parseFloat(document.getElementById('stockBalance').value);


        // Validate input
        if (selectedProduct.value === '' || quantity === "" || rate === '') {
            OpenAlert('error', 'Error!', 'Please fill in all fields.')
            return;
        }
        quantity = parseFloat(quantity)


        if (stockBalance < quantity) {
            OpenAlert('error', 'Error!', 'Insufficient Stock Balance')
            return;
        }

        // Calculate the amount
        var amount = (parseFloat(quantity) * parseFloat(rate)).toFixed(2);

        // Get the table body
        var tableBody = document.getElementById('order-table').getElementsByTagName('tbody')[0];

        // Check if the product is already in the table
        var existingRow = findExistingRow(productInfo[0]);

        if (existingRow) {
            // Update quantity and amount for existing row
            var existingQuantity = parseFloat(existingRow.cells[2].innerHTML);
            var existingAmount = parseFloat(existingRow.cells[5].innerHTML);

            existingRow.cells[2].innerHTML = (parseFloat(quantity)).toFixed(3);
            existingRow.cells[5].innerHTML = (parseFloat(amount)).toFixed(2);
        } else {
            // Create a new row
            var newRow = tableBody.insertRow(tableBody.rows.length);

            // Insert cells into the new row
            var cellNumber = newRow.insertCell(0);
            cellNumber.classList.add("text-start"); // Add the "col-md-1" class to the cell
            cellNumber.innerHTML = productID;

            var cellProduct = newRow.insertCell(1);
            cellProduct.classList.add("text-start"); // Add the "col-md-2" class to the cell
            cellProduct.innerHTML = productInfo[0];


            var cellQuantity = newRow.insertCell(2);
            cellQuantity.classList.add("text-center"); // Add the "text-center" and "col-md-2" classes to the cell
            cellQuantity.innerHTML = quantity;

            var cellUnit = newRow.insertCell(3);
            cellUnit.classList.add("text-start"); // Add the "text-center" and "col-md-1" classes to the cell
            cellUnit.innerHTML = unit;

            var cellRate = newRow.insertCell(4);
            cellRate.classList.add("text-end"); // Add the "text-end" and "col-md-2" classes to the cell
            cellRate.innerHTML = rate;

            var cellAmount = newRow.insertCell(5);
            cellAmount.classList.add("text-end"); // Add the "text-end" and "col-md-2" classes to the cell
            cellAmount.innerHTML = amount;

            var cellAction = newRow.insertCell(6);
            cellAction.classList.add("text-center"); // Add the "text-center" and "col-md-1" classes to the cell
            cellAction.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="RemoveRow(this)"><i class="fa-solid fa-trash"></i></button>';

            // Increment the row count
            rowCount++;
        }

        updateSubtotal()
        // Clear input fields
        selectedProduct.value = '';
        document.getElementById('new_quantity').value = '';
        document.getElementById('new_rate').value = '';
        document.getElementById('stockBalance').value = '';
        document.getElementById('order_Unit').value = '';


        getProductSelector()

    }


    // Function to find an existing row with the same product
    function findExistingRow(productName) {
        var table = document.getElementById('order-table');
        var rows = table.getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            if (cells.length > 1 && cells[1].innerHTML === productName) {
                return rows[i];
            }
        }

        return null;
    }

    // Function to remove a row from the table
    function RemoveRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);

        updateSubtotal()
    }

    // Function to update the subtotal row
    function updateSubtotal() {
        var table = document.getElementById('inv_content');
        var rows = table.getElementsByTagName('tr');
        var subtotal = 0;

        for (var i = 0; i < rows.length; i++) { // Exclude header row
            var cells = rows[i].getElementsByTagName('td');

            // Ensure that the row has at least 7 cells (adjust based on your HTML structure)
            if (cells.length >= 6) {
                var amountCell = cells[5];
                subtotal += parseFloat(amountCell.innerHTML);
            }
        }


        // Find the existing subtotal row
        var subtotalRow = document.getElementById('sub_total_row');

        // Update the subtotal value cell
        var cellValue = document.getElementById('sub_total_value');
        cellValue.innerHTML = formatCurrency(subtotal);

        document.getElementById('sub_total_hidden').value = subtotal.toFixed(2)

        updateDiscount()
    }



    // Function to update the discount amount based on the percentage input
    function updateDiscount() {
        var disPercentageInput = document.getElementById('dis_percentage');
        var discountAmountCell = document.getElementById('discount_value');
        var subtotalValueCell = document.getElementById('sub_total_hidden');

        if (disPercentageInput && discountAmountCell && subtotalValueCell) {
            var disPercentage = parseFloat(disPercentageInput.value) || 0;
            var subtotalValue = parseFloat(subtotalValueCell.value) || 0;

            var discountAmount = (subtotalValue * disPercentage / 100).toFixed(2);
            discountAmountCell.innerHTML = formatCurrency(discountAmount);

            // Update the grand total
            var grandTotalCell = document.getElementById('grand_total_value');
            if (grandTotalCell) {
                var grandTotal = (subtotalValue - discountAmount).toFixed(2);
                grandTotalCell.innerHTML = formatCurrency(grandTotal);
            }

            document.getElementById('discount_value_hidden').value = discountAmount
            document.getElementById('discount_percentage_hidden').value = disPercentage
            document.getElementById('grand_total_hidden').value = grandTotal
        }
    }



    // Function to format a number without currency symbol
    function formatCurrency(value) {
        return new Intl.NumberFormat('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }
</script>