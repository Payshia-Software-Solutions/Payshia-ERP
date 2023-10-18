var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var folder = 'category'
$(document).ready(function () {
  OpenIndex()
})

function OpenIndex () {
  document.getElementById('index-content').innerHTML = InnerLoader
  ClosePopUP()
  function fetch_data () {
    $.ajax({
      url: 'assets/content/' + folder + '/index.php',
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

function LoadPopUPContent (UpdateKey) {
  document.getElementById('loading-popup').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/' + folder + '/popup-content.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        UpdateKey: UpdateKey
      },
      success: function (data) {
        $('#loading-popup').html(data)
        if (UpdateKey != -1) {
          OpenPopup()
        }
      }
    })
  }
  fetch_data()
}

function AddNew (is_active, UpdateKey) {
  LoadPopUPContent(UpdateKey)
}

function Save (is_active, UpdateKey) {
  var form = document.getElementById('location-form')

  if (form.checkValidity()) {
    showOverlay()
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('is_active', is_active)
    formData.append('UpdateKey', UpdateKey)

    function fetch_data () {
      $.ajax({
        url: 'assets/content/' + folder + '/save.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          var response = JSON.parse(data)
          if (response.status === 'success') {
            var result = response.message
            OpenAlert('success', 'Done!', result)
            OpenIndex()
          } else {
            var result = response.message
            OpenAlert('error', 'Oops.. Something Wrong!', result)
          }
          hideOverlay()
        }
      })
    }
    fetch_data()
  } else {
    result = 'Please Filled out All * marked Fields.'
    OpenAlert('error', 'Oops!', result)
  }
}

function ChangeStatus (IsActive, UpdateKey) {
  showOverlay()
  document.getElementById('index-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/' + folder + '/change-status.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        company_id: company_id,
        IsActive: IsActive,
        UpdateKey: UpdateKey,
        UserLevel: UserLevel
      },
      success: function (data) {
        var response = JSON.parse(data)
        if (response.status === 'success') {
          var result = response.message
          OpenAlert('success', 'Done!', result)
          hideOverlay()
          OpenIndex()
        } else {
          var result = response.message
          OpenAlert('error', 'Oops.. Something Wrong!', result)
        }
      }
    })
  }
  fetch_data()
}
