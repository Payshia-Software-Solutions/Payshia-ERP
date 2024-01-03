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
            url: 'assets/content/lms-management/reports/index.php',
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

function OpenStudentReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/reports/student-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetStudentReport()
            }
        })
    }
    fetch_data()
}

function GetStudentReport() {
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
            url: 'assets/content/lms-management/reports/student-report/student-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)

            }
        })
        hideOverlay()
    }
    fetch_data()
}

function PrintAddressReport() {

    var studentBatch = document.getElementById('studentBatch').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/lms-reports/address-list?default_location=' + encodeURIComponent(default_location) + '&studentBatch=' + encodeURIComponent(studentBatch), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}


function PrintStudentReport() {

    var studentBatch = document.getElementById('studentBatch').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/lms-reports/student-report?default_location=' + encodeURIComponent(default_location) + '&studentBatch=' + encodeURIComponent(studentBatch), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}