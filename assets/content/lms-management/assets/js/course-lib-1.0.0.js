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

function SaveCourse(courseCode = 0, isActive = 1) {
    var course_description = tinymce.get("courseDescription").getContent();
    var form = document.getElementById('course-form')

    if (form.checkValidity()) {
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('courseCode', courseCode)
        formData.append('course_description', course_description)
        formData.append('isActive', isActive)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: './assets/content/lms-management/course/course-settings/save-course-data.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        OpenIndex()
                        ClosePopUP()
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch_data()
            }
        });

    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }

}