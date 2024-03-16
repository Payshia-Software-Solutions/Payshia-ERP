var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/lms-management/certification/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}

function GetCertificationPage(selectedCourse) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: 'assets/content/lms-management/certification/certification-by-course.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                selectedCourse: selectedCourse
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}


function PrintDialogOpen(studentBatch, studentNumber) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/certification/print-dialogue.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch,
                studentNumber: studentNumber
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function TemplateIndex(studentBatch) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/certification/template-index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}


function OpenNewTemplate(studentBatch, templateId = 0) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/certification/new-template.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                templateId: templateId,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveTemplate(studentBatch, templateId) {

    var form = document.getElementById('templateForm')

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('studentBatch', studentBatch)
        formData.append('templateId', templateId)

        function fetch_data() {
            showOverlay()
            $.ajax({
                url: 'assets/content/lms-management/certification/requests/save-template.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        GetCertificationPage(studentBatch)
                        OpenAlert('success', 'Done!', result)
                        ClosePopUP()
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }
    } else {
        form.reportValidity();
        result = 'Please Filled out All  Fields.'
        OpenAlert('error', 'Oops!', result)
    }

    fetch_data()
}

function OpenEditProfileDialogue(studentNumber, selectedCourse) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/profile/view-profile.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentNumber: studentNumber,
                selectedCourse: selectedCourse
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function OpenEditProfile(studentNumber, selectedCourse) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/profile/edit-profile.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentNumber: studentNumber,
                selectedCourse: selectedCourse
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveCertificateConfiguration(selectedCourse) {
    var form = document.getElementById('config-form')

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('selectedCourse', selectedCourse)

        function fetch_data() {
            $.ajax({
                url: 'assets/content/lms-management/certification/requests/save-configuration.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        GetCertificationPage(selectedCourse)
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
        form.reportValidity();
        result = 'Please Filled out All  Fields.'
        OpenAlert('error', 'Oops!', result)
    }
}

function SaveProfileInfo(studentNumber, selectedCourse) {
    var form = document.getElementById('submit-form')

    if (form.checkValidity()) {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('studentNumber', studentNumber)

        function fetch_data() {
            $.ajax({
                url: 'assets/content/lms-management/profile/save-user-data.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        GetCertificationPage(selectedCourse)
                        OpenEditProfileDialogue(studentNumber, selectedCourse)
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
        form.reportValidity();
        result = 'Please Filled out All  Fields.'
        OpenAlert('error', 'Oops!', result)
    }
}

function PrintCertificate(studentNumber, selectedCourse) {
    var form = document.getElementById('print-certificate-form');

    if (form.checkValidity()) {
        var formData = new FormData(form);
        var issuedDate = formData.get('issuedDate');
        var certificateTemplate = formData.get('certificateTemplate');
        var backImageStatus = formData.get('backImageStatus');

        var url = 'assets/content/lms-management/certification/print-view/certificate.php?' +
            'studentNumber=' + encodeURIComponent(studentNumber) +
            '&selectedCourse=' + encodeURIComponent(selectedCourse) +
            '&certificateTemplate=' + encodeURIComponent(certificateTemplate) +
            '&PrintedId=' + encodeURIComponent(LoggedUser) +
            '&issuedDate=' + encodeURIComponent(issuedDate) +
            '&backImageStatus=' + encodeURIComponent(backImageStatus);

        var win = window.open(url, '_blank');
        if (win) {
            win.focus();
            GetCertificationPage(selectedCourse)
            PrintDialogOpen(selectedCourse, studentNumber)
        } else {
            // If pop-up was blocked or not allowed.
            alert('Please allow pop-ups for this site to open the certificate.');
        }
    } else {
        form.reportValidity();
        result = 'Please fill out all fields.';
        OpenAlert('error', 'Oops!', result);
    }
}



function PrintTranscript(studentNumber, selectedCourse) {
    var form = document.getElementById('print-certificate-form');

    if (form.checkValidity()) {
        var formData = new FormData(form);
        var issuedDate = formData.get('issuedDate');
        var certificateTemplate = formData.get('certificateTemplate');
        var backImageStatus = formData.get('backImageStatus');

        var url = 'assets/content/lms-management/certification/print-view/transcript.php?' +
            'studentNumber=' + encodeURIComponent(studentNumber) +
            '&selectedCourse=' + encodeURIComponent(selectedCourse) +
            '&certificateTemplate=' + encodeURIComponent(certificateTemplate) +
            '&PrintedId=' + encodeURIComponent(LoggedUser) +
            '&issuedDate=' + encodeURIComponent(issuedDate) +
            '&backImageStatus=' + encodeURIComponent(backImageStatus);

        var win = window.open(url, '_blank');
        if (win) {
            win.focus();
            GetCertificationPage(selectedCourse)
            PrintDialogOpen(selectedCourse, studentNumber)
        } else {
            // If pop-up was blocked or not allowed.
            alert('Please allow pop-ups for this site to open the certificate.');
        }
    } else {
        form.reportValidity();
        result = 'Please fill out all fields.';
        OpenAlert('error', 'Oops!', result);
    }
}

function PrintWorkshop(studentNumber, selectedCourse) {
    var form = document.getElementById('print-certificate-form');

    if (form.checkValidity()) {
        var formData = new FormData(form);
        var issuedDate = formData.get('issuedDate');
        var certificateTemplate = formData.get('certificateTemplate');
        var backImageStatus = formData.get('backImageStatus');

        var url = 'assets/content/lms-management/certification/print-view/workshop-certificate.php?' +
            'studentNumber=' + encodeURIComponent(studentNumber) +
            '&selectedCourse=' + encodeURIComponent(selectedCourse) +
            '&certificateTemplate=' + encodeURIComponent(certificateTemplate) +
            '&PrintedId=' + encodeURIComponent(LoggedUser) +
            '&issuedDate=' + encodeURIComponent(issuedDate) +
            '&backImageStatus=' + encodeURIComponent(backImageStatus);

        var win = window.open(url, '_blank');
        if (win) {
            win.focus();
            GetCertificationPage(selectedCourse)
            PrintDialogOpen(selectedCourse, studentNumber)
        } else {
            // If pop-up was blocked or not allowed.
            alert('Please allow pop-ups for this site to open the certificate.');
        }
    } else {
        form.reportValidity();
        result = 'Please fill out all fields.';
        OpenAlert('error', 'Oops!', result);
    }
}

function OpenTranscriptDataEntry(studentNumber, selectedCourse) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/lms-management/certification/transcript-data-entry.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentNumber: studentNumber,
                selectedCourse: selectedCourse
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}

function SaveCertificate(CourseCode, indexNo) {
    var LoopCount = document.getElementById('LoopCount').value;
    var TitleIDs = [];
    var OptionValues = [];

    // Collect all TitleIDs and OptionValues
    for (var i = 1; i <= LoopCount; i++) {
        var TitleID = document.getElementById('optionID-' + i).value;
        var OptionValue = document.getElementById('option-' + i).value;
        TitleIDs.push(TitleID);
        OptionValues.push(OptionValue);
    }

    // showOverlay() should be called only once before AJAX request
    showOverlay();

    // AJAX request to save transcript entry
    $.ajax({
        url: 'assets/content/lms-management/certification/requests/save-transcript-entry.php',
        method: 'POST',
        data: {
            LoggedUser: LoggedUser,
            UserLevel: UserLevel,
            CourseCode: CourseCode,
            indexNo: indexNo,
            TitleIDs: TitleIDs, // Send all TitleIDs
            OptionValues: OptionValues // Send all OptionValues
        },
        success: function(data) {
            OpenAlert('success', 'Done!', data);
            hideOverlay();
        }
    });
}