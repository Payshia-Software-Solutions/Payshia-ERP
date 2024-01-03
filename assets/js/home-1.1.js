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
    ChoiceUserLocation(LoggedUser, 0)

    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/home/index.php',
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

    if (default_location != "") {
        fetch_data()
        OpenAlert('info', default_location_name, 'Default Location')
    }
}