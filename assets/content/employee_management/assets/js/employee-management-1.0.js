var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex(studentBatch = 0) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: './assets/content/employee_management/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#index-content').html(data)
                GetMailBox()
            }
        })
    }
    fetch_data()
}

function AddNewEmployee(employeeId = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopupRight()
    $('#loading-popup-right').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/employee_management/view/new-employee.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                employeeId: employeeId,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup-right').html(data)
            }
        })
    }
    fetch_data()
}


function OpenMigrations() {
    function fetch_data() {
        $.ajax({
            url: './assets/content/employee_management/migration/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()
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

function SaveEmployee(employeeId = 0) {
    var form = document.getElementById('employee-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('created_by', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('employee_id', employeeId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/employee_management/methods/saveEmployee.php',
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
                        ClosePopUPRight(0)
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

function DisableEmployee(employee_id, is_active = 1) {
    function fetch_data() {
        showOverlay()
        $.ajax({
            url: './assets/content/employee_management/methods/changeStatus.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                employee_id: employee_id,
                is_active: is_active
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()
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
        confirmButtonText: "Yes, Disable it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch_data()
        }
    });
}


function AddNewPosition(positionId = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/employee_management/view/new-position.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                positionId: positionId,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SavePosition(positionId = 0) {
    var form = document.getElementById('position-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('created_by', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('positionId', positionId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/employee_management/methods/savePosition.php',
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

function AddNewDepartment(department_id = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/employee_management/view/new-department.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                department_id: department_id,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveDepartment(department_id = 0) {
    var form = document.getElementById('department-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('created_by', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('department_id', department_id)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/employee_management/methods/saveDepartment.php',
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

function AddNewWorkLocation(work_location_id = 0) {
    var userTheme = $('#userTheme').val()
    OpenPopup()
    $('#loading-popup').html(InnerLoader)

    function fetch_data() {
        $.ajax({
            url: './assets/content/employee_management/view/new-work-location.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                work_location_id: work_location_id,
                userTheme: userTheme
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveWorkLocation(work_location_id = 0) {
    var form = document.getElementById('work-location-form')

    if (form.checkValidity()) {

        var formData = new FormData(form)
        formData.append('created_by', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('work_location_id', work_location_id)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/employee_management/methods/saveWorkLocation.php',
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