<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$LoggedUser = $_POST['LoggedUser'];
$Products = GetProducts($link);
$Units = GetUnit($link);
$CurrencySelected = "LKR";
?>


<div class="row">
    <div class="col-md-12" id="display-container">
        <div class="card p-1 mb-1" style="height: calc(100vh - 140px) !important; overflow-y:auto">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-2">
                        <h4 class="product-price mb-0">Qty</h4>
                    </div>
                    <div class="col-4">
                        <h4 class="product-price mb-0">Product</h4>
                    </div>
                    <div class="col-3 text-end">
                        <h4 class="product-price mb-0">Unit Price</h4>
                    </div>
                    <div class="col-3 text-end">
                        <h4 class="product-price mb-0">Total</h4>
                    </div>
                </div>

                <div class="row" id="customer-show">
                    <div class="col-12 text-center p-4">
                        <h2 id="date-time"></h2>
                        <img class="mt-3" src="./assets/images/aradhana-logo.png" style="width: 50%;">
                    </div>
                </div>
                <div class="border-top mb-1"></div>
                <div id="item-list">

                </div>
                <hr>
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-center">Sub Total Amount</p>
                                <h3 class="text-center" id="total-amount"></h3>
                            </div>
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-center">Discount Amount</p>
                                <h3 class="text-center" id="discount-amount"></h3>
                            </div>
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-center">Service Charge</p>
                                <h3 class="text-center" id="charge-amount"></h3>
                            </div>
                            <div class="col-6 col-md-3">
                                <p class="mb-0 text-center">Payable Amount</p>
                                <h3 class="text-center" id="payable-amount"></h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    // Function to start sending data every 250 milliseconds
    function fetchData() {
        var storedData = localStorage.getItem("formData");

        if (storedData !== null) {
            var formData = JSON.parse(storedData);
            // Formatting options for Sri Lankan Rupees
            var currencyOptions = {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            };

            // Format and set values
            document.getElementById('total-amount').innerHTML = parseFloat(formData.total).toLocaleString('en-US', currencyOptions);
            document.getElementById('discount-amount').innerHTML = parseFloat(formData.discount_amount).toLocaleString('en-US', currencyOptions);
            document.getElementById('charge-amount').innerHTML = parseFloat(formData.tax_amount).toLocaleString('en-US', currencyOptions);
            document.getElementById('payable-amount').innerHTML = parseFloat(formData.grand_total).toLocaleString('en-US', currencyOptions);



            jsonData = formData.cart_items

            // Function to create HTML content
            function generateItemListHtml(data) {
                var html = "";


                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var item = data[key];
                        html += `
                    <div class="card p-1">
                        <div class="row">
                            <div class="col-2">
                                <h4 class="product-price mb-0">${parseFloat(item.quantity).toFixed(3)}</h4>
                            </div>
                            <div class="col-4">
                                <h4 class="product-price mb-0">${item.display_name}</h4>
                            </div>
                            <div class="col-3 text-end">
                                <h4 class="product-price mb-0">${parseFloat(item.item_price).toFixed(2)}</h4>
                            </div>
                            <div class="col-3 text-end">
                                <h4 class="product-price mb-0">${parseFloat(item.quantity * item.item_price).toFixed(2)}</h4>
                            </div>
                        </div>
                    </div>
                `;
                    }
                }
                return html;
            }

            // Replace the content of the "item-list" div with new HTML
            $('#item-list').html(generateItemListHtml(jsonData));



        } else {
            console.log("No data stored yet.");
        }

        // Set up an interval to call sendData every 250 milliseconds
        setInterval(function() {
            if (Object.keys(jsonData).length > 0) {
                document.getElementById('customer-show').style.display = 'none'
            } else {
                document.getElementById('customer-show').style.display = 'block'
            }
            updateDateTime();
            fetchData();
        }, 1000);


    }

    // Run startSendingData when the DOM is fully loaded
    $(document).ready(function() {
        fetchData();
    });

    // Function to update the date and time element
    function updateDateTime() {
        const dateTimeElement = document.getElementById('date-time');
        if (dateTimeElement) {

            const currentDate = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            };
            const formattedDate = currentDate.toLocaleDateString('en-US', options);

            dateTimeElement.textContent = formattedDate;

        }
    }

    // Call the function to update the date and time immediately
    updateDateTime();
</script>