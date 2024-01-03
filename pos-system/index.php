<?php
require_once('../include/config.php');
include '../include/session.php';
include '../include/function-update.php';

$scaleFactor = 100;
$display_invoice_number = 0;
$company_id = 1452254565;
$UserLevel = $session_user_level;
$StudentNumber = $session_student_number;
$LoggedStudent = GetAccounts($link)[$session_student_number];
$LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
$pageTitle = "POS";
$SubPageTitle = "";
$SubPage = false;
$CompanyName = "PayshiaPOS";
if (isset($_GET['location_id'])) {
    $LocationID = $_GET['location_id'];
} else {
    header("Location: choice-location");
    exit();
}

$Locations = GetLocations($link);
$LastInvoiceID = 0;
if (isset($_GET['last_invoice']) && $_GET['last_invoice'] === 'true') {
    $display_invoice_number = $_GET['display_invoice_number'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './include/header.php' ?>
    <style>
        /* .button-set p {
            font-size: 12px;
            margin-top: -4px !important;
        }

        .button-set i {
            font-size: 10px;
        }

        .button-set button {
            padding: 0px 8px 3px 8px;
        }

        .list-group a i {
            font-size: 15px !important;
        }

        .list-group a {
            padding: 8px;
            font-size: 12px !important;
        } */
    </style>
</head>

<body>
    <!-- Onsite Parameters -->
    <input type="hidden" value="<?php echo $display_invoice_number; ?>" id="LastInvoiceStatus" name="LastInvoiceStatus">
    <input type="hidden" value="<?php echo $session_student_number; ?>" id="LoggedUser" name="LoggedUser">
    <input type="hidden" value="<?php echo $session_user_level; ?>" id="UserLevel" name="UserLevel">
    <input type="hidden" value="<?php echo $company_id; ?>" id="company_id" name="company_id">
    <input type="hidden" value="<?php echo $LocationID; ?>" id="LocationID" name="LocationID">

    <div class="container-fluid w-100">
        <div class="row pos-content  g-2" id="wrapper">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="top-bar p-2">
                            <div class="row">
                                <div class="col-4 col-md-2">
                                    <img src="./assets/images/pos-logo.png" style="height: 40px">
                                    <!-- <h4><?= $CompanyName ?></h4> -->
                                </div>
                                <div class="col-8 col-md-10 text-end button-set" id="button-set" style="display: flex-box;justify-content: space-between;">

                                    <button type="button" class="btn btn-sm btn-warning mr-2  text-white d-none d-lg-inline ">
                                        <i class="fa-solid fa-right-left m-0"></i>
                                        <p class="m-0">Return</p>
                                    </button>
                                    <button type="button" class="btn btn-sm  btn-danger mr-2  d-none d-lg-inline "><i class="fa-solid fa-money-bill-trend-up"></i>
                                        <p class="mb-0">Refund</p>
                                    </button>
                                    <button type="button" class="btn btn-sm  btn-secondary mr-2  d-none d-lg-inline "><i class="fa-solid fa-gift"></i>
                                        <p class="mb-0">Gift</p>
                                    </button>
                                    <button type="button" class="btn btn-sm  btn-success mr-2  d-none d-lg-inline "><i class="fa-solid fa-money-bill"></i>
                                        <p class="mb-0">Expenses</p>
                                    </button>
                                    <button onclick="AddCustomer()" type="button" class="btn btn-sm  btn-primary mr-2   d-none d-lg-inline "><i class="fa-solid fa-user-plus"></i>
                                        <p class="mb-0">Customer</p>
                                    </button>
                                    <button onclick="OpenSetting()" type="button" class="btn btn-sm  btn-dark mr-2  d-none d-lg-inline "><i class="fa-solid fa-gear"></i>
                                        <p class="mb-0">Setting</p>
                                    </button>
                                    <button type="button" id="scrollToBottomButton" class="btn btn-sm  refresh-button mr-2  d-none d-lg-inline "><i class="fa-solid fa-arrows-down-to-line"></i>
                                        <p class="mb-0">Down</p>
                                    </button>
                                    <button type="button" id="scrollToTopButton" class="btn btn-sm  refresh-button mr-2  d-none d-lg-inline "><i class="fa-solid fa-arrows-up-to-line"></i>
                                        <p class="mb-0">Up</p>
                                    </button>
                                    <button type="button" class="btn btn-sm  select-table-button " onclick="SetTable()"><i class="fa-solid fa-table btn-icon"></i>
                                        <p class="mb-0">Select Table</p>
                                    </button>
                                    <button type="button" onclick="OpenIndex()" class="btn btn-sm  refresh-button mr-2 "><i class="fa-solid fa-arrows-rotate"></i>
                                        <p class="mb-0">New</p>
                                    </button>
                                    <button type="button" onclick="OpenButtonSet()" class="btn btn-sm btn-dark mr-2 px-3"><i class="fa-solid fa-grip"></i></i>
                                        <p class="mb-0">All</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-12 d-md-flex">
                        <!-- Sidebar -->
                        <div class="border-right d-none d-md-flex" id="sidebar" style="margin-top: 70px;">
                            <div class="list-group list-group-flush">
                                <a href="./?last_invoice=false&display_invoice_number=0&location_id=<?= $LocationID ?>" class="list-group-item list-group-item-action text-center">
                                    <i class="fa-solid fa-house fs-3 d-block mx-auto"></i>
                                    POS
                                </a>

                                <a href="#" onclick="GetTodaySales()" class="list-group-item list-group-item-action text-center">
                                    <i class="fa-solid fa-cash-register fs-3  d-block mx-auto"></i>
                                    Sales
                                </a>

                                <a href="./logout" class="list-group-item list-group-item-action text-center">
                                    <i class="fa-solid fa-right-from-bracket fs-3  d-block mx-auto"></i>
                                    Logout
                                </a>

                                <a href="#" class="list-group-item list-group-item-action text-center" onclick="PromptCloseApp(1)">
                                    <i class="fa-solid fa-power-off fs-3  d-block mx-auto"></i>
                                    Exit
                                </a>

                            </div>
                        </div>


                        <!-- Page Content -->
                        <div class="container-fluid  g-2 pb-4 mt-3" id="index-content">
                        </div>

                    </div>
                </div>



                <?php include './include/footer.php' ?>

                <script src="./assets/js/index-1.7.js"></script>
                <script type="text/javascript" src="./assets/js/qz-tray.js"></script>
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
            </div>
        </div>
    </div>
</body>

</html>