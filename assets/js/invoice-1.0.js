var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value


$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#index-content').html(data)

            }
        })
    }
    fetch_data()
}

function NewInvoice() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/new-invoice.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#index-content').html(data)
                    // getOrderTable()
                GetCustomerList()
                getProductSelector()
            }
        })
    }
    fetch_data()
}



function getOrderTable() {
    $('#order-table').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/order-table.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#order-table').html(data)
                GetTotals()
            }
        })
    }
    fetch_data()
}

function GetProductInfo(ProductID) {
    var location_id = document.getElementById('location_id').value
    var customer_select = document.getElementById('customer_select').value
    var selectedProduct = document.getElementById('select_product');

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/product-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ProductID: ProductID,
                location_id: location_id
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var unit_name = response.unit_name
                    var cost_price = response.cost_price
                    var selling_price = response.selling_price
                    var minimum_price = response.minimum_price
                    var wholesale_price = response.wholesale_price
                    var price_2 = response.price_2
                    var stockBalance = response.stockBalance

                    $("#order_Unit").val(unit_name)
                    $("#new_rate").val(selling_price)
                    $("#stockBalance").val(stockBalance)

                    document.getElementById('new_quantity').focus()
                    document.getElementById('new_quantity').select()

                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }

    if (location_id != "" && customer_select != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please Select Location & Customer')
        selectedProduct.value = ''
        getProductSelector()
    }

}

function GetCustomerList() {
    var location_id = document.getElementById('location_id').value

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/request/customer-select.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                location_id: location_id
            },
            success: function(data) {
                $('#customer_select').html(data)
            }
        })
    }
    fetch_data()
}

function getProductSelector() {
    $('#product-selector').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/product-selector.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#product-selector').html(data)
            }
        })
    }
    fetch_data()
}




function ProcessInvoice(InvoiceNumber, InvoiceStatus) {
    var CustomerID = document.getElementById("customer_select").value;
    var location_id = document.getElementById("location_id").value;
    var sub_total_hidden = document.getElementById("sub_total_hidden").value;
    var tax_rate_hidden = document.getElementById("tax_rate_hidden").value;
    var tax_amount_hidden = document.getElementById("tax_amount_hidden").value;
    var discount_percentage_hidden = document.getElementById("discount_percentage_hidden").value;
    var grand_total_hidden = document.getElementById("grand_total_hidden").value;
    var discount_value_hidden = document.getElementById("discount_value_hidden").value;
    var payment_type = document.getElementById("payment_type").value;
    var invoice_type = document.getElementById("invoice_type").value;
    var payment_amount = document.getElementById('payment_amount').value

    // Check if there is at least one row in the table
    if ($("#order-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at Products/Services to the Invoice.');
        return;
    }

    // Create an array to hold table data
    var tableData = [];

    // Iterate through table rows and collect data
    var tableRows = document.getElementById('order-table').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (var i = 0; i < tableRows.length; i++) {
        var cells = tableRows[i].getElementsByTagName('td');

        // Ensure that the row has at least 7 cells (adjust based on your HTML structure)
        if (cells.length >= 7) {
            var rowData = {
                productID: cells[0].innerHTML,
                productName: cells[1].innerHTML,
                quantity: cells[2].innerHTML,
                unit: cells[3].innerHTML,
                rate: cells[4].innerHTML,
                amount: cells[5].innerHTML
            };
            tableData.push(rowData);
        }
    }


    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/request/process-invoice.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                CustomerID: CustomerID,
                tableData: tableData,
                location_id: location_id,
                InvoiceStatus: InvoiceStatus,
                InvoiceNumber: InvoiceNumber,
                sub_total_hidden: sub_total_hidden,
                discount_percentage_hidden: discount_percentage_hidden,
                tax_rate_hidden: tax_rate_hidden,
                tax_amount_hidden: tax_amount_hidden,
                discount_value_hidden: discount_value_hidden,
                payment_type: payment_type,
                invoice_type: invoice_type,
                grand_total_hidden: grand_total_hidden,
                payment_amount: payment_amount
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var invoice_number = response.invoice_number
                    OpenIndex()
                    OpenAlert('success', 'Done!', result)
                    PrintInvoice(invoice_number)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }
    if (payment_type != "" && payment_amount != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please Select Payment Type!')
    }




}

function PrintInvoice(invoice_number) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/invoice?invoiceNumber=' + encodeURIComponent(invoice_number), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function CreateReceipt(invoiceNumber, invoiceStatus) {
    if ($("#order-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at Products/Services to the Invoice.');
        return;
    }
    var grandTotal = document.getElementById('grand_total_hidden').value
    var customerId = document.getElementById('customer_select').value

    var location_id = document.getElementById('location_id').value
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/new-receipt.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                invoiceNumber: invoiceNumber,
                invoiceStatus: invoiceStatus,
                grandTotal: grandTotal,
                customerId: customerId
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
            }
        })
    }

    if (customerId != "" && location_id != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please select Location & Customer');
    }

}

function validateMinAmount(enteredValue, minPayment, dueAmount) {
    var tenderValue = parseFloat(enteredValue);
    var minimumValue = parseFloat(minPayment);
    var duePayment = parseFloat(dueAmount);
    if (tenderValue < minimumValue) {
        OpenAlert('error', 'Error!', 'Minimum Payment Should be ' + formatCurrencyLKR(minimumValue));
        $('#payment_amount').val(minimumValue)
    } else if (tenderValue > duePayment) {
        OpenAlert('error', 'Error!', 'Cannot exceed Invoice Amount ' + formatCurrencyLKR(duePayment));
        $('#payment_amount').val(duePayment)
    }
}

// Function to format a number as currency
function formatCurrencyLKR(value) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'LKR'
    }).format(value);
}