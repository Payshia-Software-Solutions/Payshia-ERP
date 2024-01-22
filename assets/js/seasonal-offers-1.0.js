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
            url: 'assets/content/promotion_module/seasonal-offers/index.php',
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

function AddNewOffer(UpdateKey = 0) {
    showOverlay()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/promotion_module/seasonal-offers/add-new-offer.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                UpdateKey: UpdateKey
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
                hideOverlay()
            }
        })
    }
    fetch_data()
}