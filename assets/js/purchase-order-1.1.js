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
            url: 'assets/content/transaction_module/purchase_order/index.php',
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

function NewPurchaseOrder() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/new-purchase-order.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#index-content').html(data)
                getOrderTable()
                getProductSelector(0)
            }
        })
    }
    fetch_data()
}

function AddToPO() {
    var new_quantity = document.getElementById('new_quantity').value
    var SupplierID = document.getElementById('supplier_id').value
    var form = document.getElementById('action-form')
    if (form.checkValidity()) {
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: 'assets/content/transaction_module/purchase_order/request/add-to-po.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        getProductSelector(SupplierID)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    getOrderTable()
                    hideOverlay()
                }
            })
        }
        if (new_quantity > 0) {
            showOverlay()
            fetch_data()
        } else {
            OpenAlert('error', 'Error!', 'Qty must be greater than Zero(0)!')
        }

    } else {
        OpenAlert('error', 'Error!', 'Please select fields!')
    }
}

function getOrderTable() {
    $('#order-table').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/order-table.php',
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
    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/product-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ProductID: ProductID
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var unit_name = response.unit_name
                    var cost_price = response.cost_price

                    $("#order_Unit").val(unit_name)
                    $("#new_rate").val(cost_price)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }
    fetch_data()
}

function getProductSelector(SupplierID) {
    $('#product-selector').html("Please Wait..")

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/product-selector.php',
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

function GetTotals() {
    $('#order-totals').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/order-totals.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#order-totals').html(data)
            }
        })
    }
    fetch_data()
}

function RemoveFromOrder(RecordID) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/remove-from-order.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                RecordID: RecordID
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    getOrderTable()
                    OpenAlert('success', 'Done!', result)
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

function FilterProductList(SupplierID) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/request/product-list-by-supplier.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                SupplierID: SupplierID
            },
            success: function(data) {
                $('#select_product').html(data)
                hideOverlay()

            }
        })
    }
    fetch_data()
}



function ProcessPurchaseOrder(po_number, po_status) {
    var order_value = $('#order_value').val()
    var remarks = $('#remarks').val()
    var no_of_items = $('#no_of_items').val()

    var form = document.getElementById('action-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('order_value', order_value)
    formData.append('po_number', po_number)
    formData.append('remarks', remarks)
    formData.append('po_status', po_status)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/request/process_purchase_order.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var po_number = response.po_number
                    OpenIndex()
                    OpenPOPrint(po_number)
                    OpenAlert('success', 'Done!', result)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
                getOrderTable()
                hideOverlay()
            }
        })
    }
    if (no_of_items != 0) {
        fetch_data()
    } else {
        var result = "No Products to Order! Please add Products";
        OpenAlert('error', 'Error!', result)
    }

}

function OpenPOPrint(po_number) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/purchase-order?po_number=' + encodeURIComponent(po_number), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}