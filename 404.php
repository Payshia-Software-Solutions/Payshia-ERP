<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "404 Error Page Not Found";
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

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="./assets/images/favicon/site.webmanifest">

    <!-- Add Icons -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css' rel='stylesheet'>


    <title>Tutorspree</title>
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
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>