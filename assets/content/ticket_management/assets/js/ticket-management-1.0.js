var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = document.getElementById('default_location').value
var default_location_name = document.getElementById('default_location_name').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex(studentBatch = 0) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader
        $.ajax({
            url: './assets/content/ticket_management/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#index-content').html(data)
                GetMailBox()
            }
        })
    }
    fetch_data()
}

function GetMailBox(FilterKey = "All", studentBatch = 0, ) {
    function fetch_data() {
        $('#ticketBox').html(InnerLoader)
        $.ajax({
            url: './assets/content/ticket_management/get-ticket-box.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch,
                FilterKey: FilterKey
            },
            success: function(data) {
                $('#ticketBox').html(data)
            }
        })
    }
    fetch_data()
}

function OpenTicket(ticketId) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: './assets/content/ticket_management/ticket-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ticketId: ticketId
            },
            success: function(data) {
                $('#loading-popup').html(data)
                ChangeTicketReadStatus(ticketId, 'read')
            }
        })
    }
    fetch_data()
}

function OpenTicketReply(ticketId) {
    OpenPopup()
    document.getElementById('loading-popup').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: './assets/content/ticket_management/ticket-reply.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ticketId: ticketId
            },
            success: function(data) {
                $('#loading-popup').html(data)
            }
        })
    }
    fetch_data()
}



function ChangeTicketStatus(ticketId, ticketStatus) {
    function fetch_data() {
        showOverlay()
        $.ajax({
            url: './assets/content/ticket_management/change-ticket-status.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ticketId: ticketId,
                ticketStatus: ticketStatus
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
                hideOverlay()
                ClosePopUP()
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
}


function SendReply(ticketId = 0, replyId = 0, isActive = 1) {

    var ticketText = tinymce.get("ticketReply").getContent();
    var form = document.getElementById('ticket-reply-form')

    if (form.checkValidity() && ticketText != '') {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('ticketId', ticketId)
        formData.append('isActive', isActive)
        formData.append('ticketText', ticketText)
        formData.append('replyId', replyId)

        function fetch_data() {
            $.ajax({
                url: './assets/content/ticket_management/send-ticket-reply.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        OpenAlert('success', 'Done!', result)
                        ClosePopUP()
                        OpenIndex()
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        fetch_data()
    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }
}

function SendReplyClose(ticketId = 0, replyId = 0, isActive = 1) {
    var ticketText = tinymce.get("ticketReply").getContent();
    var form = document.getElementById('ticket-reply-form')

    if (form.checkValidity() && ticketText != '') {
        showOverlay()
        var formData = new FormData(form)
        formData.append('LoggedUser', LoggedUser)
        formData.append('UserLevel', UserLevel)
        formData.append('company_id', company_id)
        formData.append('ticketId', ticketId)
        formData.append('isActive', isActive)
        formData.append('ticketText', ticketText)
        formData.append('replyId', replyId)

        function fetch_data() {
            $.ajax({
                url: './assets/content/ticket_management/send-ticket-reply.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data)
                    if (response.status === 'success') {
                        var result = response.message
                        ClosePopUP()
                            // OpenAlert('success', 'Done!', result)
                        ChangeTicketStatus(ticketId, 2)
                    } else {
                        var result = response.message
                        OpenAlert('error', 'Error!', result)
                    }
                    hideOverlay()
                }
            })
        }

        fetch_data()
    } else {
        form.reportValidity()
        result = 'Please Filled out All * marked Fields.'
        OpenAlert('error', 'Error!', result)
        hideOverlay()
    }

}

function ChangeTicketReadStatus(ticketId, readStatus, CloseState = 0) {
    function fetch_data() {
        $.ajax({
            url: './assets/content/ticket_management/change-read-state.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ticketId: ticketId,
                readStatus: readStatus
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                        // OpenAlert('success', 'Done!', result)
                    OpenIndex()

                    if (CloseState != 0) {
                        ClosePopUP()
                    }
                } else {
                    var result = response.message
                        // OpenAlert('error', 'Error!', result)
                }
            }
        })
    }

    fetch_data()
}

function TicketAssignmentUpdate(userName, ticketId, CloseState = 0) {
    function fetch_data() {
        $.ajax({
            url: './assets/content/ticket_management/assign-ticket.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ticketId: ticketId,
                userName: userName
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var result = response.message
                    OpenAlert('success', 'Done!', result)
                    OpenIndex()

                    if (CloseState != 0) {
                        ClosePopUP()
                    }
                } else {
                    var result = response.message
                        // OpenAlert('error', 'Error!', result)
                }
            }
        })
    }

    fetch_data()
}