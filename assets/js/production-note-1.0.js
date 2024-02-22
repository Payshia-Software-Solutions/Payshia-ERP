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
            url: 'assets/content/transaction_module/production/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#index-content').html(data)

            }
        })
    }
    fetch_data()
}


function NewProductionNote() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/production/new-production-note.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#index-content').html(data)

            }
        })
    }
    fetch_data()
}


function GetProductInfo(ProductID) {
    var location_id = document.getElementById('location_id').value

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/production/product-info.php',
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
                    $("#new_rate").val(cost_price)
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

    if (location_id != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please Select Location & Customer')
    }

}


function ProceedNote(pnNumber, poStatus) {
    if ($("#order-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at Products/Services to the Invoice.');
        return;
    }
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/production/confirmation.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                pnNumber: pnNumber,
                poStatus: poStatus
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
            }
        })
    }

    if (location_id != "") {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please select Location');
    }

}


function ProcessProduction(pnNumber, poStatus) {
    var location_id = document.getElementById("location_id").value;
    var sub_total_hidden = document.getElementById("sub_total_hidden").value;
    var create_date = document.getElementById("create_date").value;
    var remarks = document.getElementById("remarks").value;

    // Check if there is at least one row in the table
    if ($("#order-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at Products/Services to the Production.');
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
            url: 'assets/content/transaction_module/production/request/process-production.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                tableData: tableData,
                location_id: location_id,
                poStatus: poStatus,
                pnNumber: pnNumber,
                sub_total_hidden: sub_total_hidden,
                create_date: create_date,
                remarks: remarks
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var pnNumber = response.pnNumber
                    PrintPN(pnNumber)
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

function PrintPN(pnNumber) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/production-note?pnNumber=' + encodeURIComponent(pnNumber), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}