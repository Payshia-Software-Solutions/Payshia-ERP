<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$is_active = $_POST["is_active"];

// Customer Parameters (replace these with your actual form field names)
$customer_id = $_POST["customer_id"];
$customer_first_name = $_POST["customer_first_name"];
$customer_last_name = $_POST["customer_last_name"];
$phone_number = $_POST["phone_number"];
$address_line1 = $_POST["address_line1"];
$address_line2 = $_POST["address_line2"];
$city_id = $_POST["city_id"];
$email_address = $_POST["email_address"];
$opening_balance = $_POST["opening_balance"];
$created_at = date("Y-m-d H:i:s"); // Assuming you want to use the current timestamp
$company_id = $_POST["company_id"];
$location_id = $_POST["location_id"];
$credit_limit = $_POST["credit_limit"];
$credit_days = $_POST["credit_days"];
$region_id = $_POST["region_id"];
$route_id = $_POST["route_id"];
$area_id = $_POST["area_id"];

$QueryResult = CreateCustomer($link, $customer_id, $customer_first_name, $customer_last_name, $phone_number, $address_line1, $address_line2, $city_id, $email_address, $opening_balance, $created_by, $created_at, $company_id, $location_id, $is_active, $credit_limit, $credit_days, $region_id, $route_id, $area_id);
echo $QueryResult;
