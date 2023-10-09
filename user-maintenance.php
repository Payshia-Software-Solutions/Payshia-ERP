<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Users";
$SubPageTitle = "";
$SubPage = false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Add CSS -->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css" />

    <!-- Add Icons -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css' rel='stylesheet'>
    <script src="https://cdn.tiny.cloud/1/zov6oixuwjxcoleammunkvb3fm95tbgzg2kbzgcjj8f30pxf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


    <title><?= $pageTitle ?> | <?= $SiteTitle ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include './include/header.php' ?>
            <!-- Right Content Container -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="container-fluid mt-3">
                    <!-- Breadcrumb with Icons -->
                    <?php include './include/breadcrumb.php' ?>
                </div>

                <div id="index-content"></div>
            </main>
        </div>
    </div>
    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>


    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="./assets/js/scripts.js"></script>
    <script src="./assets/js/users-1.0.0.js"></script>
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