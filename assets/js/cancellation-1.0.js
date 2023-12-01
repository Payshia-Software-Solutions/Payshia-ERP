var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function() {
    OpenIndex()
})

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }


function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/administration_module/cancellation/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}


function OpenInvoiceCancellation() {
    document.getElementById('card-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/administration_module/cancellation/invoice-cancellation.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id
            },
            success: function(data) {
                $('#card-index').html(data)
            }
        })
    }
    fetch_data()
}

function RetrieveInvoice() {
    var invoice_number = document.getElementById('invoice_number').value;
    document.getElementById('type-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/administration_module/cancellation/requests/get-invoice.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                invoice_number: invoice_number
            },
            success: function(data) {
                $('#type-index').html(data)
            }
        })
    }
    fetch_data()
}


function CancelInvoice(invoice_number) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/administration_module/cancellation/requests/cancel-invoice.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                invoice_number: invoice_number
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()
                } else {
                    var result = response.message
                    OpenAlert('error', 'Oops.. Something Wrong!', result)
                }
                hideOverlay()
            }
        })
    }
    fetch_data()
}