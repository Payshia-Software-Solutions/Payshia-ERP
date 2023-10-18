<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$ProductID = $_POST['ProductID'];
$ItemPrice = $_POST['ItemPrice'];
$CurrentStockQty = $_POST['CurrentStock'];
$ItemDiscount = $_POST['ItemDiscount'];
$Product = GetProducts($link)[$ProductID];
?>

<div class="row mt-3">
    <h3>Select Qty of <?= $Product['product_name'] ?></h3>
</div>
<div class="row mt-3">
    <div class="col-12" style="padding-right: 0px;">
        <div class="input-box">
            <input type="hidden" id="item-price" value="<?= $ItemPrice ?>">
            <input type="hidden" id="item-discount" value="<?= $ItemDiscount ?>">
            <input type="text" id="qty-input" class="qty-input">
        </div>
        <div class="calculator">
            <div class="button" onclick="appendToInput('7')">7</div>
            <div class="button" onclick="appendToInput('8')">8</div>
            <div class="button" onclick="appendToInput('9')">9</div>
            <div class="button" onclick="appendToInput('4')">4</div>
            <div class="button" onclick="appendToInput('5')">5</div>
            <div class="button" onclick="appendToInput('6')">6</div>
            <div class="button" onclick="appendToInput('1')">1</div>
            <div class="button" onclick="appendToInput('2')">2</div>
            <div class="button" onclick="appendToInput('3')">3</div>
            <div class="button" onclick="appendToInput('0')">0</div>
            <div class="button" onclick="appendToInput('.')">.</div>
            <div class="button clear-button" onclick="clearInput()">C</div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" style="padding-right: 0px;">
        <button onclick="AddToCart('<?= $ProductID ?>')" class="add-button text-white w-100 btn btn-dark hold-button btn-lg p-4"><i class="fa-solid fa-plus btn-icon"></i> Add</button>
    </div>
</div>

<script>
    "use strict"; // Enable strict mode

    let listenersAdded = false;

    // Function to update the input value
    function updateInput(value) {
        const qtyInput = document.getElementById('qty-input');
        const currentValue = qtyInput.value;

        if (value === 'C') {
            qtyInput.value = '';
        } else if (/^\d$/.test(value)) {
            // Allow digits 0-9
            qtyInput.value += value;
        } else if (value === '.' && !currentValue.includes('.')) {
            // Allow a single decimal point
            qtyInput.value += value;
        }
    }

    // Function to restrict input to numeric characters
    function restrictInput(e) {
        const key = e.key;

        if (/[\d\.C]/.test(key)) {
            alert(key);
            updateInput(key);
            e.preventDefault(); // Prevent default key behavior
        } else {
            e.preventDefault(); // Prevent other keys from being input
        }
    }


    (function() {
        if (!listenersAdded) {
            const qtyInput = document.getElementById('qty-input');

            // Add an event listener to restrict input to numeric characters
            qtyInput.addEventListener('input', restrictInput);

            // Event listener for keyboard input
            document.addEventListener('keydown', function(event) {
                const key = event.key;
                const ctrlKey = event.ctrlKey || event.metaKey; // Check for Ctrl key

                // Allow system shortcuts like "Ctrl + R"
                if (ctrlKey && (key === 'r' || key === 'R')) {
                    return;
                }

                // Trigger button click when Enter key is pressed
                if (key === 'Enter') {
                    const addButton = document.querySelector('.add-button');
                    if (addButton) {
                        addButton.click();
                    }
                }
            });

            listenersAdded = true;
        }
    })();
</script>