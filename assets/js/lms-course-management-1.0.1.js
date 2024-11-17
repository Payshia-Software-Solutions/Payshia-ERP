var UserLevel = document.getElementById("UserLevel").value;
var LoggedUser = document.getElementById("LoggedUser").value;
var company_id = document.getElementById("company_id").value;
var defaultLocation = document.getElementById("default_location").value;
var defaultLocationName = document.getElementById(
    "default_location_name"
).value;

$(document).ready(function() {
    OpenIndex();
});

function OpenIndex(studentBatch = 0, orderType = 0) {
    function fetch_data() {
        document.getElementById("index-content").innerHTML = InnerLoader;
        $.ajax({
            url: "assets/content/lms-management/course/index.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch,
                orderType: orderType,
                defaultLocation: defaultLocation,
            },
            success: function(data) {
                $("#index-content").html(data);
            },
        });
    }
    fetch_data();
}

function EditGradesByCourse(studentBatch = 0) {
    function fetch_data() {
        document.getElementById("index-content").innerHTML = InnerLoader;
        $.ajax({
            url: "assets/content/lms-management/course/grade/edit-grades.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch,
            },
            success: function(data) {
                $("#index-content").html(data);
            },
        });
    }
    fetch_data();
}

function ViewGradesByCourse(studentBatch = 0) {
    function fetch_data() {
        document.getElementById("index-content").innerHTML = InnerLoader;
        $.ajax({
            url: "assets/content/lms-management/course/grade/view-saved-grades.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch,
            },
            success: function(data) {
                $("#index-content").html(data);
            },
        });
    }
    fetch_data();
}

function ChangeGradeValue(assignmentId, studentNumber, gradeValue) {
    function fetch_data() {
        showOverlay();
        $.ajax({
            url: "assets/content/lms-management/course/grade/save-edited-grade.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                assignmentId: assignmentId,
                studentNumber: studentNumber,
                gradeValue: gradeValue,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var result = response.message;
                    OpenAlert("success", "Done!", result);
                } else {
                    var result = response.message;
                    OpenAlert("error", "Error!", result);
                }
                hideOverlay();
            },
        });
    }
    fetch_data();
}

function AddNewCourse(courseCode = 0) {
    OpenPopup();
    document.getElementById("loading-popup").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/lms-management/course/update-course.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                defaultLocation: defaultLocation,
                defaultLocationName: defaultLocationName,
                courseCode: courseCode,
            },
            success: function(data) {
                $("#loading-popup").html(data);
            },
        });
    }
    fetch_data();
}

function OpenGrading(studentBatch = 0) {
    OpenPopup();
    document.getElementById("loading-popup").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/lms-management/course/grade/index.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch,
            },
            success: function(data) {
                $("#loading-popup").html(data);
            },
        });
    }
    fetch_data();
}

function GetTemplateExcel(studentBatch = 0) {
    OpenPopup();
    document.getElementById("templateExcel").innerHTML = InnerLoader;

    function fetch_data() {
        showOverlay();
        $.ajax({
            url: "assets/content/lms-management/course/grade/get-template.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                studentBatch: studentBatch,
            },
            success: function(data) {
                $("#templateExcel").html(data);
                hideOverlay();
            },
        });
    }
    fetch_data();
}

function SaveGradeData() {
    var studentBatch = document.getElementById("batchId").value;
    var form = document.getElementById("excelUploadForm");

    if (form.checkValidity()) {
        showOverlay();
        var formData = new FormData(form);
        formData.append("LoggedUser", LoggedUser);
        formData.append("UserLevel", UserLevel);
        formData.append("company_id", company_id);
        formData.append("studentBatch", studentBatch);

        function fetch_data() {
            $.ajax({
                url: "assets/content/lms-management/course/grade/view-grading.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#loading-popup").html(data);
                    hideOverlay();
                },
            });
        }

        fetch_data();
    } else {
        form.reportValidity();
        result = "Please Filled out All  Fields.";
        OpenAlert("error", "Oops!", result);
    }
}

function CommitChanges() {
    var form = document.getElementById("commitForm");

    if (form.checkValidity()) {
        showOverlay();
        var formData = new FormData(form);
        formData.append("LoggedUser", LoggedUser);
        formData.append("UserLevel", UserLevel);
        formData.append("company_id", company_id);

        function fetch_data() {
            $.ajax({
                url: "assets/content/lms-management/course/grade/save-grading.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    OpenAlert("success", "Done!", data);
                    hideOverlay();
                },
            });
        }

        fetch_data();
    } else {
        form.reportValidity();
        result = "Please Filled out All  Fields.";
        OpenAlert("error", "Oops!", result);
    }
}

function PrintGameReport(gameTitle, studentBatch, userName, locationId) {
    // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open(
        "report-viewer/lms-reports/game-reports/" +
        gameTitle +
        "?studentBatch=" +
        encodeURIComponent(studentBatch) +
        "&userId=" +
        encodeURIComponent(userName) +
        "&locationId=" +
        encodeURIComponent(locationId),
        "_blank"
    );

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}