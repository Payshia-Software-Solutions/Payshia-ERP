<?php
require_once('./include/config.php');
include './include/function-update.php';
$pageTitle = "Home";
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


    <title><?= $pageTitle ?> | <?= $SiteTitle ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

        </div>
    </div>
    <test></test>

    <?php include './include/footer.php' ?>
</body>

</html>