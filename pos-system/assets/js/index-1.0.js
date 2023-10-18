var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function () {
  OpenIndex()
})

function OpenPopup () {
  document.getElementById('loading-popup').style.display = 'flex'
}

function ClosePopUP () {
  document.getElementById('loading-popup').style.display = 'none'
}

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }

// const InnerLoader = document.getElementById('inner-preloader-content').innerHTML
const InnerLoader = 'Please Wait..'

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
        OpenItemContainer()
        OpenBillContainer()
      }
    })
  }
  fetch_data()
}

function OpenItemContainer () {
  document.getElementById('item-container').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/home/item-container.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id
      },
      success: function (data) {
        $('#item-container').html(data)
      }
    })
  }
  fetch_data()
}

function OpenBillContainer () {
  document.getElementById('bill-container').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/home/bill.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id
      },
      success: function (data) {
        $('#bill-container').html(data)
      }
    })
  }
  fetch_data()
}

function OpenQtySelector (ProductID, ItemPrice, ItemDiscount, CurrentStock) {
  OpenPopup()
  document.getElementById('pop-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/home/item-qty-selector.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        ProductID: ProductID,
        ItemPrice: ItemPrice,
        ItemDiscount: ItemDiscount,
        CurrentStock: CurrentStock
      },
      success: function (data) {
        $('#pop-content').html(data)
      }
    })
  }
  fetch_data()
}

function appendToInput (value) {
  var inputBox = document.getElementById('qty-input')
  var currentValue = inputBox.value
  if (currentValue === '0' && value !== '.') {
    inputBox.value = value
  } else {
    inputBox.value += value
  }
}

function clearInput () {
  var inputBox = document.getElementById('qty-input')
  inputBox.value = ''
}

function AddToCart (ProductID) {
  var ItemPrice = document.getElementById('item-price').value
  var ItemDiscount = document.getElementById('item-discount').value
  var CustomerID = document.getElementById('customer-id').value
  var ItemQty = document.getElementById('qty-input').value

  function fetch_data () {
    document.getElementById('pop-content').innerHTML = InnerLoader
    $.ajax({
      url: 'assets/content/home/tasks/add-to-cart.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        ProductID: ProductID,
        ItemPrice: ItemPrice,
        ItemDiscount: ItemDiscount,
        CustomerID: CustomerID,
        ItemQty: ItemQty
      },
      success: function (data) {
        var response = JSON.parse(data)
        if (response.status === 'success') {
          var result = response.message
          OpenBillContainer()
          ClosePopUP()
        } else {
          var result = response.message
        }
        showNotification(result)
      }
    })
  }
  if (ItemQty > 0) {
    fetch_data()
  } else {
    showNotification('Please Add Quantity')
  }
}

function showNotification (message) {
  var notification = document.getElementById('notification')
  notification.textContent = message
  notification.style.display = 'block'

  // Hide the notification after 3 seconds (3000 milliseconds)
  setTimeout(function () {
    notification.style.display = 'none'
  }, 3000)
}

function RemoveFromCart (ProductID) {
  document.getElementById('pop-content').innerHTML = InnerLoader
  function fetch_data () {
    $.ajax({
      url: 'assets/content/home/tasks/remove-from-cart.php',
      method: 'POST',
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        ProductID: ProductID
      },
      success: function (data) {
        var response = JSON.parse(data)
        if (response.status === 'success') {
          var result = response.message
          OpenBillContainer()
        } else {
          var result = response.message
        }
        showNotification(result)
      }
    })
  }
  fetch_data()
}
