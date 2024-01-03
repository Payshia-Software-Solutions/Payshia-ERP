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

mysqli_set_charset($link, "utf8");
date_default_timezone_set("Asia/Colombo");
$date_now = date('F j, Y H:i:s');
$SiteTitle = "Payshia ERP";
