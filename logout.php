<?php

// Initialize the session
session_start();

// Include config file
require_once "include/config.php"; // Include config file

date_default_timezone_set("Asia/Colombo");

// Retrieve the return URL from the query parameter (if available)
$return_url = isset($_GET['return_url']) ? $_GET['return_url'] : null;

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to the login page with the return URL (if available)
if ($return_url) {
    header("Location: login?return_url=" . urlencode($return_url));
} else {
    header("Location: login");
}
exit;
