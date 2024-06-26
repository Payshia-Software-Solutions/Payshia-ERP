<?php
require_once('./include/config.php');
include './include/session.php';
include './include/settings_functions.php';

$defaultLocation = $default_location_name = null;
$company_id = 1452254565;
$UserLevel = $session_user_level;
$StudentNumber = $session_student_number;
$LoggedStudent = GetAccounts($link)[$session_student_number];
$LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];

$Locations = GetLocations($link);
$defaultLocation = GetUserDefaultValue($link, $session_student_number, 'defaultLocation');
if (isset($defaultLocation) && $defaultLocation != "") {
    $default_location_name = $Locations[$defaultLocation]['location_name'];
}

$displayGroup = 0;
$userPrivilege = array();

?>
<input type="hidden" value="<?php echo $session_student_number; ?>" id="LoggedUser" name="LoggedUser">
<input type="hidden" value="<?php echo $session_user_level; ?>" id="UserLevel" name="UserLevel">
<input type="hidden" value="<?php echo $company_id; ?>" id="company_id" name="company_id">
<input type="hidden" value="<?php echo $defaultLocation; ?>" id="default_location" name="default_location">
<input type="hidden" value="<?php echo $default_location_name; ?>" id="default_location_name" name="default_location_name">
<input type="hidden" value="" id="deviceFingerPrint" name="deviceFingerPrint">

<input type="hidden" value="<?= $modeTheme ?>" id="userTheme" name="userTheme">


<div class="overlay">
    <div class="overlay-content text-center">
        <div class="card-body p-5 my-5">
            <img src="./assets/images/loader.svg" alt="">
            <p class="mb-0">Please Wait...</p>
        </div>
    </div>
