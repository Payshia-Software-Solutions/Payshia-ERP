<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "LMS Control Center";
$SubPageTitle = "";
$SubPage = false;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $modeTheme ?>">

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
                <div id="index-content" class="mb-5"></div>

            </main>
        </div>
    </div>

    <?php include './include/footer.php' ?>
    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>

    <?php include './include/popups.php' ?>
    <!-- Add Scripts -->

    <?php include './include/footer-scripts.php' ?>
    <script src="./assets/js/lms-master-1.0.0.js"></script>
    <script src="./assets/content/lms-management/assets/js/student-payment-1.0.0.js"></script>
    <script src="./assets/content/lms-management/assets/js/lms-send-lib-1.0.0.js"></script>
    <script src="./assets/content/lms-management/assets/js/course-lib-1.0.0.js"></script>
</body>

</html>