<?php
// Set session-related configurations before starting the session
$expire = 365 * 24 * 3600; // 1 year in seconds

// Set the session cookie lifetime
ini_set('session.cookie_lifetime', $expire);

// Set the session cache expire
session_cache_expire($expire / 60); // session_cache_expire is in minutes


session_start(); // Initialize the session


// // Check if the session is active
// if (isset($_SESSION['user_name'])) {
//   // Session is active

//   // Get the session cookie lifetime
//   $cookieLifetime = ini_get('session.cookie_lifetime');

//   if ($cookieLifetime == 0) {
//     // The session cookie is set to expire when the browser is closed
//     echo "Session cookie expires when the browser is closed.";
//   } else {
//     // The session cookie has a specific lifetime in seconds
//     echo "Session cookie expires in {$cookieLifetime} seconds.";
//   }
// } else {
//   // Session is not active
//   echo "Session is not active.";
// }

if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["user_name"])) {
  $session_id = $_SESSION["user_name"];
  $session_user_name = htmlspecialchars($_SESSION["user_name"]);

  $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type` FROM `user_accounts` WHERE `email` LIKE '$session_user_name' OR  `user_name` LIKE '$session_user_name'";
  $result = $link->query($sql);
  while ($row = $result->fetch_assoc()) {
    $session_first_name = $row['first_name'];
    $session_last_name = $row['last_name'];
    $session_student_number = $row['user_name'];
    $session_email = $row['email'];
    $session_user_level = $row['acc_type'];
    $session_user_id = $row['id'];
  }
} else {
  // Redirect to logout.php if session is not active
  // Capture the current URL
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
  $current_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

  // Redirect to logout.php with the current URL as a query parameter
  header("Location: logout?return_url=" . urlencode($current_url));
  exit;
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login");
  exit;
}
