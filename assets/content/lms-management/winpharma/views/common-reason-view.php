<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/lms-functions.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$UserLevel = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : 'Officer';
$userTheme = getUserTheme($userThemeInput);

include_once '../classes/LmsDatabase.php';
include_once '../classes/WinpharmaReasons.php';

$LoggedUser = $_POST['LoggedUser'];
// Create a new Database object with the path to the configuration file
$config_file = '../../../../../include/env.txt';
$database = new LmsDatabase($config_file);
$db = $database->getConnection();

// Create a new object
$WinpharmaReasons = new WinpharmaReasons($db);
$reasonList = $WinpharmaReasons->fetchAll();
?>

<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?> ">
    <div class="row">
        <div class="col-6">
            <h3 class="mb-0">Common Reasons</h3>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenCommonReasons()" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>
        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 text-end">
            <button type="button" class="btn btn-dark btn-sm" onclick="NewReason()"><i class="fa-solid fa-plus"></i> Add New</button>
            <div class="border-bottom mt-2"></div>
        </div>
        <div class="col-12">
            <?php if (!empty($reasonList)) : ?>
                <div class="table-responsive">
                    <table class="table table-hovered table-striped" id="reason-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reasonList as $reason) :

                                if ($reason['is_active'] == 1) {
                                    $activeStatus = 'Active';
                                    $statusBgColor = 'bg-primary';
                                } else {
                                    $activeStatus = 'Inactive';
                                    $statusBgColor = 'bg-danger';
                                } ?>
                                <tr>
                                    <td><?= $reason['id'] ?></td>
                                    <td><?= $reason['reason'] ?></td>
                                    <td>
                                        <div class="badge <?= $statusBgColor ?>"><?= $activeStatus ?></div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="NewReason('<?= $reason['id'] ?>')">Open</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="DeleteReason('<?= $reason['id'] ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-primary">No Reasons</div>
                <?php endif ?>
                </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#reason-table').DataTable();

    });
</script>