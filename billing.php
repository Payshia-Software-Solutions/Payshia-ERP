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

    <!-- Add Icons -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css' rel='stylesheet'>


    <title><?= $pageTitle ?> | <?= $SiteTitle ?></title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

        </div>
    </div>


    <?php include './include/footer.php' ?>
</body>

</html>