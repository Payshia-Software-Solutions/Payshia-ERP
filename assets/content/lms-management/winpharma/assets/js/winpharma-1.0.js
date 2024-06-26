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
            url: './assets/content/lms-management/winpharma/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id
            },
            success: function(data) {
                $('#index-content').html(data)
                GetCounters()
            }
        })
    }
    fetch_data()
}

function OpenSubmission(submissionId = 0, requestStatus) {
    var userTheme = $('#userTheme').val()
    OpenPopupRight()
    $('#loading-popup-right').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/submission-view.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                submissionId: submissionId,
                userTheme: userTheme,
                requestStatus: requestStatus
            },
            success: function(data) {
                $('#loading-popup-right').html(data)
            }
        })
    }
    fetch_data()
}

function OpenCommonReasons() {
    var userTheme = $('#userTheme').val()
    OpenPopupRight()
    $('#loading-popup-right').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/common-reason-view.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                userTheme: userTheme,
            },
            success: function(data) {
                $('#loading-popup-right').html(data)
            }
        })
    }
    fetch_data()
}

function NewReason(reasonId = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/new-reason.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                reasonId: reasonId,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function saveReason(reasonId) {
    var form = document.getElementById('reason-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('reasonId', reasonId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/lms-management/winpharma/methods/saveReason.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        OpenCommonReasons()
                        ClosePopUP(0)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        fetch_data()
    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }
}

function DeleteReason(reasonId) {
    function fetch_data() {
        showOverlay()
        $.ajax({
            url: './assets/content/lms-management/winpharma/methods/deleteReason.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                reasonId: reasonId
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenCommonReasons()
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
        confirmButtonText: "Yes, Delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch_data()
        }
    });
}

function GetCounters() {
    function fetch_data() {
        document.getElementById('total-counters').innerHTML = InnerLoader
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/total-counters.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
            },
            success: function(data) {
                $('#total-counters').html(data)
            }
        })
    }
    fetch_data()
}

function GetWinpharmaSubmissions(defaultCourse, requestStatus) {
    function fetch_data() {
        document.getElementById('submission-list').innerHTML = InnerLoader
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/submission-list.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                defaultCourse: defaultCourse,
                requestStatus: requestStatus
            },
            success: function(data) {
                $('#submission-list').html(data)
            }
        })
    }
    fetch_data()
}

function ViewResource(resourceId) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/lms-management/winpharma/views/view-resource.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                resourceId: resourceId,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveGrade(submissionId, defaultCourse, requestStatus) {
    var form = document.getElementById('grade-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('submissionId', submissionId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/lms-management/winpharma/methods/saveGrade.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        GetWinpharmaSubmissions(defaultCourse, requestStatus)
                        GetCounters()
                        OpenAlert('success', 'Done!', result)
                        ClosePopUPRight(0)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        fetch_data()
    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }
}