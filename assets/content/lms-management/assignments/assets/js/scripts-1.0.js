var UserLevel = document.getElementById("UserLevel").value;
var LoggedUser = document.getElementById("LoggedUser").value;
var company_id = document.getElementById("company_id").value;
var default_location = document.getElementById("default_location").value;
var default_location_name = document.getElementById(
  "default_location_name"
).value;

$(document).ready(function () {
  OpenIndex();
});

function OpenIndex() {
  function fetch_data() {
    document.getElementById("index-content").innerHTML = InnerLoader;
    $.ajax({
      url: "./assets/content/lms-management/assignments/index.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
      },
      success: function (data) {
        $("#index-content").html(data);
        GetMailBox();
      },
    });
  }
  fetch_data();
}

function GetCourseAssignments(courseCode) {
  function fetch_data() {
    document.getElementById("index-content").innerHTML = InnerLoader;
    $.ajax({
      url: "./assets/content/lms-management/assignments/views/get-assignments.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        courseCode: courseCode,
      },
      success: function (data) {
        $("#index-content").html(data);
      },
    });
  }
  fetch_data();
}

function AddNewAssignment(courseCode, assignmentId = 0) {
  var userTheme = $("#userTheme").val();
  OpenPopup();
  $("#loading-popup").html(InnerLoader);

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/assignments/views/assignment-form.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        userTheme: userTheme,
        company_id: company_id,
        assignmentId: assignmentId,
        courseCode: courseCode,
      },
      success: function (data) {
        $("#loading-popup").html(data);
      },
    });
  }
  fetch_data();
}

function OpenAssignment(courseCode, assignmentId = 0) {
  function fetch_data() {
    document.getElementById("index-content").innerHTML = InnerLoader;
    $.ajax({
      url: "./assets/content/lms-management/assignments/views/open-assignment.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        assignmentId: assignmentId,
        courseCode: courseCode,
      },
      success: function (data) {
        $("#index-content").html(data);
      },
    });
  }
  fetch_data();
}

function SaveAssignment(studentBatch, assignmentId, activeStatus = 1) {
  var form = document.getElementById("assignment-form");

  if (form.checkValidity()) {
    showOverlay();
    var formData = new FormData(form);
    formData.append("LoggedUser", LoggedUser);
    formData.append("UserLevel", UserLevel);
    formData.append("company_id", company_id);
    formData.append("studentBatch", studentBatch);
    formData.append("assignmentId", assignmentId);
    formData.append("activeStatus", activeStatus);

    function fetch_data() {
      showOverlay();
      $.ajax({
        url: "./assets/content/lms-management/assignments/controllers/save-assignment.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          var response = JSON.parse(data);
          if (response.status === "success") {
            var result = response.message;
            GetCourseAssignments(studentBatch);
            OpenAlert("success", "Done!", result);
            ClosePopUP();
          } else {
            var result = response.message;
            OpenAlert("error", "Error!", result);
          }
          hideOverlay();
        },
      });
    }
  } else {
    form.reportValidity();
    result = "Please Filled out All  Fields.";
    OpenAlert("error", "Oops!", result);
  }

  fetch_data();
}

function OpenSubmissions(courseCode, assignmentId = 0) {
  function fetch_data() {
    document.getElementById("index-content").innerHTML = InnerLoader;
    $.ajax({
      url: "./assets/content/lms-management/assignments/views/view-submissions.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
        assignmentId: assignmentId,
        courseCode: courseCode,
      },
      success: function (data) {
        $("#index-content").html(data);
      },
    });
  }
  fetch_data();
}

function OpenSubmittedFiles(assignmentId, indexNumber, courseCode) {
  var userTheme = $("#userTheme").val();

  OpenPopupRight();
  $("#loading-popup-right").html(InnerLoader);

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/assignments/views/view-submitted-files.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        userTheme: userTheme,
        company_id: company_id,
        assignmentId: assignmentId,
        indexNumber: indexNumber,
        courseCode: courseCode,
      },
      success: function (data) {
        $("#loading-popup-right").html(data);
      },
    });
  }
  fetch_data();
}

function SaveGrade(studentBatch, assignmentId, activeStatus = 1) {
  var form = document.getElementById("grade-form");

  if (form.checkValidity()) {
    showOverlay();
    var formData = new FormData(form);
    formData.append("LoggedUser", LoggedUser);
    formData.append("UserLevel", UserLevel);
    formData.append("company_id", company_id);
    formData.append("studentBatch", studentBatch);
    formData.append("assignmentId", assignmentId);
    formData.append("activeStatus", activeStatus);

    function fetch_data() {
      showOverlay();
      $.ajax({
        url: "./assets/content/lms-management/assignments/controllers/save-grade.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          var response = JSON.parse(data);
          if (response.status === "success") {
            var result = response.message;
            OpenAssignment(studentBatch, assignmentId);
            OpenAlert("success", "Done!", result);
            ClosePopUPRight();
          } else {
            var result = response.message;
            OpenAlert("error", "Error!", result);
          }
          hideOverlay();
        },
      });
    }
  } else {
    form.reportValidity();
    result = "Please Filled out All  Fields.";
    OpenAlert("error", "Oops!", result);
  }

  fetch_data();
}

function ChangeStatus(assignmentId, indexNumber, courseCode, isActive) {
  var userTheme = $("#userTheme").val();

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/assignments/controllers/delete-submission.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        userTheme: userTheme,
        company_id: company_id,
        assignmentId: assignmentId,
        isActive: isActive,
        indexNumber: indexNumber,
        courseCode: courseCode,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.status === "success") {
          var result = response.message;
          OpenAlert("success", "Done!", result);
          OpenAssignment(courseCode, assignmentId);
        } else {
          var result = response.message;
          OpenAlert("error", "Error!", result);
        }
        hideOverlay();
      },
    });
  }
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Save it!",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch_data();
    }
  });
}

function ChangeAssignmentStatus(assignmentId, courseCode, activeStatus) {
  var userTheme = $("#userTheme").val();

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/assignments/controllers/change-assignment-status.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        userTheme: userTheme,
        company_id: company_id,
        assignmentId: assignmentId,
        activeStatus: activeStatus,
        courseCode: courseCode,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.status === "success") {
          var result = response.message;
          OpenAlert("success", "Done!", result);
          GetCourseAssignments(courseCode);
        } else {
          var result = response.message;
          OpenAlert("error", "Error!", result);
        }
        hideOverlay();
      },
    });
  }
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Save it!",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch_data();
    }
  });
}

function ForceDeleteSubmission(submissionId, courseCode, assignmentId) {
  var userTheme = $("#userTheme").val();

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/assignments/controllers/force-delete-submission.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        userTheme: userTheme,
        company_id: company_id,
        submissionId: submissionId,
      },
      success: function (data) {
        var response = JSON.parse(data);
        if (response.status === "success") {
          var result = response.message;
          OpenAlert("success", "Done!", result);
          OpenAssignment(courseCode, assignmentId);
        } else {
          var result = response.message;
          OpenAlert("error", "Error!", result);
        }
        hideOverlay();
      },
    });
  }
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Save it!",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch_data();
    }
  });
}
