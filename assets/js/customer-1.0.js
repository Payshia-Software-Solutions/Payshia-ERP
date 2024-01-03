var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }


function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/customer/index.php',
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

function NewCustomer(isActive, updateKey) {
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/customer/create.php',
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
            }
        })
    }

    fetch_data()
}

function SaveCustomer(is_active, UpdateKey) {
    var form = document.getElementById('customer-form')

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('is_active', is_active)
        formData.append('UpdateKey', UpdateKey)

        function fetch_data() {
            $.ajax({
                url: 'assets/content/customer/save.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
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
    } else {
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Oops!', result)
    }
}