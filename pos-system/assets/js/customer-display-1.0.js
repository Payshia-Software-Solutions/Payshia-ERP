var UserLevel = document.getElementById("UserLevel").value;
var LoggedUser = document.getElementById("LoggedUser").value;
var company_id = document.getElementById("company_id").value;
var LocationID = document.getElementById("LocationID").value;
var LastInvoiceStatus = document.getElementById("LastInvoiceStatus").value;

let inactivityTimer
const inactivityTimeout = 5 * 60 * 1000 // 5 minutes in milliseconds

function resetInactivityTimer() {
    clearTimeout(inactivityTimer)
    inactivityTimer = setTimeout(logout, inactivityTimeout);
}

function logout() {
    // Perform logout actions, such as redirecting to a logout page or clearing the session.
    window.location.href = 'logout.php' // Redirect to the logout page
}

// Set up an interval to run the resetInactivityTimer function every 1 second (1000 milliseconds).
setInterval(resetInactivityTimer, 30000);

// Start the initial inactivity timer
resetInactivityTimer();


$(document).ready(function() {
    OpenIndex();
});

function OpenIndex() {
    function fetch_data() {
        $.ajax({
            url: "assets/content/customer-display/index.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#index-content").html(data);
            },
        });
    }
    fetch_data();
}