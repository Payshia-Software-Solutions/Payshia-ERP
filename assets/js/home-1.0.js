var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function () {
  OpenIndex()
})

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }

const InnerLoader = document.getElementById('inner-preloader-content').innerHTML

function OpenIndex () {
  document.getElementById('index-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/home/index.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id
      },
      success: function (data) {
        $('#index-content').html(data)
      }
    })
  }
  fetch_data()
}
