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
$LocationID = 1;
$LastInvoiceID = 0;
if (isset($_GET['last_invoice']) && $_GET['last_invoice'] === 'true') {
    $display_invoice_number = $_GET['display_invoice_number'];
}

$sub_total = $total = 0;

$discount_amount = 0;
$location_id  = 1;
$charge_status = 1;


$Locations = GetLocations($link);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './include/header.php' ?>
    <link rel="stylesheet" href="./assets/css/customer-display-1.0.css">
    <title>Customer Display - PayshiaPOS</title>
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
    <div class="container" style="margin-top:80px" id="index-content">

        <div class="row mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <h5>Set Location</h5>
                    </div>
                    <div class="row">
                        <?php
                        if (!empty($Locations)) {
                            foreach ($Locations as $Location) {
                                $location_id = $Location['location_id'];
                                $location_name = $Location['location_name'];
                                $active_status = "Deleted";
                                $color = "warning";
                                if ($Location['is_active'] == 1) {
                                    $active_status = "Active";
                                    $color = "primary";
                                } else {
                                    continue;
                                }

                                if ($Location['pos_status'] != 1) {
                                    continue;
                                }

                        ?>
                                <div class="col-6 col-md-6 col-lg-4 mb-3 d-flex">
                                    <div class="card flex-fill clickable table-card" onclick="openURL('./?last_invoice=true&display_invoice_number=0&location_id=<?= $location_id ?>')">
                                        <div class="card-body p-2 pb-2">
                                            <span class="badge mt-2 bg-<?= $color ?>"><?= $active_status ?></span>
                                            <h1 class="tutor-name mt-2"><?= $location_name ?></h1>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">No Entires</p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
        <div id="date-time"></div>
        <div class="credit-text">Powered By Payshia.com</div>
    </div>




    <div class="popup" id="notification"></div>

    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script>
        function openURL(url) {
            // Open the URL in a new window
            window.location.href = url;
        }
    </script>

</body>

</html>