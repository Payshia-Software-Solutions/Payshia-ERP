<?php
require_once('../include/config.php');
include '../include/session.php';
include '../include/function-update.php';

$company_id = 1452254565;
$UserLevel = $session_user_level;
$StudentNumber = $session_student_number;
$LoggedStudent = GetAccounts($link)[$session_student_number];
$LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
$pageTitle = "POS";
$SubPageTitle = "";
$SubPage = false;
$CompanyName = "Payshia";
$LocationID = 1;
?>


<input type="hidden" value="<?php echo $session_student_number; ?>" id="LoggedUser" name="LoggedUser">
<input type="hidden" value="<?php echo $session_user_level; ?>" id="UserLevel" name="UserLevel">
<input type="hidden" value="<?php echo $company_id; ?>" id="company_id" name="company_id">
<input type="hidden" value="<?php echo $LocationID; ?>" id="LocationID" name="LocationID">


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './include/header.php' ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="row">
            <div class="col-12">
                <div class="top-bar p-2">
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <h4><?= $CompanyName ?></h4>
                        </div>
                        <div class="d-none d-lg-block col-md-4">
                            <input type="text" class="form-control" placeholder="Search Product" id="search-key" onclick="this.select()">
                        </div>
                        <div class="col-8 col-md-4 text-end">
                            <button type="button" id="scrollToBottomButton" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-down-to-line"></i></button>
                            <button type="button" id="scrollToTopButton" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-up-to-line"></i></button>
                            <button type="button" class="btn select-table-button " onclick="SetTable()"><i class="fa-solid fa-table btn-icon"></i> Select Table</button>
                            <button type="button" onclick="OpenIndex()" class="btn refresh-button mr-2"><i class="fa-solid fa-arrows-rotate"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="border-right d-none d-md-flex" id="sidebar">
            <div class="list-group list-group-flush">
                <a href="./" class="list-group-item list-group-item-action text-center">
                    <i class="fa-solid fa-house fs-3 d-block mx-auto"></i>
                    POS
                </a>
                <a href="../" target="_blank" class="list-group-item list-group-item-action text-center">
                    <i class="fa-solid fa-box fs-3  d-block mx-auto"></i>
                    Admin
                </a>
                <a href="#" onclick="GetTodaySales()" class="list-group-item list-group-item-action text-center">
                    <i class="fa-solid fa-cash-register fs-3  d-block mx-auto"></i>
                    Sales
                </a>

                <a href="./logout" class="list-group-item list-group-item-action text-center">
                    <i class="fa-solid fa-right-from-bracket fs-3  d-block mx-auto"></i>
                    Logout
                </a>


            </div>
        </div>


        <!-- Page Content -->
        <div class="container-fluid" id="index-content">
        </div>

        <?php include './include/footer.php' ?>

        <script>
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            document.addEventListener("DOMContentLoaded", function() {
                const scrollToTopButton = document.getElementById("scrollToTopButton");
                const scrollToBottomButton = document.getElementById("scrollToBottomButton");

                scrollToTopButton.addEventListener("click", function() {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                });

                scrollToBottomButton.addEventListener("click", function() {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: "smooth"
                    });
                });
            });
        </script>
</body>

</html>