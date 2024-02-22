<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$Products = GetProducts($link);

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
        <h4 class="mb-0 fw-bold">Select Return Products</h4>
        <p class="mb-0 text-secondary border-bottom pb-2">Note : A La Carte Items cannot be Returned!</p>
    </div>

    <div class="inner-popup-container mt-2">

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Select Customer</label>
                <select onchange="getInvoiceLIstByCustomer(this.value)" class="form-control" name="customer_select" id="customer_select" required autocomplete="off">
                    <option value="">Select Customer</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Select Invoice (If Available)</label>
                <select onchange="getReturnItemsByInvoice(this.value)" class="form-control" name="select_invoice" id="select_invoice" required autocomplete="off">
                    <option value="">Select Invoice</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Reason</label>
                <input class="form-control" type="text" name="reason" id="reason" value="" placeholder="Enter Reason for Return">
            </div>
        </div>

        <div id="return-content"></div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('#select_product').select2({
            width: 'resolve'
        });
        $('#customer_select').select2({
            width: 'resolve'
        });

        $('#select_invoice').select2({
            width: 'resolve'
        });

        GetCustomerList()
    });

    // Initialize total amount
    var totalAmount = 0;

    function AddToTable() {
        // Read values from input fields
        var selectedItem = document.getElementById("select_product");
        var selectedOption = selectedItem.options[selectedItem.selectedIndex];
        var itemName = selectedOption.text;
        var productId = selectedOption.value;
        var qty = parseFloat(document.getElementById("new_quantity").value);
        var rate = parseFloat(document.getElementById("new_rate").value);

        // Check if item is selected
        if (productId === "") {
            showNotification("Please select an item.");
            return; // Stop execution if item is not selected
        }

        // Check if rate is greater than zero and not NaN
        if (isNaN(rate) || rate <= 0) {
            showNotification("Rate must be a valid number greater than zero.");
            return; // Stop execution if rate is not a valid number or negative
        }

        // Check if quantity is greater than zero and not NaN
        if (isNaN(qty) || qty <= 0) {
            showNotification("Quantity must be a valid number greater than zero.");
            return; // Stop execution if quantity is not a valid number or negative
        }

        var amount = qty * rate; // Calculate amount

        // Reference to the table body
        var tableBody = document.querySelector("#return-items tbody");

        // Check if the item already exists in the table
        var existingRow = tableBody.querySelector(`tr[data-productid="${productId}"]`);

        if (existingRow) {
            // If the item exists, update its quantity and amount
            var existingQty = parseFloat(existingRow.querySelector(".qty").textContent);
            var existingAmount = parseFloat(existingRow.querySelector(".amount").textContent);

            existingQty += qty;
            existingAmount += amount;

            existingRow.querySelector(".qty").textContent = existingQty.toFixed(2);
            existingRow.querySelector(".amount").textContent = existingAmount.toFixed(2);
        } else {
            // If the item doesn't exist, create a new row
            var newRow = document.createElement("tr");

            newRow.innerHTML = `
        <td>${productId}</td>
        <td class='itemName'>${itemName}</td>
        <td class='text-center qty'>${qty.toFixed(2)}</td>
        <td class='text-end'>${rate.toFixed(2)}</td>
        <td class='text-end amount'>${amount.toFixed(2)}</td>
        <td class='text-center'><button class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button></td>
    `;
            newRow.setAttribute("data-productid", productId);

            // Append the new row to the table
            tableBody.appendChild(newRow);
        }

        // Update total amount displayed
        totalAmount += amount;
        document.getElementById("totalAmount").textContent = totalAmount.toFixed(2);

        // Clear input fields
        document.getElementById("new_quantity").value = '';
        document.getElementById("new_rate").value = '';
        document.getElementById("select_product").value = '';
        document.getElementById("order_Unit").value = '';

        // Reinitialize Select2
        $('#select_product').select2('destroy');
        $('#select_product').select2({
            width: 'resolve'
        });
    }

    function removeRow(button) {
        // Find the row to be removed
        var rowToRemove = button.closest("tr");
        var totalAmount = parseFloat(document.getElementById("totalAmount").textContent.replace(/,/g, ''));

        // Update total amount
        var amountToRemove = parseFloat(rowToRemove.querySelector(".amount").textContent.replace(/,/g, ''));

        totalAmount -= amountToRemove;
        document.getElementById("totalAmount").textContent = totalAmount.toFixed(2);

        // Remove the row from the table
        rowToRemove.remove();
    }
</script>