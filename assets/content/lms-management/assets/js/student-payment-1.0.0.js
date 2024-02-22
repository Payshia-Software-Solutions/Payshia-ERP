function MakeStudentPayment(studentNumber) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/payments/student-payment.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentNumber: studentNumber
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function ProceedStudentPayment(studentNumber) {
    var courseCode = $('#courseCode ').val()
    var paymentAmount = $('#payment_amount').val();
    var paymentType = $('#paymentType').val();
    var discountAmount = $('#discountAmount').val();

    // Check if the variables are not empty and payment amount is greater than 0
    if (paymentAmount && paymentType && discountAmount) {
        function fetch_data() {

            showOverlay();

            $.ajax({
                url: 'assets/content/lms-management/payments/proceed-payment.php',
                method: 'POST',
                data: {
                    LoggedUser: LoggedUser,
                    UserLevel: UserLevel,
                    studentNumber: studentNumber,
                    paymentType: paymentType,
                    paymentAmount: paymentAmount,
                    discountAmount: discountAmount,
                    courseCode: courseCode
                },
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        ClosePopUP()
                        GetStudentInformation(studentNumber)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Oops..!', result)
                    }

                    hideOverlay();
                }
            });
        }
        fetch_data();
    } else {
        // Handle validation errors, for example, show an alert to the user.
        OpenAlert('error', 'Oops..!', 'Please make sure all fields are filled.');
    }
}