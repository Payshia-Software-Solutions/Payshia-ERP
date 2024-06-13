<?php

include __DIR__ . '/../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


function createFolderStructure($givenName, $template)
{
    $messages = [];
    $errors = false;
    $baseDir =  __DIR__ . '/../../../../assets/content/';

    // Main folder
    $mainDir = $baseDir . DIRECTORY_SEPARATOR . $givenName;

    // Subfolders
    $subDirs = ['assets', 'models', 'methods'];
    $assetSubDirs = ['js', 'css', 'images'];

    // Create the main directory
    if (!file_exists($mainDir)) {
        if (mkdir($mainDir, 0777, true)) {
            createIndexFile($mainDir, $template);
            $messages[] = array('status' => 'success', 'message' => "Directory '$mainDir' created successfully.");
        } else {
            $messages[] = array('status' => 'error', 'message' => "Failed to create directory '$mainDir'.");
            $errors = true;
        }
    } else {
        $messages[] = array('status' => 'error', 'message' => "Directory '$mainDir' already exists.");
        $errors = true;
    }

    // Create subdirectories
    foreach ($subDirs as $dir) {
        $subDirPath = $mainDir . DIRECTORY_SEPARATOR . $dir;
        if (!file_exists($subDirPath)) {
            if (mkdir($subDirPath, 0777, true)) {
                $messages[] = array('status' => 'success', 'message' => "Directory '$subDirPath' created successfully.");
            } else {
                $messages[] = array('status' => 'error', 'message' => "Failed to create directory '$subDirPath'.");
                $errors = true;
            }
        } else {
            $messages[] = array('status' => 'error', 'message' => "Directory '$subDirPath' already exists.");
            $errors = true;
        }

        // If the subdirectory is 'assets', create the additional subdirectories inside it
        if ($dir == 'assets') {
            foreach ($assetSubDirs as $assetSubDir) {
                $assetSubDirPath = $subDirPath . DIRECTORY_SEPARATOR . $assetSubDir;
                if (!file_exists($assetSubDirPath)) {
                    if (mkdir($assetSubDirPath, 0777, true)) {
                        $messages[] = array('status' => 'success', 'message' => "Directory '$assetSubDirPath' created successfully.");
                    } else {
                        $messages[] = array('status' => 'error', 'message' => "Failed to create directory '$assetSubDirPath'.");
                        $errors = true;
                    }
                } else {
                    $messages[] = array('status' => 'error', 'message' => "Directory '$assetSubDirPath' already exists.");
                    $errors = true;
                }
            }

            // Create specific files in the js, css, and methods directories
            createAssetFiles($subDirPath, $givenName, $messages, $errors);
        }
    }

    if ($errors) {
        return $messages;
    } else {
        return array('status' => 'success', 'message' => 'All directories and files created successfully.');
    }
}

function createIndexFile($dirPath, $template)
{
    $filePath = $dirPath . DIRECTORY_SEPARATOR . 'index.php';
    file_put_contents($filePath, $template);
}

function createAssetFiles($assetsDirPath, $moduleName, &$messages, &$errors)
{
    // Create JS file
    $jsFilePath = $assetsDirPath . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $moduleName . '-1.0.js';
    $jsContent = <<<JS
var UserLevel = document.getElementById('UserLevel').value;
var LoggedUser = document.getElementById('LoggedUser').value;
var company_id = document.getElementById('company_id').value;
var default_location = document.getElementById('default_location').value;
var default_location_name = document.getElementById('default_location_name').value;

$(document).ready(function() {
    OpenIndex();
});

function OpenIndex(studentBatch = 0) {
    function fetch_data() {
        document.getElementById('index-content').innerHTML = InnerLoader;
        $.ajax({
            url: './assets/content/super-admin/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                studentBatch: studentBatch
            },
            success: function(data) {
                $('#index-content').html(data);
            }
        });
    }
    fetch_data();
}
JS;
    if (file_put_contents($jsFilePath, $jsContent) !== false) {
        $messages[] = array('status' => 'success', 'message' => "File '$jsFilePath' created successfully.");
    } else {
        $messages[] = array('status' => 'error', 'message' => "Failed to create file '$jsFilePath'.");
        $errors = true;
    }

    // Create CSS file
    $cssFilePath = $assetsDirPath . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $moduleName . '_styles-1.0.css';
    $cssContent = "/* Add your CSS styles here */";
    if (file_put_contents($cssFilePath, $cssContent) !== false) {
        $messages[] = array('status' => 'success', 'message' => "File '$cssFilePath' created successfully.");
    } else {
        $messages[] = array('status' => 'error', 'message' => "Failed to create file '$cssFilePath'.");
        $errors = true;
    }

    // Create PHP file
    $phpFilePath = $assetsDirPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'methods' . DIRECTORY_SEPARATOR . $moduleName . '_functions.php';
    $phpContent = <<<PHP
<?php

include __DIR__ . '/../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

PHP;
    if (file_put_contents($phpFilePath, $phpContent) !== false) {
        $messages[] = array('status' => 'success', 'message' => "File '$phpFilePath' created successfully.");
    } else {
        $messages[] = array('status' => 'error', 'message' => "Failed to create file '$phpFilePath'.");
        $errors = true;
    }
}

function formatModuleName($moduleName)
{
    // Replace spaces with underscores and convert to lowercase
    return strtolower(str_replace(' ', '_', $moduleName));
}
