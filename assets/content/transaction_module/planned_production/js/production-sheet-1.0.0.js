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
            url: 'assets/content/transaction_module/planned_production/index.php',
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

function NewProductionSheet(isActive = 1, updateKey = 0) {
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/planned_production/new-production-sheet.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                default_location: default_location,
                updateKey: updateKey,
                isActive: isActive
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
                hideOverlay()
            }
        })

    }

    fetch_data()
}


function GetReceiptContent(productID, targetQty) {

    function fetch_data() {
        document.getElementById('nav-materials').innerHTML = InnerLoader
        showOverlay()
        $.ajax({
            url: 'assets/content/transaction_module/planned_production/get-recipe-content.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location,
                productID: productID,
                targetQty: targetQty
            },
            success: function(data) {
                $('#nav-materials').html(data)
                hideOverlay()
            }
        })
    }

    if (targetQty != "" || targetQty > 0) {
        fetch_data()
    } else {
        OpenAlert('error', 'Error!', 'Please Add Quantity')
    }

}

function SaveProduction() {
    var form = document.getElementById('production-form')

    var productionCost = parseFloat($('#ProductionTotalCost').html().replace(/,/g, ''));

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('default_location', default_location)
        formData.append('productionCost', productionCost)
            // Get table data
        var tableRows = document.querySelectorAll('.material-table tbody tr');
        var tableData = [];
        tableRows.forEach(function(row) {
            var rowData = {
                'product_id': row.cells[1].innerText,
                'product_name': row.cells[2].innerText,
                'quantity': row.cells[3].querySelector('input[type="number"]').value,
                'unit': row.cells[4].innerText,
                'unit_price': row.cells[5].innerText.split('/')[0].replace(',', ''), // Assuming price format like "123.45/Unit"
                'amount': row.cells[6].innerText.replace(',', '') // Remove commas from amount
            };
            tableData.push(rowData);
        });
        formData.append('tableData', JSON.stringify(tableData));

        function fetch_data() {
            $.ajax({
                url: 'assets/content/transaction_module/planned_production/save-batch-sheet.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        var batchNumber = response.batchNumber
                        PrintBatchSheet(batchNumber)
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
    } else {
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Oops!', result)
        form.reportValidity()
    }
}

function PrintBatchSheet(batchNumber) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/batch-production-note?batchNumber=' + encodeURIComponent(batchNumber), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}