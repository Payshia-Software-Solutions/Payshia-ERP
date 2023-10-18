<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

mysqli_set_charset($link, "utf8mb4");

// Account Parameters
$UserLevel = $_POST["UserLevel"];
$created_by = $_POST["LoggedUser"];

// Form Parameters
$UpdateKey = $_POST["UpdateKey"];
$supplier_name = $_POST["supplier_name"];
$opening_balance = $_POST["opening_balance"];
$contact_person = $_POST["contact_person"];
$email = $_POST["email"];
$street_name = $_POST["street_name"];
$city = $_POST["city"];
$zip_code = $_POST["zip_code"];
$telephone = $_POST["telephone"];
$fax = $_POST["fax"];
$is_active = $_POST["is_active"];



$QueryResult = SaveSupplier($link, $supplier_name, $opening_balance, $contact_person, $email, $street_name, $city, $zip_code, $telephone, $fax, $is_active, $created_by, $UpdateKey);
echo $QueryResult;
