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

include_once './assets/content/super-admin/classes/Database.php';
include_once './assets/content/super-admin/classes/Pages.php';

// Create a new Database object with the path to the configuration file
$config_file = './include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$pages = new Pages($database);


// Read JSON file
$jsonData = file_get_contents('./include/strings.json');

// Decode JSON data
$arrays = json_decode($jsonData, true);

$commonModules = $arrays['commonMenuRoots'];
$organizationRoots = $arrays['organizationRoots'];

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$current_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
                <a href="./logout?return_url=<?= urlencode($current_url) ?>" class="mx-1"><i class="fa-solid text-white fa-right-from-bracket"></i></a>
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

            <?php
            $catchAccess = 1;
            foreach ($commonModules as $module) :

                // Fetch pages for the current module
                $pageList = $pages->fetchByCategories($module[0]);

                // Fetch user privileges for the current module
                $userPrivilege = GetUserPrivileges($link, $session_student_number, $module[0]);

                // Check if read access is granted for the module
                $readAccess = (!empty($userPrivilege) && $userPrivilege[$session_student_number]['read'] == 1);

                if ($readAccess) :
                    $catchAccess = 0;
            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-<?= $module[1] ?> menu-icon"></i>
                            <?= $module[0] ?>
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php foreach ($pageList as $page) :
                                $pageId = $page['id'];

                                // Fetch user privileges for the current page
                                $pagePrivilege = (!empty($userPrivilege) ? GetUserPrivileges($link, $session_student_number, $pageId) : null);

                                // Check if read access is granted for the page
                                $readAccessPage = (!empty($pagePrivilege) && $pagePrivilege[$session_student_number]['read'] == 1);

                                if ($readAccessPage) :
                            ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./<?= $page['page_name'] ?>" target="<?= $page['open_type'] ?>">
                                            <i class="fa-solid fa-<?= $page['pack_icon'] ?> menu-icon"></i>
                                            <?= $page['display_name'] ?>
                                        </a>
                                    </li>
                            <?php endif;
                            endforeach; ?>
                        </ul>
                    </li>
            <?php
                endif;
            endforeach;
            ?>

            <?php if ($catchAccess == 1) : ?>
                <div class="alert alert-warning">Not Permitted to any Common Module</div>
            <?php endif ?>

            <div class="row">
                <div class="col-12 px-3 mt-4">
                    <h6 class="text-light">Organization</h6>
                    <div class="border-top border-secondary mt-2"></div>
                </div>
            </div>

            <?php
            $catchAccess = 1;
            foreach ($organizationRoots as $module) :

                $pageList = $pages->fetchByCategories($module[0]);

                // Fetch user privileges for the current module
                $userPrivilege = GetUserPrivileges($link, $session_student_number, $module[0]);

                // Check if read access is granted for the module
                $readAccess = (!empty($userPrivilege) && $userPrivilege[$session_student_number]['read'] == 1);

                if ($readAccess) :
                    $catchAccess = 0;
            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSubmenu(event)">
                            <i class="fa-solid fa-<?= $module[1] ?> menu-icon"></i>
                            <?= $module[0] ?>
                            <i class="fas fa-chevron-right collapse-icon"></i>
                        </a>
                        <ul class="submenu">
                            <?php foreach ($pageList as $page) :
                                $pageId = $page['id'];
                                $userPrivilege = GetUserPrivileges($link, $session_student_number, $pageId);

                                if (!empty($userPrivilege)) :
                                    $readAccess = $userPrivilege[$session_student_number]['read'];
                                    $writeAccess = $userPrivilege[$session_student_number]['write'];
                                    $AllAccess = $userPrivilege[$session_student_number]['all'];

                                    if ($readAccess == 1) :
                            ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./<?= $page['page_name'] ?>" target="<?= $page['open_type'] ?>">
                                                <i class="fa-solid fa-<?= $page['pack_icon'] ?> menu-icon"></i>
                                                <?= $page['display_name'] ?>
                                            </a>
                                        </li>
                            <?php endif;
                                endif;
                            endforeach; ?>
                        </ul>
                    </li>
            <?php endif;
            endforeach ?>

            <?php if ($catchAccess == 1) : ?>
                <div class="alert alert-warning">Not Permitted to any Organizational Module</div>
            <?php endif ?>
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
    <div class="text-center">
        <div class="card-body p-5 my-5">
            <img src="./assets/images/inner-loader.svg" alt="">
        </div>
    </div>
</div>