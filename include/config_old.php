<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "uni_erp";
$database_error = "";

/* Attempt to connect to MySQL database */
$link = mysqli_connect($server, $username, $password, $database);

// Check connection

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} else {
    $database_error = "Connected to the Server";
}

// PDO
try {
    // Set up the PDO connection
    $pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If connection is successful
    $database_error = "Connected to the Server";
} catch (PDOException $e) {
    // If connection fails, display error message
    $database_error = "Connection failed: " . $e->getMessage();
}


// Establish LMS Connection
$server = "localhost";
$username = "root";
$password = "";
$database = "pharmaco_pharmacollege";
$database_error = "";

/* Attempt to connect to MySQL database */
$lms_link = mysqli_connect($server, $username, $password, $database);

// Check connection

if ($lms_link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} else {
    $database_error = "Connected to the Server";
}

// PDO
try {
    // Set up the PDO connection
    $lms_pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);

    // Set the PDO error mode to exception
    $lms_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If connection is successful
    $database_error = "Connected to the Server";
} catch (PDOException $e) {
    // If connection fails, display error message
    $database_error = "Connection failed: " . $e->getMessage();
}

mysqli_set_charset($link, "utf8");
date_default_timezone_set("Asia/Colombo");
$date_now = date('F j, Y H:i:s');
$SiteTitle = "Payshia ERP";
