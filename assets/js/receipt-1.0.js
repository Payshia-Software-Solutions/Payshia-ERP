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
            url: 'assets/content/transaction_module/receipt/index.php',
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

function CreateReceipt(invoiceNumber, invoiceStatus, payableAmount, customerId, location_id) {

    var grandTotal = payableAmount
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/receipt/new-receipt.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                invoiceNumber: invoiceNumber,
                invoiceStatus: invoiceStatus,
                grandTotal: grandTotal,
                customerId: customerId,
                location_id: location_id
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
            }
        })
    }

    fetch_data()
}

function ProcessReceipt(invoiceNumber, customerId, location_id) {

    var paymentMethod = document.getElementById('payment_type').value
    var payment_amount = document.getElementById('payment_amount').value


    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/receipt/process-receipt.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                invoiceNumber: invoiceNumber,
                customerId: customerId,
                location_id: location_id,
                paymentMethod: paymentMethod,
                payment_amount: payment_amount
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var rec_number = response.rec_number
                    OpenIndex()
                    OpenAlert('success', 'Done!', result)
                    PrintReceipt(rec_number)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }

                hideOverlay()

            }
        })
    }

    if (paymentMethod != -1 && payment_amount > 0) {
        fetch_data()
    }
}


function PrintReceipt(rec_number) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/receipt?rec_number=' + encodeURIComponent(rec_number), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}