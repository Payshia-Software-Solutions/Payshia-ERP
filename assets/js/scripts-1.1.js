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
                // Scroll to the active nav-link within the container
            var container = $(this).closest('.submenu'); // Replace with the actual selector of your container
            container.animate({
                scrollTop: $(this).offset().top + container.scrollTop() - container.offset().top - 50
            }, 1000); // You can adjust the duration as needed

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

function ClosePopUP(closeType = 0) {
    if (closeType == 1) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Close it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('loading-popup').style.display = 'none'
            }
        });
    } else if (closeType == 0) {
        document.getElementById('loading-popup').style.display = 'none'
    }

}

function OpenPopupRight() {
    document.getElementById('loading-popup-right').style.display = 'flex'
}

function ClosePopUPRight(closeType = 0) {
    if (closeType == 1) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Close it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('loading-popup-right').style.display = 'none'
            }
        });
    } else if (closeType == 0) {
        document.getElementById('loading-popup-right').style.display = 'none'
    }
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

function SelectDepartments(sectionId, selectedDepartment = 0, selectedCategory = 0) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/product/get-departments.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                company_id: company_id,
                sectionId: sectionId,
                selectedDepartment: selectedDepartment,
                selectedCategory: selectedCategory
            },
            success: function(data) {
                $('#department_id').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function SelectCategory(sectionId, departmentId, selectedCategory = 0) {
    showOverlay()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/product/get-category.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                company_id: company_id,
                sectionId: sectionId,
                departmentId: departmentId,
                selectedCategory: selectedCategory
            },
            success: function(data) {
                $('#category_id').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function toggleDarkMode() {
    const htmlElement = document.documentElement;
    const currentTheme = htmlElement.getAttribute("data-bs-theme");
    toggleBackgrounds()

    if (currentTheme === "dark") {
        htmlElement.setAttribute("data-bs-theme", "light");
        document.getElementById('userTheme').value = 'light'
        var iconSet = document.querySelectorAll('.top-icon')
        iconSet.forEach(element => {
            element.classList.add('text-dark')
            element.classList.remove('text-light')
        });

    } else {
        htmlElement.setAttribute("data-bs-theme", "dark");
        document.getElementById('userTheme').value = 'dark'

        var iconSet = document.querySelectorAll('.top-icon')
        iconSet.forEach(element => {
            element.classList.add('text-light')
            element.classList.remove('text-dark')
        });
    }


}


function toggleBackgrounds() {
    // Toggle background for the navigation element
    const body = document.getElementById('body');
    toggleBackground(body);


    const loadingPop = document.getElementById('loading-popup-content');
    toggleBackground(loadingPop);

    const loadingPopRight = document.getElementById('loading-popup-content-right');
    toggleBackground(loadingPopRight);

    const allSelectElements = document.querySelectorAll('select');
    allSelectElements.forEach(selectElement => {
        // Perform operations on each selectElement
        toggleBackground(selectElement);
        // alert()
    });




}

function toggleBackground(element) {

    // Ensure element is defined and has a classList property
    if (!element || !element.classList) {
        console.error('Invalid element provided:', element);
        return;
    }


    // Check if 'bg-light' class is present, then toggle to 'bg-dark'
    if (element.classList.contains('bg-light')) {
        element.classList.remove('bg-light');
    } else if (element.classList.contains('backlight')) {
        element.classList.remove('backlight')
        element.classList.add('back_dark');
    } else if (element.classList.contains('back_dark')) {
        element.classList.remove('back_dark')
        element.classList.add('backlight');
    } else {
        // Otherwise, toggle to 'bg-light'
        element.classList.remove('bg-dark');
        element.classList.add('bg-light');
    }
}