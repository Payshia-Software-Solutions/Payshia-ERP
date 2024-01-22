<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Home";
$SubPageTitle = "";
$SubPage = false;

// Path to your PowerPoint file
$pptxFile = './newPresentation.pptx';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Add Icons -->
    <?php include './include/common-scripts.php' ?>

    <title><?= $pageTitle ?> | <?= $SiteTitle ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include './include/header.php' ?>

            <!-- Right Content Container -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4" id="site-content">
                <div class="container-fluid mt-3">
                    <!-- Breadcrumb with Icons -->
                    <?php include './include/breadcrumb.php' ?>
                </div>

                <div id="index-content"></div>


                <!-- Include necessary JavaScript libraries -->
                <script src="https://appsforoffice.microsoft.com/lib/1/hosted/office.js"></script>
                <style>
                    #presentationContainer {
                        width: 100%;
                        height: 600px;
                        /* Set the desired height */
                    }
                </style>
                <!-- Container for the PowerPoint presentation -->
                <div id="presentationContainer">
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=https://web.pharmacollege.lk/newPresentation.pptx" width="100%" height="100%" frameborder="0"> </iframe>
                </div>










            </main>

        </div>



    </div>

    <?php include './include/footer.php' ?>
    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>


    <div class="loading-popup" id="loading-popup"></div>
    <!-- Add Scripts -->

    <?php include './include/footer-scripts.php' ?>
    <script src="./assets/js/home-1.1.js"></script>
</body>

</html>