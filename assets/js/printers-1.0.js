var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/master_module/printers/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}

function UpdateSetting(settingKey, settingValue, LocationID) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/master_module/printers/set-printer.php',
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                settingKey: settingKey,
                settingValue: settingValue,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var message = response.message;
                    OpenIndex();
                    OpenAlert('success', 'Done!', message)
                } else {
                    var message = response.message;
                    OpenAlert('error', 'Oops!', message)
                }
                hideOverlay()
            },
        });
    }

    fetch_data();
}