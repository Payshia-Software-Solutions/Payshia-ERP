<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ProductID = $_POST['ProductID'];
$ItemPrice = $_POST['ItemPrice'];
$LocationID = $_POST['LocationID'];
$CurrentStockQty = $_POST['CurrentStock'];
$ItemDiscount = $_POST['ItemDiscount'];
$Product = GetProducts($link)[$ProductID];
$Units = GetUnit($link);
$itemDiscounts = MostUsedDiscounts($link, $ProductID);

$getHoldQty = GetHoldItemQtyNew($link, $LocationID);
$cartItemsByLocation = GetCartByLocation($link, $LocationID);

$holdItemQty = (isset($getHoldQty[$ProductID])) ? $getHoldQty[$ProductID] : 0;
$cartItemQty = (isset($cartItemsByLocation[$ProductID])) ? $cartItemsByLocation[$ProductID]['total_quantity'] : 0;
$totalHoldQty = $holdItemQty + $cartItemQty;

if ($Product['image_path'] == 'no-image.png') {
    $file_path = "../assets/images/products/no-image.png";
} else {
    $file_path = "./assets/images/products/" . $Product['product_id'] . "/" . $Product['image_path'];
}
$recipeType = $Product['recipe_type'];
$ItemType = $Product['item_type'];
$currentStock = GetStockBalanceByProductByLocation($link, $ProductID, $LocationID);
$item_unit = $Units[$Product['measurement']]['unit_name'];

$actualAvailableQty = $currentStock - $totalHoldQty;

$profitPercentage = (($Product['selling_price'] - $Product['cost_price']) / $Product['cost_price']) * 100;
$profit_ratio = number_format($profitPercentage, 2) . "%";

if ($recipeType == 0) {
    $recipeDisplay = "None";
} else if ($recipeType == 1) {
    $recipeDisplay = "A La Carte";
} else if ($recipeType == 2) {
    $recipeDisplay = "Item Recipe";
}

$ProductCode = $Product['product_code'];
$productSpecificBarcode = $Product['barcode'];
// $barcode = GenerateNormalBarcode($ProductCode);
$barcode = "";
?>

<style>
    .item-image {
        width: 100%;
        border-radius: 20px 20px;
        height: 220px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin-bottom: 10px;
    }
