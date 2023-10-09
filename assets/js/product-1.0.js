var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function () {
  OpenIndex()
})

// JavaScript to show the overlay
function showOverlay () {
  var overlay = document.querySelector('.overlay')
  overlay.style.display = 'block'
}

// JavaScript to hide the overlay
function hideOverlay () {
  var overlay = document.querySelector('.overlay')
  overlay.style.display = 'none'
}

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }

const InnerLoader = document.getElementById('inner-preloader-content').innerHTML

// Take this
function OpenIndex () {
  document.getElementById('index-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/product/index.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel
      },
      success: function (data) {
        $('#index-content').html(data)
      }
    })
  }
  fetch_data()
}

function AddProduct (is_active, updateKey) {
  showOverlay()
  document.getElementById('index-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/product/create.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        company_id: company_id,
        is_active: is_active,
        updateKey: updateKey,
        UserLevel: UserLevel
      },
      success: function (data) {
        $('#index-content').html(data)
        hideOverlay()
      }
    })
  }
  fetch_data()
}
