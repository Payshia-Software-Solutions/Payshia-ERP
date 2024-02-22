function SendSmsPopup(phoneNumber) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/send-sms/message-popup.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                phoneNumber: phoneNumber
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SendEmailPopup(fromAddress, toEmailAddress) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/send-email/message-popup.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                fromAddress: fromAddress,
                toEmailAddress: toEmailAddress
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}