<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
$userName = $_POST['userName'];
$selectedUser = GetAccountByID($link, $userName);
$pageTable =  getPageTable($link);

$activeSection = $_POST['activeSection'];
$Locations = GetLocations($link);

$jsonData = file_get_contents('../../../include/strings.json'); // Read JSON file
$arrays = json_decode($jsonData, true); // Decode JSON data

$commonRoots = convertSelectBox2DArray($arrays['commonMenuRoots'], 0, 0);
$organizationRoots = convertSelectBox2DArray($arrays['organizationRoots'], 0, 0);

// Combine arrays
$combinedArrays = array_merge($commonRoots, $organizationRoots);

// Remove duplicates and re-index the array
$rootValues = array_values(array_unique($combinedArrays, SORT_REGULAR));

?>

<div class="row">
    <div class="col-12 mb-3">
        <h4 class="border-bottom pb-2">User Details</h4>
        <p class="mb-0">Username : <?= $userName ?></p>
        <p class="mb-0">Name : <?= $selectedUser['first_name'] ?> <?= $selectedUser['last_name'] ?></p>
    </div>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link <?= ($activeSection == "root") ? "active" : "" ?>" id="nav-root-tab" data-bs-toggle="tab" data-bs-target="#nav-root" type="button" role="tab" aria-controls="nav-root" aria-selected="true">Roots</button>
        <button class="nav-link <?= ($activeSection == "pages") ? "active" : "" ?>" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Pages</button>
        <button class="nav-link <?= ($activeSection == "report") ? "active" : "" ?>" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Reports</button>
        <button class="nav-link <?= ($activeSection == "branch") ? "active" : "" ?>" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Branches</button>
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade <?= ($activeSection == "root") ? "show active" : "" ?>" id="nav-root" role="tabpanel" aria-labelledby="nav-root-tab" tabindex="0">
        <div class="row mt-3">
            <div class="col-12">
                <h4 class="border-bottom pb-2">Roots</h4>
            </div>

            <?php
            if (!empty($rootValues)) {
                foreach ($rootValues as $selectedArray) {

                    $readAccess = 0;
                    $userPrivilege = GetUserPrivileges($link, $userName,  $selectedArray['value']);

                    if (!empty($userPrivilege)) {
                        $readAccess = $userPrivilege[$userName]['read'];
                    }
            ?>
                    <div class="col-md-4">
                        <div class="form-check form-switch">
                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['value'] ?>', 'read', 'root')" class="form-check-input" <?= ($readAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                            <label class="form-check-label" for="itemImageSetting"><?= $selectedArray['value'] ?></label>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="tab-pane fade  <?= ($activeSection == "pages") ? "show active" : "" ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="row mt-3">
            <div class="col-12">
                <h4 class="border-bottom pb-2 mb-0">Pages</h4>
            </div>
            <div class="col-12">
                <table class="table table-hover table-bordered mt-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Read</th>
                            <th>Write</th>
                            <!-- <th>Update</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($pageTable)) {
                            foreach ($pageTable as $selectedArray) {

                                if ($selectedArray['type'] == "report") {
                                    continue;
                                }

                                $readAccess = $writeAccess = $AllAccess = 0;
                                $userPrivilege = GetUserPrivileges($link, $userName,  $selectedArray['id']);

                                if (!empty($userPrivilege)) {
                                    $readAccess = $userPrivilege[$userName]['read'];
                                    $writeAccess = $userPrivilege[$userName]['write'];
                                    $AllAccess = $userPrivilege[$userName]['all'];
                                }

                        ?>
                                <tr>
                                    <td><label class="form-check-label" for="itemImageSetting"><?= $selectedArray['page_name'] ?></label></td>
                                    <td><label class="form-check-label" for="itemImageSetting"><?= $selectedArray['root'] ?></label></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'read', 'pages')" class="form-check-input" <?= ($readAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'write', 'pages')" class="form-check-input" <?= ($writeAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'all', 'pages')" class="form-check-input" <?= ($AllAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td> -->
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade <?= ($activeSection == "report") ? "show active" : "" ?>" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
        <div class="row mt-3">
            <div class="col-12">
                <h4 class="border-bottom pb-2 mb-0">Reports</h4>
            </div>
            <div class="col-12">
                <table class="table table-hover table-bordered mt-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Read</th>
                            <!-- <th>Write</th> -->
                            <!-- <th>Update</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($pageTable)) {
                            foreach ($pageTable as $selectedArray) {

                                if ($selectedArray['type'] != "report") {
                                    continue;
                                }
                                $accessMode = 1;
                                $readAccess = $writeAccess = $AllAccess = 0;
                                $userPrivilege = GetUserPrivileges($link, $userName,  $selectedArray['id']);

                                if (!empty($userPrivilege)) {
                                    $readAccess = $userPrivilege[$userName]['read'];
                                    $writeAccess = $userPrivilege[$userName]['write'];
                                    $AllAccess = $userPrivilege[$userName]['all'];
                                }

                        ?>
                                <tr>
                                    <td><label class="form-check-label" for="itemImageSetting"><?= $selectedArray['page_name'] ?></label></td>
                                    <td><label class="form-check-label" for="itemImageSetting"><?= $selectedArray['root'] ?></label></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'read', 'report')" class="form-check-input" <?= ($readAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'write', 'report')" class="form-check-input" <?= ($writeAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="form-check form-switch">
                                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['id'] ?>', 'all', 'report')" class="form-check-input" <?= ($AllAccess == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                                        </div>
                                    </td> -->
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>





        </div>
    </div>
    <div class="tab-pane fade <?= ($activeSection == "branch") ? "show active" : "" ?>" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
        <div class="row mt-3">
            <div class="col-12">
                <h4 class="border-bottom pb-2">Branches</h4>
            </div>

            <?php
            if (!empty($Locations)) {
                foreach ($Locations as $selectedArray) {
                    $accessMode = 1;
            ?>
                    <div class="col-md-4">
                        <div class="form-check form-switch">
                            <input onclick="UpdatePrivilege('<?= $userName ?>', '<?= $selectedArray['location_id'] ?>', 'all', 'branch')" class="form-check-input" <?= ($accessMode == 1) ? 'checked' : '' ?> type="checkbox" role="switch" style="width: 35px !important;">
                            <label class="form-check-label" for="itemImageSetting"><?= $selectedArray['location_name'] ?></label>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>








</div>