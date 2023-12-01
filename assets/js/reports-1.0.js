var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = 1;

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/report-home/index.php',
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

function DayEndSaleReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/day-end-sale/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetDayEndSaleReport()
            }
        })
    }
    fetch_data()
}

function GetDayEndSaleReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/day-end-sale/day-end-sale-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintDayEndSale() {
    var queryDate = document.getElementById('date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/day-end-sale-report?location_id=' + encodeURIComponent(location_id) + '&date_input=' + encodeURIComponent(queryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}


// Sale Summary Report
function SaleSummaryReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/sale-summary/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetDaySaleSummaryReport()
            }
        })
    }
    fetch_data()

}

function GetDaySaleSummaryReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/sale-summary/sale-summary-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintDaySaleSummaryReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/sale-summary-report?location_id=' + encodeURIComponent(location_id) + '&fromQueryDate=' + encodeURIComponent(fromQueryDate) + '&toQueryDate=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function StockBalanceReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/stock-balance-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetStockBalanceReport()
            }
        })
    }
    fetch_data()

}

function GetStockBalanceReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/stock-balance-report/stock-balance-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintStockBalanceReport() {
    var location_id = document.getElementById('location_id').value
    var section_id = document.getElementById('section_id').value
    var department_id = document.getElementById('department_id').value
    var category_id = document.getElementById('category_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/stock-balance-report?location_id=' + encodeURIComponent(location_id) + '&section_id=' + encodeURIComponent(section_id) + '&category_id=' + encodeURIComponent(category_id) + '&department_id=' + encodeURIComponent(department_id), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function BinCardReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/bin-card/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetBinCardReport()
            }
        })
    }
    fetch_data()

}

function GetBinCardReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/bin-card/bin-card-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintBinCardReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
    var select_product = document.getElementById('select_product').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/bin-card?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate) + '&select_product=' + encodeURIComponent(select_product), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function ItemWiseSale() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/item-wise-sale-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetItemWiseSaleReport()
            }
        })
    }
    fetch_data()
}

function GetItemWiseSaleReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/item-wise-sale-report/item-wise-sale-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintItemWiseSaleReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/item-wise-sale?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function ReceiptReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/receipt-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetReceiptReport()
            }
        })
    }
    fetch_data()
}


function GetReceiptReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/receipt-report/receipt-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintReceiptReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/receipt-report?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}