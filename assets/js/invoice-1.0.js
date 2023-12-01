var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

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
                    var stockBalance = response.stockBalance

                    $("#order_Unit").val(unit_name)
                    $("#new_rate").val(cost_price)
                    $("#stockBalance").val(stockBalance)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }

    if (location_id != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please Select Location')
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

function getProductSelector(SupplierID) {
    $('#product-selector').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/invoice/product-selector.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                SupplierID: SupplierID
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
    var locationID = document.getElementById("location_id").value;
    var subTotal = document.getElementById("sub_total_hidden").value;
    var discountPercentage = document.getElementById("discount_percentage_hidden").value;
    // Check if there is at least one row in the table
    if ($("#order-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at least one row to the table.');
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
                unit: cells[2].innerHTML,
                stockBalance: cells[3].innerHTML,
                quantity: cells[4].innerHTML,
                rate: cells[5].innerHTML,
                amount: cells[6].innerHTML
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
                locationID: locationID,
                InvoiceStatus: InvoiceStatus,
                subTotal: subTotal,
                discountPercentage: discountPercentage,
                InvoiceNumber: InvoiceNumber
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var grn_number = response.grn_number
                    OpenIndex()
                    OpenAlert('success', 'Done!', result)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }
    fetch_data()



}

function OpenPOPrint(po_number) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/purchase-order?po_number=' + encodeURIComponent(po_number), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}