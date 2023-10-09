<?php
require_once('./include/config.php');
include './include/session.php';

$company_id = 1452254565;
$UserLevel = $session_user_level;
$StudentNumber = $session_student_number;
$LoggedStudent = GetAccounts($link)[$session_student_number];
$LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
?>

<input type="hidden" value="<?php echo $session_student_number; ?>" id="LoggedUser" name="LoggedUser">
<input type="hidden" value="<?php echo $session_user_level; ?>" id="UserLevel" name="UserLevel">
<input type="hidden" value="<?php echo $company_id; ?>" id="company_id" name="company_id">

<style>
    .submenu {
        display: none;
        padding-left: 20px;
    }

    .submenu li {
        list-style: none;
    }

    .submenu-item {
        display: flex;
        align-items: center;
        padding: 8px;
    }

    .submenu-icon {
        margin-right: 10px;
    }

    .collapse-icon {
        margin-left: auto;
        margin-right: 10px;
        /* Add margin to create spacing between link text and icon */
    }

    .nav-link {
        display: flex;
        align-items: center;
    }

    .submenu .nav-item .active {
        background-color: #328bed !important;
    }

    .swal2-html-container input {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .logged-details {
        padding: 10px;
        border-bottom: 1px solid grey;
    }

    .logged-details p {
        font-weight: 500;
        margin-bottom: 0px;
    }

    .logged-details .hero-title-bar {
        border-bottom: none !important;
    }

    .logged-details .sidebar-profile-image {
        width: 50px;
    }

    .clickable {
        cursor: pointer;
    }



    #index-content {
        margin-bottom: 15px;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css">
<!-- 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" /> -->


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" /> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />



<!-- Favicons -->
<link href="./assets/images/favicon/apple-touch-icon.png" rel="icon">
<link href="./assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">
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
                <a href="./"><i class="fa-solid fa-home text-white clickable"></i></a> <a href="./logout.php" class="mx-1"><i class="fa-solid text-white fa-right-from-bracket"></i></a> <span class="badge bg-danger mt-2"><?= $UserLevel ?></span>
            </div>
        </div>

    </div>
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <ul class="nav flex-column nav-menu">
            <?php if ($UserLevel == "Admin" || $UserLevel == "Editor") { ?>

                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-chalkboard menu-icon"></i>
                        Master

                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./product">
                                <i class="fa-brands fa-product-hunt menu-icon"></i>
                                Product
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./location">
                                <i class="fa-solid fa-location-dot menu-icon"></i>
                                Location
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./supplier">
                                <i class="fa-solid fa-building menu-icon"></i>
                                Supplier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./section">
                                <i class="fa-solid fa-bookmark menu-icon"></i>
                                Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./department">
                                <i class="fa-solid fa-city menu-icon"></i>
                                Department
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./category">
                                <i class="fa-solid fa-briefcase menu-icon"></i>
                                Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./unit-of-measurement">
                                <i class="fa-solid  fa-weight-hanging menu-icon"></i>
                                Unit of Measurement
                            </a>
                        </li>

                    </ul>
                </li>
            <?php } ?>

            <?php if ($UserLevel == "Admin" || $UserLevel == "Editor") { ?>

                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                        <i class="fa-solid fa-list-check menu-icon"></i>
                        Transaction

                        <i class="fas fa-chevron-right collapse-icon"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./purchase-order">
                                <i class="fa-solid fa-file-contract menu-icon"></i>
                                Purchase Order
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./good-receive-note">
                                <i class="fa-solid fa-file-arrow-down menu-icon"></i>
                                Good Receive Note
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./invoice">
                                <i class="fa-solid fa-newspaper menu-icon"></i>
                                Invoice
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./stock-transfer">
                                <i class="fa-solid fa-newspaper menu-icon"></i>
                                Stock Transfer
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link submenu-item" href="./production-note">
                                <i class="fa-solid fa-newspaper menu-icon"></i>
                                Production Note
                            </a>
                        </li>

                    </ul>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                    <i class="fa-solid fa-user-group menu-icon"></i>
                    Pay Roll
                    <i class="fas fa-chevron-right collapse-icon"></i>
                </a>
                <ul class="submenu">
                    <li class="nav-item">
                        <a class="nav-link" href="./employee-registration">
                            <i class="fa-solid fa-user menu-icon"></i>
                            Employee Registration
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                    <i class="fa-solid fa-users-gear menu-icon"></i>
                    User Maintenance
                    <i class="fas fa-chevron-right collapse-icon"></i>
                </a>
                <ul class="submenu">
                    <?php if ($UserLevel == "Admin" || $UserLevel == "Editor") { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./user-maintenance">
                                <i class="fa-solid fa-children menu-icon"></i>
                                Users
                            </a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./profile">
                            <i class="fa-solid fa-user menu-icon"></i>
                            Profile
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./reset-password">
                            <i class="fa-solid fa-key menu-icon"></i>
                            Reset Password
                        </a>
                    </li>

                </ul>
            </li>
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />