$(document).ready(function() {
    $('.navbar-toggler').on('click', function() {
        $('.sidebar').toggleClass('d-none')
    })
})

$(document).ready(function() {
    // Toggle the profile container on clicking the "fa-user top-icon"
    $('.fa-user.top-icon').click(function() {
        $('.profile-container').toggleClass('open')
    })

    // Close the profile container when clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.profile-container, .fa-user.top-icon').length) {
            $('.profile-container').removeClass('open')
        }
    })

    // Toggle the notification container on clicking the "fa-bell top-icon"
    $('.fa-bell.top-icon').click(function() {
        $('.notification-container').toggleClass('open')
    })

    // Close the profile container when clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.notification-container, .fa-bell.top-icon').length) {
            $('.notification-container').removeClass('open')
        }
    })
})

window.addEventListener('load', () => {
    const filler = document.getElementById('filler')
    filler.style.width = '100%'; // Set the width to 100% to show the filling animation
    setTimeout(() => {
        const preloader = document.getElementById('preloader')
        preloader.style.display = 'none'
    }, 500)
})

function setActiveNavLink() {
    var currentUrl = window.location.pathname // Get the current URL

    // Loop through each nav-link
    $('.nav-link').each(function() {
        var navLinkUrl = $(this).attr('href')
        var fileName = navLinkUrl.substring(navLinkUrl.lastIndexOf('/') + 1) // Extract file name from URL

        // Check if the file name matches the current URL
        if (currentUrl.endsWith(fileName)) {
            $(this).addClass('active'); // Add active class to the matching nav-link

            // Expand the parent submenu if a child menu item is active
            var parentSubmenu = $(this).closest('.submenu')
            if (parentSubmenu.length > 0) {
                parentSubmenu.show()
                var parentCollapseIcon = parentSubmenu.prev('.nav-link').find('.collapse-icon')
                parentCollapseIcon.removeClass('fa-chevron-right')
                parentCollapseIcon.addClass('fa-chevron-down')
            }

            $(this).closest('.submenu').prev('.nav-link').addClass('active')

            return false // Break the loop if a match is found
        }
    })
}

// Call the function to set the active class on page load
$(document).ready(function() {
    setActiveNavLink()
})

function toggleSubmenu(event) {
    event.preventDefault()
    var submenu = event.target.nextElementSibling
    var collapseIcon = event.target.querySelector('.collapse-icon')
    var parentNavLink = event.target.closest('.nav-link'); // Get the parent nav-link

    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block'
        collapseIcon.classList.remove('fa-chevron-right')
        collapseIcon.classList.add('fa-chevron-down')
        parentNavLink.classList.add('active'); // Add active class to parent nav-link
    } else {
        submenu.style.display = 'none'
        collapseIcon.classList.remove('fa-chevron-down')
        collapseIcon.classList.add('fa-chevron-right')
        parentNavLink.classList.remove('active'); // Remove active class from parent nav-link
    }
}

function OpenAlert(status, Title, Text) {
    Swal.fire({
        icon: status,
        title: Title,
        text: Text,
        html: Text
    })
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

function OpenPopup() {
    document.getElementById('loading-popup').style.display = 'flex'
}

function ClosePopUP() {
    document.getElementById('loading-popup').style.display = 'none'
}

const InnerLoader = document.getElementById('inner-preloader-content').innerHTML


function validateInput(input) {
    // Convert the input value to a string and check its length
    if (input.value.length > 10) {
        // If the length exceeds 10, trim the input value
        input.value = input.value.slice(0, 10);
    }
}

function ChoiceUserLocation(userName, forceChange) {
    var default_location = document.getElementById('default_location').value
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/home/choice-location.php',
            method: 'POST',
            data: {
                userName: userName
            },
            success: function(data) {
                $('#loading-popup').html(data)
                OpenPopup()
            }
        })
    }
    if (default_location == "" || forceChange == 1) {
        fetch_data()
    }

}

function UpdateUserDefaultLocation(userName, locationId, setting) {
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/home/update-default-setting.php',
            method: 'POST',
            data: {
                userName: userName,
                locationId: locationId,
                setting: setting
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    location.reload()
                } else {
                    var result = response.message
                    OpenAlert('error', 'Oops.. Something Wrong!', result)
                }
            }
        })
    }
    fetch_data()

}

function getDeviceApproval(LoggedUser, UserLevel, visitorId) {
    $.ajax({
        url: 'assets/content/set_attributes/get_device_approval.php',
        method: 'POST',
        data: {
            LoggedUser: LoggedUser,
            UserLevel: UserLevel,
            visitorId: visitorId
        },
        success: function(data) {
            var response = JSON.parse(data)
            if (response.status === 'success') {
                var result = response.message
                    // OpenAlert('success', 'Done!', result)
            } else {
                var result = response.message
                OpenAlert('error', 'Unauthorized', result)
            }
        }
    })
}