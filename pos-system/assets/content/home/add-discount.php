<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$LocationID = $_POST['LocationID'];
$total = $_POST['total'];
?>

<style>
    .payment-value {
        padding: 10px;
        width: 100%;
        text-align: center;
        font-size: 45px;
        border-radius: 5px;
        border: 1px solid #b69f9f !important;
        outline: none;
        box-shadow: none;
        font-weight: 700;
    }

    /* 
    @media (max-width: 600px) {
        .payment-value {
            width: 80%;
        }
    } */

    .payment-value:focus {
        outline: none;
        box-shadow: none;
        border: 2px solid #3f2828;
    }


    .payment-button-container {
        display: flex;
        justify-content: center;
    }

    .payment-button {
        cursor: pointer;
        font-size: 20px;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        color: #000;
        margin-right: 5px;
        background-color: #fff;
        margin-bottom: 10px;
    }

    .payment-button:hover {
        background-color: #3498db;
        color: #fff;
    }

    .active-button {
        background-color: #3498db;
        color: #fff;
    }

    input:focus {
        box-shadow: none !important;
    }

    .loading-popup-content {
        max-width: 450px !important;
    }
</style>
<input type="hidden" id="total" value="<?= $total ?>">

<div class="row">
    <div class="col-md-5 text-center mb-3">
        <p class="mb-0">Bill Amount</p>
        <h1 class="my-0">LKR <?= number_format($total, 2) ?></h1>
    </div>
    <div class="col-md-7">
        <p class="mb-0">Discount Rate (%)</p>
        <input type="tel" class="payment-value" id="payment-value" name="payment-value" value="0" pattern="[0-9]*\.?[0-9]*">

        <div class="calculator mt-2">
            <div class="button" onclick="appendToPaymentInput('7')">7</div>
            <div class="button" onclick="appendToPaymentInput('8')">8</div>
            <div class="button" onclick="appendToPaymentInput('9')">9</div>
            <div class="button" onclick="appendToPaymentInput('4')">4</div>
            <div class="button" onclick="appendToPaymentInput('5')">5</div>
            <div class="button" onclick="appendToPaymentInput('6')">6</div>
            <div class="button" onclick="appendToPaymentInput('1')">1</div>
            <div class="button" onclick="appendToPaymentInput('2')">2</div>
            <div class="button" onclick="appendToPaymentInput('3')">3</div>
            <div class="button" onclick="appendToPaymentInput('0')">0</div>
            <div class="button" onclick="appendToPaymentInput('.')">.</div>
            <div class="button clear-button" onclick="clearPaymentInput()"><i class="fa-solid fa-delete-left"></i></div>
            <!-- <div class="button clear-button" onclick="backspaceInput()">←</div> -->
        </div>
    </div>
    <div class="col-12 mt-3">
        <button onclick="ReturnSetDiscount()" class="text-white w-100 btn btn-dark add-discount-button btn-lg p-4"><i class="fa-solid fa-plus btn-icon"></i> Add Discount</button>
    </div>
</div>

<script>
    function changeClass(button, PaymentType) {
        document.getElementById('payment-type').value = PaymentType
        const buttons = document.querySelectorAll('.payment-button');

        // Remove 'active' class from all buttons
        buttons.forEach(btn => btn.classList.remove('active-button'));

        // Add 'active' class to the clicked button
        button.classList.add('active-button');

    }
</script>
<script>
    // Get the input element

    function appendToPaymentInput(value) {
        var inputBox = document.getElementById('payment-value');
        var currentValue = inputBox.value;
        if (currentValue === '0' && value !== '.') {
            inputBox.value = value;
        } else {
            inputBox.value += value;
        }
    }

    function clearPaymentInput() {
        var inputBox = document.getElementById('payment-value');
        inputBox.value = inputBox.value.slice(0, -1); // Remove the last character
    }

    function ResetValue() {
        var inputBox = document.getElementById('payment-value');
        var total = document.getElementById('total').value;
        inputBox.value = total;
    }

    function ReturnSetDiscount() {
        var TableID = document.getElementById('set-table').getAttribute('data-id')
        var TableName = document.getElementById('set-table').value

        var stewardID = document.getElementById("set-steward").getAttribute("data-id");
        var stewardName = document.getElementById("set-steward").value;

        var CustomerID = document.getElementById('customer-id').getAttribute('data-id')
        var discount_rate = document.getElementById('payment-value').value
        var charge_status = document.getElementById('charge_status').value

        var close_type = document.getElementById('close_type').value
        var tendered_amount = document.getElementById('tendered_amount').value
        var invoice_number = document.getElementById('invoice_number').value

        if (discount_rate >= 0 && discount_rate <= 100) {
            OpenBillContainer(TableName, TableID, charge_status, discount_rate, close_type, tendered_amount, invoice_number, CustomerID, stewardName, stewardID)
            ClosePopUP()
        } else {
            showNotification('Please add Discount Rate')
        }


    }


    // Event listener for keyboard input
    document.addEventListener('keydown', function(event) {
        const key = event.key;

        // Trigger button click when Enter key is pressed
        if (key === 'Enter') {
            const addButton = document.querySelector('.add-discount-button');
            if (addButton) {
                addButton.click();
            }
        }
    });
</script>