var UserLevel = document.getElementById('UserLevel').value;
var LoggedUser = document.getElementById('LoggedUser').value;
var company_id = document.getElementById('company_id').value;
var default_location = document.getElementById('default_location').value;
var default_location_name = document.getElementById('default_location_name').value;

$(document).ready(function() {
    OpenIndex();
});

function OpenIndex(studentBatch = 0) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader;
        $.ajax({
            url: './assets/content/super-admin/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#index-content').html(data);
            }
        });
    }
    fetch_data();
}