function ConfirmEnrollmentRemove(studentNumber, studentBatch) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        showOverlay();

        $.ajax({
            url: 'assets/content/lms-management/course/enrollments/confirmation.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentNumber: studentNumber,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#loading-popup').html(data)
                hideOverlay();
            }
        })
    }
    fetch_data()
}