</style>
<div class="row mt-3">
    <div class="col-md-5">
        <div class="row">
            <div class="col-12">
                <h4><?= $Product['product_name'] ?><?= ($productSpecificBarcode != "")  ? ' - ' . $productSpecificBarcode : '' ?></h4>
            </div>
            <div class="col-12">
                <div class="item-image" style="background-image:url('<?= $file_path ?>')"></div>
            </div>
            <div class="col-3 col-md-3 mb-2">
                <p class="mb-0">Stock</p>
                <h6 class="mb-0"><?= $currentStock ?> (Hold: <?= $totalHoldQty ?>)</h6>
            </div>
            <div class="col-3 col-md-3 mb-2">
                <p class="mb-0">Unit</p>
                <h6 class="mb-0"><?= $item_unit ?></h6>
            </div>

            <div class="col-3 col-md-3 mb-2">
                <p class="mb-0">Price</p>
                <h6 class="mb-0"><?= $Product['selling_price'] ?></h6>
            </div>

            <div class="col-3 col-md-3 mb-2">
                <p class="mb-0">Min</p>
                <h6 class="mb-0 text-danger"><?= $Product['minimum_price'] ?></h6>
            </div>

            <div class="col-6 col-md-3 mb-2 d-none d-md-block">
                <p class="mb-0">Recipe</p>
                <h6 class="mb-0 text-secondary"><?= $recipeDisplay ?></h6>
            </div>

            <div class="col-3 col-md-3 mb-2 d-none d-md-block">
                <p class="mb-0">Wholesale</p>
                <h6 class="mb-0 text-secondary"><?= $Product['wholesale_price'] ?></h6>
            </div>
            <div class="col-3 col-md-3 mb-2 d-none d-md-block">
                <p class="mb-0">Barcode</p>
            </div>


        </div>
        <hr>
        <div class="row mb-2">
            <div class="col-12">
                <label for="remark">Remark</label>
                <input type="text" class="form-control p-3" name="item_remark" id="item_remark" placeholder="Enter Remark/Addon Here">
            </div>
            <div class="col-12 text-end mb-2">
                <?php

                if (!empty($itemDiscounts)) {
                    foreach ($itemDiscounts as $selectedArray) {

                        $itemDiscountPre = $selectedArray['item_discount'];
                        $itemDiscountPricePre = $ItemPrice - $selectedArray['item_discount'];
                ?>
                        <button type="button" onclick="SetItemDiscount('<?= $itemDiscountPre ?>', '<?= $itemDiscountPricePre ?>')" class="btn btn-secondary"><?= number_format($itemDiscountPricePre, 2) ?></button>
                <?php
                    }
                }
                ?>
            </div>
            <div class="col-6 col-md-5">
                <p class="mb-0">Item Discount</p>
                <input oninput="SetDiscountedPrice()" class="form-control p-3  text-center" min="0" type="number" step="0.1" name="itemDiscount" id="itemDiscount" placeholder="Item Discount" value="0" onclick="this.select()">
            </div>

            <div class="col-6 col-md-7">
                <p class="mb-0">Discounted Price</p>
                <input type="hidden" name="minPrice" id="minPrice" value="<?= $Product['minimum_price'] ?>">
                <input type="hidden" name="itemPrice" id="itemPrice" value="<?= $ItemPrice ?>">
                <input readonly class="form-control p-3 text-end" type="number" step="0.1" value="<?= $ItemPrice ?>" name="discountedPrice" id="discountedPrice" placeholder="Discounted Price">
            </div>
        </div>
    </div>
    <div class="col-md-7 mt-2 mt-md-2" style="padding-right: 0px;">
        <div class="border-bottom d-md-none mb-2"></div>

        <h4>Select Quantity </h4>
        <div class="input-box">
            <input type="hidden" id="stock_balance" value="<?= $currentStock ?>">
            <input type="hidden" id="item-price" value="<?= $ItemPrice ?>">
            <input type="hidden" id="item-discount" value="<?= $ItemDiscount ?>">
            <input type="text" id="qty-input" class="qty-input" readonly>
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
            <!-- <div class="button clear-button" onclick="backspaceInput()">←</div> -->
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <?php
                if ($actualAvailableQty > 0 || $recipeType == "1" || $ItemType == "SService") { ?>
                    <button onclick="ValidateStockToCart('<?= $recipeType ?>', '<?= $LocationID ?>', '<?= $ProductID ?>', '<?= $ItemType ?>')" class="add-button text-white w-100 btn btn-dark hold-button btn-lg p-4"><i class="fa-solid fa-plus btn-icon"></i> Add</button>
                <?php
                } else {
                ?>
                    <div class="card bg-danger">
                        <div class="card-body ">
                            <h4 class="mb-0 text-center text-white">Insufficient Stock to Proceed</h4>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
    </div>

</div>
<script>
    var isItemDiscountFocused = false;
    var itemDiscountElement = document.getElementById('itemDiscount');
    itemDiscountElement.addEventListener('focus', function() {
        isItemDiscountFocused = true;
    });

    // Add a blur event listener to reset the flag when focus is lost
    itemDiscountElement.addEventListener('blur', function() {
        isItemDiscountFocused = false;
    });


    function SetDiscountedPrice() {
        var itemDiscount = document.getElementById('itemDiscount').value;
        var itemPrice = parseFloat(document.getElementById('itemPrice').value);
        var minPrice = parseFloat(document.getElementById('minPrice').value);

        var ItemDiscountValue = parseFloat(itemDiscount);
        var discountedPrice = itemPrice - itemDiscount;
        if (itemDiscount == "") {
            document.getElementById('discountedPrice').value = parseFloat(itemPrice).toFixed(2)
        }
        if (discountedPrice >= minPrice) {
            document.getElementById('discountedPrice').value = parseFloat(discountedPrice).toFixed(2)
        } else {
            document.getElementById('discountedPrice').value = parseFloat(itemPrice).toFixed(2)
            document.getElementById('itemDiscount').value = 0
            document.getElementById('itemDiscount').select()
            showNotification("Discounted Price cannot be less than Minimum Selling Price!");
        }

    }

    function SetItemDiscount(itemDiscountPre, itemDiscountPricePre) {
        document.getElementById('itemDiscount').value = parseFloat(itemDiscountPre).toFixed(2);
        document.getElementById('discountedPrice').value = parseFloat(itemDiscountPricePre).toFixed(2);
    }
</script>

<script>
    // Function to update the input value
    function updateInput(value) {
        try {
            const qtyInput = document.getElementById('qty-input');
            const currentValue = qtyInput.value;

            if (value === 'C') {
                qtyInput.value = '';
            } else if (value === 'Backspace') { // Check for backspace
                qtyInput.value = qtyInput.value.slice(0, -1); // Remove the last character
            } else if (/^\d$/.test(value)) {
                // Allow digits 0-9
                qtyInput.value += value;
            } else if (value === '.' && !currentValue.includes('.')) {
                // Allow a single decimal point
                qtyInput.value += value;
            }

            // Remove the input event listener after updating the value
            qtyInput.removeEventListener('input', restrictInput);
        } catch (error) {

        }

    }

    // Event listener for restricting input to numeric characters
    function restrictInput(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9.]/g, '');
    }

    // Event listener for the backspace key
    function backspaceInput() {
        const qtyInput = document.getElementById('qty-input');
        qtyInput.value = qtyInput.value.slice(0, -1); // Remove the last character
    }

    let qtyInput = document.getElementById('qty-input');

    // Add event listeners
    qtyInput.addEventListener('input', restrictInput);
    qtyInput.addEventListener('keydown', function(event) {
        // Prevent the default backspace behavior to avoid navigating the page
        if (event.key === 'Backspace') {
            event.preventDefault();
            backspaceInput();
        }
    });
    // Event listener for keyboard input
    document.addEventListener('keydown', function(event) {
        const key = event.key;
        const ctrlKey = event.ctrlKey || event.metaKey; // Check for Ctrl key

        // Allow system shortcuts like "Ctrl + R"
        if (ctrlKey && (key === 'r' || key === 'R')) {
            return;
        }

        // Check if the key is a number, a decimal point, backspace, or 'C' (for clear)
        if (/[\d\.CB]/.test(key) && !isItemDiscountFocused) {

            updateInput(key);

            // event.preventDefault(); // Prevent default key behavior
        } else {
            // event.preventDefault(); // Prevent other keys from being input
        }

        // Trigger button click when Enter key is pressed
        if (key === 'Enter') {
            const addButton = document.querySelector('.add-button');
            if (addButton) {
                addButton.click();
            } else {
                showNotification("Insufficient Stock to Proceed");
            }
        }
    });

    // Add an event listener to restrict input to numeric characters when typing directly into the input box
    qtyInput.addEventListener('input', function(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9.]/g, '');
    });
</script>