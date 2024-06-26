<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

// Get User Theme
$userThemeInput = isset($_POST['userTheme']) ? $_POST['userTheme'] : null;
$userTheme = getUserTheme($userThemeInput);

$pageId = $_POST['pageId'];

include_once '../classes/Database.php';
include_once '../classes/Pages.php';
include_once '../classes/Icons.php';

// Create a new Database object with the path to the configuration file
$config_file = '../../../../include/env.txt';
$database = new Database($config_file);
$db = $database->getConnection();

$jsonData = file_get_contents('../../../../include/strings.json'); // Read JSON file
$arrays = json_decode($jsonData, true); // Decode JSON data
$openTypeValues = convertSelectBox1DArrayValueOnly($arrays['openTypes']);

$commonRoots = convertSelectBox2DArray($arrays['commonMenuRoots'], 0, 0);
$organizationRoots = convertSelectBox2DArray($arrays['organizationRoots'], 0, 0);

// Combine arrays
$combinedArrays = array_merge($commonRoots, $organizationRoots);

// Remove duplicates and re-index the array
$uniqueCombinedArrays = array_values(array_unique($combinedArrays, SORT_REGULAR));

$pages = new Pages($db);
$icons = new Icons($db);


if ($pageId != 0) {
    $page_info = $pages->fetchById($pageId);
}

$all_icons = $icons->fetchAll();

?>

<div class="loading-popup-content <?= htmlspecialchars($userTheme) ?>">
    <div class="row">

        <div class="col-md-6">
            <h4 class="mb-0"><?= ($pageId != 0) ? $page_info['display_name'] : 'New Page' ?></h4>
        </div>

        <div class="col-md-6 text-end">
            <button class="btn btn-dark btn-sm" onclick="viewPageInfo('<?= $pageId ?>')" type="button"><i class="fa solid fa-rotate-left"></i> Reload</button>
            <button class="btn btn-light btn-sm" onclick="ClosePopUP(0)" type="button"><i class="fa solid fa-xmark"></i> Close</button>
        </div>

        <div class="col-12">
            <div class="border-bottom border-2 my-2"></div>
        </div>
    </div>


    <form action="#" id="page-form" method="post">
        <div class="row g-2">
            <div class="col-md-2">
                <?php
                $ElementName = 'Page ID';
                $defaultValue = ($pageId != 0) ? $page_info['id'] : '';
                echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue, 'readonly')
                ?>
            </div>

            <div class="col-md-5">
                <?php
                $ElementName = 'Display Name';
                $defaultValue = ($pageId != 0) ? $page_info[convertToSnakeCase($ElementName)] : '';
                echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue, '')
                ?>
            </div>

            <div class="col-md-5">
                <?php
                $ElementName = 'Page Name';
                $defaultValue = ($pageId != 0) ? $page_info[convertToSnakeCase($ElementName)] : '';
                echo ReturnTextInput($ElementName, 'required', 'form-control', $defaultValue, '')
                ?>
            </div>



        </div>

        <div class="row g-2 mt-2">
            <div class="col-4">
                <?php
                $ElementName = 'Open Type';
                $defaultValue = ($pageId != 0) ? $page_info[convertToSnakeCase($ElementName)] : '';
                echo ReturnSelectInput($ElementName, 'required', 'form-control', $openTypeValues, $defaultValue)
                ?>
            </div>
            <div class="col-4">
                <?php
                $ElementName = 'Root';
                $defaultValue = ($pageId != 0) ? $page_info[convertToSnakeCase($ElementName)] : '';
                echo ReturnSelectInput($ElementName, 'required', 'form-control', $uniqueCombinedArrays, $defaultValue)
                ?>
            </div>
            <div class="col-4">
                <?php
                $ElementName = 'Pack Icon';
                $defaultValue = ($pageId != 0) ? $page_info[convertToSnakeCase($ElementName)] : '';
                echo ReturnTextInput($ElementName, 'required', 'form-control btn', $defaultValue, '')
                ?>
            </div>
            <div class="col-12 text-end">
                <button onclick="SavePageInfo('<?= $pageId ?>')" type="button" class="btn btn-dark btn-sm">Save Changes</button>
            </div>
        </div>

    </form>

</div>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        max-height: 100vh;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }


    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-height: 60%;
        max-width: 600px;
        position: relative;
    }

    /* .model-header {} */

    .model-body {
        overflow: auto;
        max-height: 50%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 20px;
    }

    .icon-grid i {
        font-size: 28px;
        cursor: pointer;
        text-align: center;
        padding: 10px 5px;
        border-radius: 8px;
    }


    .icon-grid i:hover {
        background-color: #dfe8e1;
    }
</style>
<div id="iconModal" class="modal">
    <div class="modal-content">
        <div class="model-header text-end mb-2">
            <span class="close">&times;</span>
            <input type="text" class="form-control" id="iconSearch" placeholder="Search icons...">
        </div>
        <div class="model-body">
            <div class="icon-grid">
                <?php foreach ($all_icons as $icon) : ?>
                    <i class="fa-<?= $icon['category'] ?> <?= $icon['icon_name'] ?>"></i>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("iconModal");

    // Get the button that opens the modal
    var btn = document.getElementById("pack_icon");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Add event listener to each icon
    document.querySelectorAll('.icon-grid i').forEach(function(icon) {
        icon.addEventListener('click', function() {
            // Your logic to handle icon selection
            btn.value = icon.className;
            // alert("Icon selected: " + icon.className);
            modal.style.display = "none";
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#iconSearch').on('keyup', function() {
            var searchText = $(this).val().toLowerCase(); // Convert input value to lowercase
            $('.icon-grid i').each(function() {
                var iconClass = $(this).attr('class').toLowerCase(); // Get class attribute of each icon
                if (iconClass.indexOf(searchText) === -1) {
                    $(this).hide(); // Hide icons that do not match the search text
                } else {
                    $(this).show(); // Show icons that match the search text
                }
            });
        });
    });
</script>