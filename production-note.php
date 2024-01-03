<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Production Note";
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

    <?php include './include/footer.php' ?>
    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>

    <div class="loading-popup" id="loading-popup"></div>
    <!-- Add Scripts -->

    <?php include './include/footer-scripts.php' ?>
    <script src="./assets/js/production-note-1.0.js"></script>
</body>

</html>