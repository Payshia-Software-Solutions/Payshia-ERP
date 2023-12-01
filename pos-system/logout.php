<?php

// Initialize the session
session_start();

$sessionid = $_SESSION["user_name"];

// Include config file
require_once "../include/config.php"; // Include config file

date_default_timezone_set("Asia/Colombo");

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: login");
exit;
