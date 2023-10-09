<?php
session_start(); // Initialize the session

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

  // Check if the user is logged in, if not then redirect him to login page
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // header("location: ./login");
    // exit;
  }
} else {
  // handle the case when the session or user_name variable is not set
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login");
  exit;
}
