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
            url: 'assets/content/lms-management/user-management/index.php',
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

function OpenUserInfo(refId) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/user-management/user-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                refId: refId
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function UpdateUserStatus(refId, activeStatus) {
    var studentBatch = $('#studentBatch').val()
    var createUserLevel = $('#userLevel').val()

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/lms-management/user-management/user-activation.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location,
                studentBatch: studentBatch,
                refId: refId,
                activeStatus: activeStatus,
                createUserLevel: createUserLevel
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var username = response.username
                    OpenAlert('success', 'Done!', result + '<br> Index Number - ' + username)
                    OpenIndex()
                    ClosePopUP()
                } else {
                    var result = response.message
                    OpenAlert('error', 'Oops..!', result)
                }
                hideOverlay()

            }
        })
    }
    if (studentBatch != "" && userLevel != "" || activeStatus == "Rejected") {
        fetch_data()
    } else {
        var result = "Please Select Batch & User Level!"
        OpenAlert('info', 'Invalid!', result)

    }
}

function OpenEditUserInfo(refId) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/user-management/edit-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                refId: refId
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function EditUserData(refId) {
    var form = document.getElementById('submit-form')

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('refId', refId)

        function fetch_data() {
            $.ajax({
                url: 'assets/content/lms-management/user-management/update-user-info.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        OpenUserInfo(refId)
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
        form.reportValidity();
        result = 'Please Filled out All  Fields.'
        OpenAlert('error', 'Oops!', result)
    }
}

function OpenPendingUser() {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/user-management/export-not-approved-list.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}