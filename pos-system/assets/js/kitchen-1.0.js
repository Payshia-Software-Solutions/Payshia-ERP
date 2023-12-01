var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var LocationID = document.getElementById('LocationID').value

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
    OpenIndex()
})

function OpenPopup() {
    document.getElementById('loading-popup').style.display = 'flex'
}

function ClosePopUP() {
    document.getElementById('loading-popup').style.display = 'none'
}

function EmptyPopup() {
    document.getElementById('loading-popup').style.display = 'none'
    document.getElementById('pop-content').innerHTML = ""
}

// JavaScript to show the overlay
function showOverlay() {
    var overlay = document.querySelector('.overlay')
    overlay.style.display = 'block'
}

// JavaScript to hide the overlay
function hideOverlay() {
    var overlay = document.querySelector('.overlay')
    overlay.style.display = 'none'
}

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }

const InnerLoader = document.getElementById('inner-preloader-content').innerHTML
    // const InnerLoader = 'Please Wait..'

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/kitchen/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID
            },
            success: function(data) {
                $('#index-content').html(data)
                GetNewOrder()
                    // Set up an interval to run the resetInactivityTimer function every 1 second (1000 milliseconds).
                setInterval(GetNewOrder, 6000);
            }
        })
    }
    fetch_data()
}

function GetNewOrder() {
    var PreCount = parseInt(document.getElementById('hold_count').value, 10);

    function fetch_data() {
        $.ajax({
            url: 'assets/content/kitchen/get-new-order.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                PreCount: PreCount
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    var InvoiceNumber = response.inv_number
                    GetLatestInvoice(InvoiceNumber, 1)
                    document.getElementById('hold_count').value = PreCount + 1
                } else {
                    var result = response.message
                }

                showNotification(result)
            }
        })
    }

    fetch_data()
}

function GetLatestInvoice(InvoiceNumber, PlayStatus) {
    document.getElementById('pop-content').innerHTML = InnerLoader
    OpenPopup()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/kitchen/order-notification.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                InvoiceNumber: InvoiceNumber,
                PlayStatus: PlayStatus
            },
            success: function(data) {
                $('#pop-content').html(data)

                OpenIndex()
            }
        })
    }

    fetch_data()
}

function PrintKOT(InvoiceNumber) {
    // Replace 'bill-print.php' with the actual URL of your PHP script
    var windowWidth = 600 // Adjust this width as needed
    var windowHeight = 800 // Adjust this height as needed

    // Calculate the center position based on screen dimensions
    var screenWidth = window.screen.availWidth
    var screenHeight = window.screen.availHeight
    var left = (screenWidth - windowWidth) / 2
    var top = (screenHeight - windowHeight) / 2

    // Replace 'bill-print.php' with the actual URL of your PHP script
    var printWindow = window.open('kot-print.php?invoice_number=' + InvoiceNumber, '_blank', 'width=' + windowWidth + ',height=' + windowHeight + ',left=' + left + ',top=' + top)

    // Check if the window was opened successfully
    if (printWindow) {
        // Add an event listener to print once the content is loaded
        printWindow.addEventListener('load', function() {
            printWindow.document.location.href = 'kot-print.php?invoice_number=' + InvoiceNumber
            printWindow.print()
        })
    } else {
        alert('The popup window was blocked. Please allow pop-ups for this site.')
    }
}