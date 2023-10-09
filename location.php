<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Location";
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkDMTbt8fmdX97m_oC_fZ93cX79k92rnU&libraries=places"></script>
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

    <div class="loading-popup" id="loading-popup"></div>
    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="./assets/js/scripts.js"></script>

    <script src="./assets/js/location-1.0.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>