</div>
<nav class="d-none d-md-block col-md-3 col-lg-2 sidebar">
    <div class="position-sticky">

        <div class="sidebar-header"></div>

        <div class="hero-title-bar">
            <h2 class="hero-title"><?= $SiteTitle ?></h2>
            <button class="btn hide-button navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-down-left-and-up-right-to-center top-icon"></i>
            </button>

        </div>
    </div>
    <div class="logged-details">
        <div class="row">
            <div class="col-3">
                <img src="./assets/images/student/<?= $LoggedStudent['img_path'] ?>" class="sidebar-profile-image" style="border-radius: 50%;">
            </div>
            <div class="col-9">
                <p class="class-fee text-light"><?= $LoggedName ?></p>
                <a href="./"><i class="fa-solid fa-home text-white clickable"></i></a>
                <a href="./pos-system" target="_blank" class="mx-1"><i class="fa-solid fa-cash-register text-white clickable"></i></a>
                <a href="./logout.php" class="mx-1"><i class="fa-solid text-white fa-right-from-bracket"></i></a>
                <span class="badge bg-danger mt-2"><?= $UserLevel ?></span>
                <i class="fa-solid fa-circle-half-stroke clickable text-light" onclick="toggleDarkMode()"></i>
            </div>
            <div class="col-12 mb-1">
                <p class="bg-success p-1 rounded-3 text-center text-white mt-3 clickable" onclick="ChoiceUserLocation('<?= $StudentNumber ?>', 1)"><i class="fa-solid px-1 fa-location-dot"></i> <?= $default_location_name ?></p>
            </div>
        </div>

    </div>
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <ul class="nav flex-column nav-menu">

            <div class="row">
                <div class="col-12 px-3 mt-2">
                    <h6 class="text-light">Common Modules</h6>
                    <div class="border-top border-secondary mt-2"></div>
                </div>
            </div>



            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") {
                $displayGroup = 0;
                $pageIdArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-chalkboard menu-icon"></i>
                            Master
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 1;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./product">
                                            <i class="fa-brands fa-product-hunt menu-icon"></i>
                                            Product/Service
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 3;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./location">
                                            <i class="fa-solid fa-location-dot menu-icon"></i>
                                            Location
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 4;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./supplier">
                                            <i class="fa-solid fa-building menu-icon"></i>
                                            Supplier
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 5;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./section">
                                            <i class="fa-solid fa-bookmark menu-icon"></i>
                                            Section
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 6;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./department">
                                            <i class="fa-solid fa-city menu-icon"></i>
                                            Department
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 7;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./category">
                                            <i class="fa-solid fa-briefcase menu-icon"></i>
                                            Category
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 8;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./unit-of-measurement">
                                            <i class="fa-solid  fa-weight-hanging menu-icon"></i>
                                            Unit of Measurement
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                            <?php
                            $pageID = 9;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./table">
                                            <i class="fa-solid  fa-table menu-icon"></i>
                                            Tables
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <li class="nav-item">
                                <a class="nav-link" href="./printers">
                                    <i class="fa-solid fa-print menu-icon"></i>
                                    Printers
                                </a>
                            </li>

                        </ul>
                    </li>
            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") {
                $displayGroup = 0;
                $pageIdArray = [11, 12, 13, 28, 14, 15, 16];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-list-check menu-icon"></i>
                            Transaction

                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 10;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./purchase-order">
                                            <i class="fa-solid fa-file-contract menu-icon"></i>
                                            Purchase Order
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 11;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./good-receive-note">
                                            <i class="fa-solid fa-file-arrow-down menu-icon"></i>
                                            Good Receive Note
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 12;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./recipe">
                                            <i class="fa-solid fa-right-left menu-icon"></i>
                                            Bill of Materials
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                            <?php
                            $pageID = 13;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./invoice">
                                            <i class="fa-solid fa-file-invoice menu-icon"></i>
                                            Invoice
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 28;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./receipt">

                                            <i class="fa-solid fa-receipt menu-icon"></i>
                                            Receipt
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 14;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./quotation">
                                            <i class="fa-solid fa-receipt menu-icon"></i>
                                            Quotation
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 15;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>

                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./batch-production">

                                            <i class="fa-solid  fa-tarp-droplet menu-icon"></i>
                                            Production Sheet
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./production-note">

                                            <i class="fa-solid  fa-tarp-droplet menu-icon"></i>
                                            Production Note
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                            <?php
                            $pageID = 16;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-item" href="./pos-system">
                                            <i class="fa-solid fa-cash-register menu-icon"></i>
                                            POS System
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") {
                $displayGroup = 0;
                $pageIdArray = [17];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-landmark menu-icon"></i>
                            Accounts
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 17;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./chart-of-accounts">
                                            <i class="fa-solid fa-chart-bar menu-icon"></i>
                                            Chart of Accounts
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>

            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin") { ?>


                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-percent menu-icon"></i>
                        Promotions <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">

                        <li class="nav-item">
                            <a class="nav-link" href="seasonal-offers">
                                <i class="fa-solid fa-clock menu-icon"></i>
                                Timed Discounts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-business-time menu-icon"></i>
                                Item Discounts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-socks menu-icon"></i>
                                BOGO Type
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-ticket menu-icon"></i>
                                Coupons Codes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-user-tie menu-icon"></i>
                                Employee Discounts
                            </a>
                        </li>
                    </ul>
                </li>

            <?php } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer" || $UserLevel == "Cashier") {
                $displayGroup = 0;
                $pageIdArray = [18];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-file-lines menu-icon"></i>
                            Reports
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 18;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./reports">
                                            <i class="fa-solid fa-file menu-icon"></i>
                                            ERP Reports
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") {
                $displayGroup = 0;
                $pageIdArray = [19];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-gear menu-icon"></i>
                            Administration
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 19;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./cancellation">
                                            <i class="fa-solid fa-wrench menu-icon"></i>
                                            Cancellation
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin") {
                $displayGroup = 0;
                $pageIdArray = [20, 21];
                foreach ($pageIdArray as $pageId) {
                    $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageId);


                    if (array_key_exists($session_student_number, $userPrivilege)) {
                        $readAccess = $userPrivilege[$session_student_number]['read'];
                        if ($readAccess == 1) {
                            $displayGroup = 1;
                            break;
                        }
                    }
                }
                if ($displayGroup == 1) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-users-gear menu-icon"></i>
                            User Maintenance
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php
                            $pageID = 20;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./user-maintenance">
                                            <i class="fa-solid fa-children menu-icon"></i>
                                            Users
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                            <?php
                            $pageID = 21;
                            $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                            if (!empty($userPrivilege)) {
                                $readAccess = $userPrivilege[$session_student_number]['read'];
                                $writeAccess = $userPrivilege[$session_student_number]['write'];
                                $AllAccess = $userPrivilege[$session_student_number]['all'];

                                if ($readAccess == 1) {
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./user-privileges">
                                            <i class="fa-solid fa-pen-nib menu-icon"></i>
                                            Change Privileges
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>

                        </ul>
                    </li>
            <?php endif;
            } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-brands fa-intercom menu-icon"></i>
                        CRM <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <?php
                        $pageID = 2;
                        $userPrivilege = GetUserPrivileges($link, $session_student_number,  $pageID);

                        if (!empty($userPrivilege)) {
                            $readAccess = $userPrivilege[$session_student_number]['read'];
                            $writeAccess = $userPrivilege[$session_student_number]['write'];
                            $AllAccess = $userPrivilege[$session_student_number]['all'];

                            if ($readAccess == 1) {
                        ?>
                                <li class="nav-item">
                                    <a class="nav-link submenu-item" href="./customer">
                                        <i class="fa-solid fa-person-military-pointing menu-icon"></i>
                                        Customer
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-building-user menu-icon"></i>
                        HRM
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="employee-management">
                                <i class="fa-solid fa-users menu-icon"></i>
                                Employees
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="employee-dashboard">
                                <i class="fa-solid fa-table-columns menu-icon"></i>
                                Employee Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-money-bill menu-icon"></i>
                                Salary
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-person-walking-arrow-right menu-icon"></i>
                                Leaves
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-building menu-icon"></i>
                                Departments
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <div class="row">
                <div class="col-12 px-3 mt-4">
                    <h6 class="text-light">Organization</h6>
                    <div class="border-top border-secondary mt-2"></div>
                </div>
            </div>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-building-wheat  menu-icon"></i>
                        Hotel Management <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-computer menu-icon"></i>
                                Front Desk
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-house menu-icon"></i>
                                Rooms
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-house-circle-check menu-icon"></i>
                                Housekeeping
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-hat-cowboy menu-icon"></i>
                                Room Types
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-percent menu-icon"></i>
                                Promo Code
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-truck menu-icon"></i>
                        Courier Portal <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="./courier-services">
                                <i class="fa-solid fa-list-ol menu-icon"></i>
                                Courier Services
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-rectangle-list menu-icon"></i>
                                Order Templates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-file-invoice menu-icon"></i>
                                Order Details
                            </a>
                        </li>


                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-graduation-cap menu-icon"></i>
                        LMS Management
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="./lms-control-center">
                                <i class="fa-solid fa-laptop-file menu-icon"></i>
                                Control Center
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="./lms-certification">
                                <i class="fa-solid fa-certificate menu-icon"></i>
                                LMS Certification
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="./lms-course-management">
                                <i class="fa-solid fa-award  menu-icon"></i>
                                Course Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./received-orders">
                                <i class="fa-solid fa-hard-drive  menu-icon"></i>
                                Delivery Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./lms-user-approval">
                                <i class="fa-solid fa-user-check  menu-icon"></i>
                                User Approval
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="./lms-report-view">
                                <i class="fa-solid fa-laptop-file menu-icon"></i>
                                LMS Reports
                            </a>
                        </li>



                    </ul>
                </li>

            <?php } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-car-side menu-icon"></i>
                        Vehicle Management <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-truck menu-icon"></i>
                                Vehicles
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-charging-station menu-icon"></i>
                                Orders
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Officer") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="./ticket-management">
                        <i class="fa-solid fa-ticket menu-icon"></i>
                        Ticket Management
                    </a>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-tags menu-icon"></i>
                        Label Printing
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="./print-labels">
                                <i class="fa-solid fa-user menu-icon"></i>
                                Print Labels
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin") { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-brands fa-webflow menu-icon"></i>
                        Website Management <span class="mx-2 badge bg-warning" style="font-size: 10px;">New</span>
                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-folder-tree  menu-icon"></i>
                                Content Management
                            </a>
                        </li>


                    </ul>
                </li>

            <?php } ?>


        </ul>
    </div>
</nav>

<!-- Preloader -->
<div id="inner-preloader-content" class="preloader-content">
    <div class=" text-center">
        <div class="card-body p-5 my-5">
            <img src="./assets/images/loader.svg" alt="">
            <p class="mb-0">Please Wait...</p>
        </div>
    </div>
</div>

<div id="component-preloader-content" class="preloader-content">
    <div class=" text-center">
        <div class="card-body p-5 my-5">
            <img src="./assets/images/inner-loader.svg" alt="">
        </div>
    </div>
</div>