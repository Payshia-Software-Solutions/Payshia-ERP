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
            url: 'assets/content/change-privileges/index.php',
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

function LoadUserPrivilege(userName, activeSection) {
    document.getElementById('side-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/change-privileges/user-privileges.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                userName: userName,
                activeSection: activeSection
            },
            success: function(data) {
                $('#side-content').html(data)
            }
        })
    }
    fetch_data()
}

function UpdatePrivilege(userName, pageID, accessMode, activeSection) {
    function fetch_data() {
        $.ajax({
            url: 'assets/content/change-privileges/update-privileges.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                userName: userName,
                pageID: pageID,
                accessMode: accessMode
            },
            success: function(data) {
                LoadUserPrivilege(userName, activeSection)
            }
        })
    }
    fetch_data()
}