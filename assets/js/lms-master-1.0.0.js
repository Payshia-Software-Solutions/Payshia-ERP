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
            url: 'assets/content/lms-management/master/index.php',
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

function GetGameResults(selectedCourseCode, selectedStudent) {

    function fetch_data() {
        document.getElementById('gameResults').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/lms-management/master/game-results.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                selectedCourseCode: selectedCourseCode,
                selectedStudent: selectedStudent
            },
            success: function(data) {
                $('#gameResults').html(data)
            }
        })
    }
    fetch_data()
}

function GetSearchPopUp() {
    showOverlay()
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/master/search-student.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#loading-popup').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function GetStudentInformation(studentNumber) {

    showOverlay()
    ClosePopUP()

    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/lms-management/master/student-information.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentNumber: studentNumber
            },
            success: function(data) {
                $('#index-content').html(data)
                hideOverlay()

            }
        })
    }
    fetch_data()
}