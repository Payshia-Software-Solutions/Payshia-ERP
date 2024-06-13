var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex(studentBatch = 0, orderType = 0) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/lms-management/orders/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch,
                orderType: orderType
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}

function OpenOrder(ref_id, studentBatch, orderType) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/orders/order-detail.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ref_id: ref_id,
                studentBatch: studentBatch,
                orderType: orderType
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function RemoveOrder(ref_id, studentBatch, orderType) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/orders/remove-order.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ref_id: ref_id
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenIndex(studentBatch, orderType)
            }
        })
    }
    fetch_data()
}

function PrintShippingLabel(ref_id) {

    var trackingNumber = $('#trackingNumber').val()
    var codAmount = $('#codAmount').val()
    var packageWeight = parseFloat($('#packageWeight').val())

    if (trackingNumber != "" && codAmount != "" && packageWeight > 0) {
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
        var printWindow = window.open('report-viewer/shipping-label?ref_id=' + encodeURIComponent(ref_id), '_blank');

        // Focus on the new tab
        if (printWindow) {
            printWindow.focus();
        }
    } else {
        var result = "COD Amount, Package Weight & Tracking Number are required!"
        OpenAlert('info', 'Invalid Input!', result)

    }

}

function UpdateStatusOrder(refId, orderStatus, studentBatch, orderType) {
    var trackingNumber = $('#trackingNumber').val()
    var codAmount = $('#codAmount').val()
    var packageWeight = parseFloat($('#packageWeight').val())

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/lms-management/orders/update-order-status.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                refId: refId,
                orderStatus: orderStatus,
                trackingNumber: trackingNumber,
                codAmount: codAmount,
                packageWeight: packageWeight,
                default_location: default_location
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex(studentBatch, orderType)
                    OpenOrder(refId, studentBatch, orderType)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Oops.. Something Wrong!', result)
                }
                hideOverlay()

            }
        })
    }
    if (trackingNumber != "" && codAmount != "" && packageWeight > 0) {
        fetch_data()
    } else {
        var result = "COD Amount, Package Weight & Tracking Number are required!"
        OpenAlert('info', 'Invalid Input!', result)

    }
}

function ProductLinkERP(refCode) {
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/orders/product-link.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                refCode: refCode
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
            }
        })
    }
    fetch_data()
}

function GetUploadExcel(studentBatch = 0, orderType = 0) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/orders/get-upload-excel.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch,
                orderType: orderType
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function GetUploadExcelNew(studentBatch = 0, orderType = 0) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/orders/get-upload-excel-new.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch,
                orderType: orderType
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveERPLink(refCode, isActive) {
    var erpProductId = $("#select_product").val();

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/lms-management/orders/save-product-link.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                refCode: refCode,
                erpProductId: erpProductId,
                isActive: isActive
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()
                    ClosePopUP()
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