<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Super Admin";
$SubPageTitle = "";
$SubPage = false;
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
<<<<<<<< HEAD:super-admin.php
    <script src="./assets/content/super-admin/assets/js/super-admin-1.0.js"></script>
========
    <script src="./assets/js/lms-course-management-1.0.1.js"></script>
>>>>>>>> 4820cfea525a5afd6311ec61f7829f2439d6add6:lms-course-management.php
</body>

</html>