<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/settings_functions.php';
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = $_POST['LocationID'];
$LoggedUser = $_POST['LoggedUser'];
$TableName = $_POST['TableName'];
$TableID = $_POST['TableID'];
$ServiceChargeStatus = $_POST['ServiceChargeStatus'];
$discount_rate = $_POST['DiscountRate'];
$invoice_number = $_POST['invoice_number'];

$stewardName =  $_POST['stewardName'];
$stewardID = $_POST['stewardID'];

$close_type = $_POST['CloseType'];
$tendered_amount = $_POST['TenderedAmount'];

$CustomerID = $_POST['CustomerID'];
$CustomerName = GetCustomerName($link, $CustomerID);
$CartProducts = GetCart($link, $LoggedUser);
$CurrencySelected = "LKR";
$sub_total = $total = 0;

$discount_amount = 0;
$location_id  = 1;
$charge_status = 1;
if ($TableID <= 0) {
    $ServiceChargeStatus = 0;
}

$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];

if (!empty($CartProducts)) {
    $total = array_reduce($CartProducts, function ($carry, $SelectRecord) {
        $selling_price = $SelectRecord['item_price'];
        $item_quantity = $SelectRecord['quantity'];
        $item_discount = $SelectRecord['item_discount'];
        $line_total = ($selling_price - $item_discount) * $item_quantity;
        return $carry + $line_total;
    }, 0);
}


if ($ServiceChargeStatus == 1) {
    $taxRate = 0.1;
} else {
    $taxRate = 0;
}

$discount_amount = $total * ($discount_rate / 100);
$sub_total = $total - $discount_amount;

$taxAmount = $sub_total * $taxRate;
$grand_total = $sub_total + $taxAmount;

if ($tendered_amount < $grand_total) {
    $tendered_amount = 0;
}

if ($close_type == -1) {
    $paymentMethod = "Credit";
    $tendered_amount = 0;
} else {
    $paymentMethod =  $PaymentTypes[$close_type]['text'];
}

$change_amount = max($tendered_amount - $grand_total, 0);


?>

<style>
    .item-area {
        height: 800px;
    }

    .item-area::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        background-image: url('./assets/images/payshia-logo.png');
        background-size: 50%;
        background-position: center;
        background-repeat: no-repeat;
    }

    .item-area h4 {
        margin-bottom: 0px;
    }

    /* 
    .button-text {
        font-size: 12px !important;
    }

    .action-button {
        font-size: 14px !important;
    } */

    .button-labels {
        font-size: 12px;
    }
</style>

<?php
$kotPrintStatus = GetSetting($link, $LocationID, 'kot_printer');
$receiptPrinterStatus = GetSetting($link, $LocationID, 'receipt_printer');
?>


<div class="row mt-0 g-1 mt-md-0">
    <div class="col-6 d-flex">
        <button onclick="ProcessInvoice('<?= $invoice_number ?>', '1' , '<?= $kotPrintStatus ?>' )" class=" flex-fill text-white w-100 btn btn-info hold-button btn-lg action-button p-md-3"><i class="fa-solid fa-pause btn-icon"></i> Hold</button>
    </div>
    <div class="col-6 d-flex">
        <?php
        if ($tendered_amount < $grand_total && $close_type != -1) {
        ?>
            <button onclick="SetPayment()" class="text-white flex-fill w-100 btn btn-dark hold-button btn-lg action-button"><i class="fa-solid fa-money-bill btn-icon p-md-3"></i> Payment</button>
        <?php } else { ?>
            <button onclick="ProcessInvoice('<?= $invoice_number ?>', '2', '<?= $receiptPrinterStatus ?>')" class="text-white w-100 btn btn-success hold-button btn-lg action-button p-md-3"><i class="fa-solid fa-check btn-icon"></i> Proceed</button>
        <?php } ?>
    </div>
    <?php
    if ($invoice_number != '0') { ?>
        <div class="col-12 d-flex">
            <button onclick="PrintGuestReceipt('<?= $invoice_number ?>', '<?= $receiptPrinterStatus ?>', '<?= $LocationID ?>', 1)" class=" flex-fill text-white w-100 btn btn-secondary hold-button btn-lg action-button p-md-3"><i class="fa-solid fa-receipt btn-icon"></i> Guest Receipt</button>
        </div>
    <?php } ?>
</div>

