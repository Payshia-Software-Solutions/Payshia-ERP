<?php
require_once('./include/config.php');
include './include/functions.php';
$pageTitle = "Profile";
$SubPageTitle = "";
$SubPage = false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Add Script -->
    <?php include './include/common-scripts.php' ?>

    <title><?= $SiteTitle ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include './include/header.php' ?>
            <!-- Right Content Container -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="container-fluid mt-3">
                    <!-- breadcrumb -->
                    <?php include './include/breadcrumb.php' ?>

                    <div id="index-content"></div>


                </div>
            </main>
        </div>
    </div>

    <?php include './include/footer.php' ?>
    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>



    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="./assets/js/scripts-1.0.js"></script>
    <script src="./assets/js/profile-1.0.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>


    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>