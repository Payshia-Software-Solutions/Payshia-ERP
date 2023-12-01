var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/printing-module/print-label/index.php',
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

function submitForm() {
    var label = $('#label').val();
    var action = ''; // Change 'url' to 'action'

    // Determine the action based on the selected label
    switch (label) {
        case '0':
            action = './label-printing/thilina-bite-20'; // Change 'url' to 'action'
            break;
        case '1':
            action = './label-printing/thilina-bite-50'; // Change 'url' to 'action'
            break;
        case '2':
            action = './label-printing/thilina-bite-20'; // Change 'url' to 'action'
            break;
        default:
            // Handle other cases or set a default action
            break;
    }

    var form = $('#label-form'); // Use jQuery to select the form

    if (form[0].checkValidity()) { // Use [0] to access the actual DOM element
        form.attr('action', action);
        form.submit();
    } else {
        form[0].reportValidity(); // Use [0] to access the actual DOM element
        var result = 'Please fill out all * marked fields.';
        OpenAlert('error', 'Oops!', result);
    }
}