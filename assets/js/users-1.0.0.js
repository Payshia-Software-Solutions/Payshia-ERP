var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function() {
    OpenIndex()
})


function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/student/index.php',
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

function CreateStudent(is_active, UpdateKey) {
    showOverlay()
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/student/create.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                company_id: company_id,
                is_active: is_active,
                UpdateKey: UpdateKey,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#index-content').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function SaveStudent(is_active, UpdateKey) {
    var form = document.getElementById('add-form')

    if (form.checkValidity()) {
        var formData = new FormData(form)
        formData.append('updated_by', LoggedUser)
        formData.append('company_id', company_id)
        formData.append('is_active', is_active)
        formData.append('updateKey', UpdateKey)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: 'assets/content/student/save.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', 'Updated Successfully')
                        hideOverlay()
                        OpenIndex()
                    } else {
                        var result = response.message
                        var extension_error = response.extension_error
                        var file_size = response.file_size
                        var img_upload = response.img_upload
                        OpenAlert('error', 'Oops.. Something Wrong!', result + '\n' + img_upload + '\n' + extension_error + '\n' + file_size)
                    }
                    console.log(result)
                }
            })
        }

        if (validatePassword() || UpdateKey != 0) {
            fetch_data()
        } else {
            OpenAlert('error', 'Password Error!', 'Password must be at least 6 characters long. or Passwords do not match.')
        }
    } else {
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Oops!', result)
    }
}

function OpenAlert(status, Title, Text) {
    Swal.fire({
        icon: status,
        title: Title,
        text: Text,
        html: Text
    })
}

function validatePassword() {
    const password = document.getElementById('password').value
    const c_password = document.getElementById('c_password').value
    var Error
        // Check password length and matching passwords
    if (password.length < 6) {
        Error = 'Password must be at least 6 characters long.'
        return false
    }

    if (password !== c_password) {
        Error = 'Passwords do not match.'
        return false
    }

    return true
}