<div class="card mt-2">
    <div class="bg-white p-3 pb-0" style="z-index: 2;">
        <div class="row">
            <div class="col-6" style="padding-right: 0px;">
                <label class="button-labels">Customer</label>
                <input type="text" id="customer-id" class="flex-fill w-100 btn btn-light button-text" onclick="SelectCustomer()" value="<?= $CustomerName ?>" data-id="<?= $CustomerID ?>" readonly>
            </div>
            <div class="col-6">
                <label class="button-labels">Table</label>
                <input type=" text" id="set-table" class="flex-fill w-100 btn btn-light button-text" onclick="SetTable()" value="<?= $TableName ?>" data-id="<?= $TableID ?>" readonly>
            </div>

            <div class="col-6" style="padding-right: 0px;">
                <label class="button-labels">Steward</label>
                <input type="text" id="set-steward" class="flex-fill w-100 btn btn-light button-text" onclick="SetSteward()" value="<?= $stewardName ?>" data-id="<?= $stewardID ?>" readonly>
            </div>
            <div class="col-6">
                <label class="button-labels">Hold List</label>
                <button type="button" id="hold-list" class="flex-fill w-100 btn btn-light button-text" onclick="GetHoldInvoices ('<?= $LocationID ?>', 1)">
                    Holds </button>
            </div>
        </div>

        <div class="row d-block d-md-none mt-3">
            <div class="col-12 d-flex">
                <button class="btn btn-dark w-100 p-2" onclick="OpenProductSelector(0, 'not-set')"> <i class="fa-solid fa-plus"></i> Add Product</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <hr class="mt-0">
        <div class="item-area">
            <?php
            if (!empty($CartProducts)) {
                foreach ($CartProducts as $SelectRecord) {
                    $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                    $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                    $selling_price = $SelectRecord['item_price'];
                    $item_quantity = $SelectRecord['quantity'];
                    $item_discount = $SelectRecord['item_discount'];
                    $product_id = $SelectRecord['product_id'];

                    $line_total = ($selling_price - $item_discount) * $item_quantity;
                    $totalItemDiscount = $item_discount * $item_quantity;
            ?>
                    <div class="p-1 bg-light">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 col-md-2">
                                        <h4 class="product-price m-0"><?= $item_quantity ?><br><span class="product-title m-0"><?= $item_unit ?></span></h4>
                                    </div>
                                    <div class="col-8 col-md-6">
                                        <h4 class="product-title"><?= $display_name ?>
                                            <br>Price -<?= $CurrencySelected ?> <?= number_format($selling_price, 2) ?>
                                            <br> <span class="product-title">Discount - LKR <?= number_format($item_discount, 2) ?></span>
                                        </h4>
                                    </div>
                                    <div class="col-8 col-md-3 mt-2 mt-md-0 text-start">
                                        <h4 class="product-price"><?= number_format($line_total, 2) ?></h4>
                                        <span class="product-title text-danger">(<?= number_format($totalItemDiscount, 2) ?>)</span>
                                    </div>
                                    <div class="col-4 col-md-1 mt-2 mt-md-0 text-end"><i class="fa-solid fa-trash text-danger clickable" onclick="OpenRemoval('<?= $product_id ?>', '<?= $invoice_number ?>')"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="p-2 bg-light">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">No Entires</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


        </div>
    </div>
    <div class="bg-light p-3" style="z-index: 2;">
        <div class="row">
            <div class="col-4">
                <span class="mb-0 text-end text-dark px-2" style="font-size: 15px; font-weight:700"><?= count($CartProducts) ?> Item(s)</span>
            </div>
            <div class="col-8 text-end">
                <button class="btn btn-light" onclick="SetDiscount()">
                    <span class="mb-0 text-end text-warning px-2" style="font-size: 20px;">Bill Discount (<?= $discount_rate ?>%)</span>
                </button>
            </div>
        </div>
    </div>

</div>

<?php
?>

<div class="card bg-white shadow-sm mt-2">
    <div class="card-body">
        <div class="bg-light p-2">
            <div class="row">
                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Sub</span>
                    <h6><?= number_format($total, 2) ?></h6>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Discount</span>
                    <h6><?= number_format($discount_amount, 2) ?></h6>
                </div>

                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Total</span>
                    <h6><?= number_format($sub_total, 2) ?></h6>
                </div>

                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Service</span>
                    <h6><?= number_format($taxAmount, 2) ?></h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 text-start" style="display:flex;align-items: center;justify-content: left;">
                    <button class="mt-2 btn service-charge-button <?= ($ServiceChargeStatus == 1) ? "active-charge-button " : "" ?>" id="charge-button"><?= ($ServiceChargeStatus == 1) ? "Charged" : "Not Charged" ?></button>
                </div>
                <div class="col-md-8 mt-2 mt-md-0 text-end">
                    <div class="border-top"></div>
                    <span class="mb-0 px-2 payable-amount">Payable</span>
                    <span class="mb-0 px-2 payable-value" style="font-size: 35px; font-weight:700"><?= number_format($grand_total, 2) ?></span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card shadow-sm mt-2">
    <div class="card-body">
        <div class="bg-light p-2">

            <div class="row">
                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Type</span>
                    <h6><?= $paymentMethod ?></h6>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Due</span>
                    <h6><?= number_format($grand_total, 2) ?></h6>
                </div>

                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Tendered</span>
                    <h6><?= number_format($tendered_amount, 2) ?></h6>
                </div>

                <div class="col-6 col-md-3 text-center">
                    <span class="mb-0 text-end text-secondary px-2">Change</span>
                    <h6><?= number_format($change_amount, 2) ?></h6>
                </div>
            </div>


            <div class="row">
                <div class="col-12 text-end">
                    <button onclick="SetPayment()" class="btn btn-light btn-sm"><i class="fa-solid fa-money-bill btn-icon"></i> Change Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>


<form id="inv_form" method="post">
    <input type="hidden" id="total" name="total" value="<?= $total ?>">
    <input type="hidden" id="invoice_number" name="total" value="<?= $invoice_number ?>">
    <input type="hidden" id="sub_total" name="sub_total" value="<?= $total ?>">
    <input type="hidden" id="tax_rate" name="tax_rate" value="<?= $taxRate ?>">
    <input type="hidden" id="tax_amount" name="tax_amount" value="<?= $taxAmount ?>">
    <input type="hidden" id="discount_rate" name="discount_rate" value="<?= $discount_rate ?>">
    <input type="hidden" id="discount_amount" name="discount_amount" value="<?= $discount_amount ?>">
    <input type="hidden" id="grand_total" name="grand_total" value="<?= $grand_total ?>">
    <input type="hidden" id="tendered_amount" name="tendered_amount" value="<?= $tendered_amount ?>">
    <input type="hidden" id="close_type" name="close_type" value="<?= $close_type ?>">
    <input type="hidden" id="charge_status" name="charge_status" value="<?= $ServiceChargeStatus ?>">
    <input type="hidden" id="location_id" name="location_id" value="<?= $location_id ?>">


</form>


<script>
    document.getElementById('charge-button').addEventListener('click', function() {

        var CustomerID = document.getElementById('customer-id').getAttribute('data-id')
        var TableID = document.getElementById('set-table').getAttribute('data-id')
        var TableName = document.getElementById('set-table').value
        var discount_rate = document.getElementById('discount_rate').value
        const chargeStatusInput = document.getElementById('charge_status');
        var close_type = document.getElementById('close_type').value
        var tendered_amount = document.getElementById('tendered_amount').value
        var invoice_number = document.getElementById('invoice_number').value

        var stewardID = document.getElementById("set-steward").getAttribute("data-id");
        var stewardName = document.getElementById("set-steward").value;
        this.classList.toggle('active-charge-button');
        var ServiceChargeStatus
        if (this.classList.contains('active-charge-button')) {
            this.textContent = 'Service Charge Applied';
            ServiceChargeStatus = 1
        } else {
            this.textContent = 'Service Charge Removed';
            ServiceChargeStatus = 0
        }

        chargeStatusInput.value = ServiceChargeStatus;
        OpenBillContainer(TableName, TableID, ServiceChargeStatus, discount_rate, close_type, tendered_amount, invoice_number, CustomerID, stewardName, stewardID)
    });
</script>

<script>
    // Function to send data
    function sendData() {
        // Object to hold all the data
        var data = {};

        // Collect data for each input field
        var dataFields = [
            'total', 'invoice_number', 'sub_total', 'tax_rate', 'tax_amount',
            'discount_rate', 'discount_amount', 'grand_total', 'tendered_amount',
            'close_type', 'charge_status', 'location_id'
        ];

        dataFields.forEach(function(fieldId) {
            data[fieldId] = document.getElementById(fieldId).value;
        });

        data['cart_items'] = <?= json_encode($CartProducts) ?>

        // Store the data in local storage
        localStorage.setItem("formData", JSON.stringify(data));
    }

    // Function to start sending data every 250 milliseconds
    function startSendingData() {
        sendData(); // Initial call when the DOM is loaded

        // Set up an interval to call sendData every 250 milliseconds
        // setInterval(function() {
        //     sendData();
        // }, 1000);
    }

    // Run startSendingData when the DOM is fully loaded
    $(document).ready(function() {
        startSendingData();
    });
</script>