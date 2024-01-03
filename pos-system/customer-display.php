<?php
require_once('../include/config.php');
include '../include/session.php';
include '../include/function-update.php';

$scaleFactor = 100;
$Locations = GetLocations($link);
$display_invoice_number = 0;
$company_id = 1452254565;
$UserLevel = $session_user_level;
$StudentNumber = $session_student_number;
$LoggedStudent = GetAccounts($link)[$session_student_number];
$LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
$pageTitle = "Customer Display";
$SubPageTitle = "";
$SubPage = false;
$CompanyName = "PayshiaPOS";
$LocationID = 1;
$LastInvoiceID = 0;
if (isset($_GET['last_invoice']) && $_GET['last_invoice'] === 'true') {
    $display_invoice_number = $_GET['display_invoice_number'];
}

$sub_total = $total = 0;

$discount_amount = 0;
$location_id  = 1;
$charge_status = 1;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './include/header.php' ?>
    <link rel="stylesheet" href="./assets/css/customer-display-1.0.css">
</head>

<body>
    <!-- Onsite Parameters -->
    <input type="hidden" value="<?php echo $display_invoice_number; ?>" id="LastInvoiceStatus" name="LastInvoiceStatus">
    <input type="hidden" value="<?php echo $session_student_number; ?>" id="LoggedUser" name="LoggedUser">
    <input type="hidden" value="<?php echo $session_user_level; ?>" id="UserLevel" name="UserLevel">
    <input type="hidden" value="<?php echo $company_id; ?>" id="company_id" name="company_id">
    <input type="hidden" value="<?php echo $LocationID; ?>" id="LocationID" name="LocationID">

    <div class="row">
        <div class="col-12">
            <div class="top-bar p-2">
                <div class="row">
                    <div class="col-12 text-center">
                        <img src="./assets/images/pos-logo.png" style="height: 50px">
                        <!-- <h4><?= $CompanyName ?></h4> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:80px" id="index-content"></div>

    <script src="./assets/js/qty-selector.js"></script>

    <!-- Preloader -->
    <!-- <div id="preloader">
    <div id="filler"></div>
</div> -->
    <style>
        .footer-credit {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 999 !important;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            /* This will push the date-time to the left and credit to the right. */
            padding: 10px;
        }

        #date-time {
            margin: 0;
            font-weight: 700;
            padding: 0;
        }

        .credit-text {
            font-weight: 700;
        }

        #logged-user span {
            font-weight: 700;
            margin-left: 5px;
        }
    </style>

    <div class="footer-credit">
        <div id="logged-user"><i class="fa-solid fa-user"></i> <span><?= $LoggedName ?></span></div>
        <div id="logged-user"><i class="fa-solid fa-location-dot"></i> <span><?= $Locations[$LocationID]['location_name'] ?></span></div>
        <div id="date-time"></div>
        <div class="credit-text">Powered By uni-erp.com</div>
    </div>




    <div class="popup" id="notification"></div>

    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./assets/js/customer-display-1.0.js"></script>

</body>

</html>