var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: './assets/content/lms-management/pharma-hunter/index.php',
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

function OpenMedicine(medicineId) {
    var userTheme = $('#userTheme').val()
    OpenPopupRight()
    $('#loading-popup-right').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/pharma-hunter/views/item-view.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                userTheme: userTheme,
                medicineId: medicineId
            },
            success: function(data) {
                $('#loading-popup-right').html(data)
            }
        })
    }
    fetch_data()
}

function CreateMedicine(medicineId = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/pharma-hunter/views/new-item.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                userTheme: userTheme,
                medicineId: medicineId
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveMedicine(medicineId = 0) {
    var form = document.getElementById('medicine-form')
    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('created_by', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('medicineId', medicineId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/lms-management/pharma-hunter/controllers/save-medicine.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        ClosePopUP(0)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Save it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch_data()
            }
        });
    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }
}