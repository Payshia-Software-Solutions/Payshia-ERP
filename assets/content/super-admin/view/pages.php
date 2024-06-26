<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$userTheme = getUserTheme($userThemeInput);

include_once '../classes/Database.php';
include_once '../classes/Pages.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$pages = new Pages($database);

$page_list = $pages->fetchAll();
?>

<div class="loading-popup-content-right <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-6">
            <h3 class="mb-0">Pages</h3>
        </div>

        <div class="col-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="OpenPages()" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUPRight(1)" type="button"><i class="fa solid fa-xmark"></i> Cancel</button>
            <button class="btn btn-success btn-sm" onclick="viewPageInfo()" type="button"><i class="fa solid fa-plus"></i> Add New Page</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-5 my-2"></div>
        </div>


    </div>

    <div class="row mb-5">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="page-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Page Name</th>
                                    <th>Url</th>
                                    <th>icon</th>
                                    <th>Open Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($page_list as $page) : ?>
                                    <tr>
                                        <td><?= $page['id'] ?></td>
                                        <td><?= $page['display_name'] ?></td>
                                        <td>/<?= $page['page_name'] ?></td>
                                        <td class="text-center"><i class="fa solid <?= $page['pack_icon'] ?>"></i></td>
                                        <td><?= $page['open_type'] ?></td>
                                        <td><button onclick="viewPageInfo('<?= $page['id'] ?>')" type="button" class="btn btn-dark btn-sm">Edit</button></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#page-table').DataTable({
            pageLength: 50
        });

    });
</script>