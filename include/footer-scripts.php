<script src="./pos-system/vendor/jquery/jquery-3.7.1.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/js/scripts-1.0.js"></script>

<!-- Data Tables -->
<script src="./vendor/datatables/DataTables-1.13.8/js/jquery.dataTables.js"></script>
<script src="./vendor/datatables/DataTables-1.13.8/js/jquery.dataTables.min.js"></script>
<script src="./vendor/datatables/DataTables-1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="./vendor/datatables/Buttons-2.4.2/js/dataTables.buttons.min.js"></script>
<script src="./vendor/datatables/JSZip-3.10.1/jszip.min.js"></script>
<script src="./vendor/datatables/pdfmake-0.2.7/pdfmake.min.js"></script>
<script src="./vendor/datatables/pdfmake-0.2.7/vfs_fonts.js"></script>
<script src="./vendor/datatables/Buttons-2.4.2/js/buttons.html5.min.js"></script>
<script src="./vendor/datatables/Buttons-2.4.2/js/buttons.print.min.js"></script>
<script src="./vendor/datatables/Buttons-2.4.2/js/buttons.colVis.min.js"></script>
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

<script src="./vendor/select2/dist/js/select2.min.js"></script>

<script>
    // Initialize the agent at application startup.
    const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
        .then(FingerprintJS => FingerprintJS.load())

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
            // This is the visitor identifier:
            const visitorId = result.visitorId
            console.log(visitorId)
            $('#deviceFingerPrint').val(visitorId)
            getDeviceApproval('<?php echo $session_student_number; ?>', '<?php echo $session_user_level; ?>', visitorId)
        })
</script>