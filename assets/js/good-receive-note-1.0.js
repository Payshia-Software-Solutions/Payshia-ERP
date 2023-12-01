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
            url: 'assets/content/transaction_module/good_receive_note/index.php',
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

function OpenGRN(po_number) {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/good-receive-note.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                po_number: po_number
            },
            success: function(data) {
                $('#index-content').html(data)
                getItemTable(po_number, 0)
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

function OpenGRNPrint(grn_number) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/good-receive-note?grn_number=' + encodeURIComponent(grn_number), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function getItemTable(po_number, updateStatus) {
    $('#order-table').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/order-table.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                po_number: po_number,
                updateStatus: updateStatus
            },
            success: function(data) {
                $('#order-table').html(data)
            }
        })
    }
    fetch_data()
}

function RemoveFromOrder(ProductID, po_number) {
    $('#order-table').html("Please Wait..")

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/request/remove-from-order.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                po_number: po_number,
                ProductID: ProductID
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    getItemTable(po_number, 1)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function UpdateGRNQty(po_number, receivedQty, ProductID) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/request/update-qty.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                po_number: po_number,
                receivedQty: receivedQty,
                ProductID: ProductID
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {

                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
                getItemTable(po_number, 1)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function ProcessGRN(po_number, grn_number, grn_status) {
    var subTotal = $('#subTotal').val()
    var taxAmount = $('#taxAmount').val()
    var grandTotal = $('#grandTotal').val()
    var remarks = $('#remarks').val()
    var itemCount = $('#itemCount').val()

    var form = document.getElementById('action-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('subTotal', subTotal)
    formData.append('taxAmount', taxAmount)
    formData.append('grandTotal', grandTotal)
    formData.append('po_number', po_number)
    formData.append('grn_number', grn_number)
    formData.append('remarks', remarks)
    formData.append('grn_status', grn_status)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/request/process_grn.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var grn_number = response.grn_number
                    OpenIndex()
                    OpenGRNPrint(grn_number)
                    OpenAlert('success', 'Done!', result)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
                hideOverlay()
            }
        })
    }
    if (itemCount > 0) {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Nothing to Process')
    }

}

function GetConfirmation(po_number, grn_number) {
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/good_receive_note/request/pop-up.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                po_number: po_number,
                grn_number: grn_number
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()

            }
        })
    }
    fetch_data()
}