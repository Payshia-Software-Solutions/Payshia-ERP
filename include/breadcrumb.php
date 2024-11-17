<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$current_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<div class="row">
    <div class="col-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="./"><i class="fa-solid fa-home top-icon <?= $iconColor ?>"></i></a>
                </li>
                <?php if ($SubPage) { ?>
                    <li class="breadcrumb-item">
                        <a href="./class"><?= $pageTitle ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <?= $SubPageTitle ?>
                    </li>
                <?php } else { ?>
                    <li class="breadcrumb-item">
                        <?= $pageTitle ?>
                    </li>
                <?php } ?>

            </ol>
        </nav>
    </div>
    <div class="col-6 text-end">
        <!-- <input type="text" class="d-none d-md-inline top-search" placeholder="Search Here.."> -->
        <button class="btn top-buttons" type="button" onclick="OpenIndex()">
            <i class="fa-solid fa-arrow-left top-icon <?= $iconColor ?>"></i>
        </button>
        <!-- <button class="btn top-buttons" type="button" onclick="OpenInnerPage()">
            <i class="fa-solid fa-rotate top-icon"></i>
        </button> -->
        <!-- <button class="btn top-buttons" type="button">
            <i class="fa-solid fa-bell top-icon"></i>
        </button> -->

        <button class="btn top-buttons" type="button">
            <i class="fa-solid fa-user top-icon <?= $iconColor ?>"></i>
        </button>
        <button class="d-md-inline  d-md-none top-buttons navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars top-icon <?= $iconColor ?>"></i>
        </button>
    </div>

    <div class="profile-container">
        <ul class="nav-links">
            <!-- <li class="nav-item"><a href="./profile"><i class="fa-solid fa-circle-user top-icon"></i>Profile</a></li> -->
            <li class="nav-item" onclick="ChoiceUserLocation('<?= $StudentNumber ?>', 1)"><a href="#"><i class="fa-solid fa-location-dot top-icon"></i>Change Location</a></li>
            <li class="nav-item"><a href="./logout?return_url=<?= urlencode($current_url) ?>"><i class="fa-solid fa-right-from-bracket top-icon"></i>Sign Out</a></li>
        </ul>
    </div>

    <div class="notification-container">
        <ul class="nav-links">
            <li class="nav-item"><a href=""><i class="fa-solid fa-user-gear top-icon"></i>New User
                    Logged in to system</a></li>
            <li class="nav-item"><a href=""><i class="fa-solid fa-user-gear top-icon"></i>New User
                    Logged in to system</a></li>
        </ul>
    </div>
</div>