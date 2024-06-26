<?php
// include __DIR__ . './../vendor/phpqrcode/qrlib.php';
include __DIR__ . '/config.php';
date_default_timezone_set("Asia/Colombo");

function generateQRCode($text)
{

    // $ecc stores error correction capability('L')
    $ecc = 'L';
    $pixel_Size = 10;
    $frame_Size = 0;

    // Generate QR Code as a string
    ob_start(); // Start output buffering
    QRcode::png($text, null, $ecc, $pixel_Size, $frame_Size);
    $qrCodeData = ob_get_clean(); // Get the buffer contents and clean the buffer


    // Create data URI for the image
    $dataUri = 'data:image/png;base64,' . base64_encode($qrCodeData);

    return $dataUri;
}



// Sent Email// Define the API URL
function sendCurlRequest($url, $data)
{
    $dataJson = json_encode($data);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['status' => 'error', 'message' => 'cURL error: ' . curl_error($ch)];
    }
    curl_close($ch);

    return $response;
}

function GenerateIndexNumber($link, $accType)
{
    $prefixValue = "ST";

    if ($accType == "Cashier") {
        $prefixValue = 'RSC';
    } else if ($accType == "Officer") {
        $prefixValue = 'OFS';
    } else if ($accType == "Admin") {
        $prefixValue = 'AD';
    } else if ($accType == "Front-User") {
        $prefixValue = 'RSF';
    } else if ($accType == "Steward") {
        $prefixValue = 'STD';
    }

    $sql = "SELECT count(*) as count FROM `user_accounts` WHERE `user_name` LIKE '$prefixValue%'";
    $result = $link->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $currentCount = $row['count'];
        $nextCount = ++$currentCount;

        $GeneratedNumber = $prefixValue . str_pad($nextCount, 2, '0', STR_PAD_LEFT);
        return $GeneratedNumber;
    } else {
        // Handle the SQL error if needed
        return false;
    }
}

function MakeFormatProductCode($refCode)
{
    return str_pad($refCode, 4, '0', STR_PAD_LEFT);
}

function GetCities($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `district_id`, `name_en`, `name_si`, `name_ta`, `sub_name_en`, `sub_name_si`, `sub_name_ta`, `postcode`, `latitude`, `longitude` FROM `cities` ORDER BY `name_en`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetAccounts($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by` FROM `user_accounts` ORDER BY `user_status` DESC, `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['user_name']] = $row;
        }
    }
    return $ArrayResult;
}


function GetAccountByID($link, $userName)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by` FROM `user_accounts` WHERE `user_name` LIKE '$userName'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['user_name']] = $row;
        }
    }
    return $ArrayResult[$userName];
}

function SaveUserAccount($link, $status, $first_name, $last_name, $nic, $student_number, $address_line_1, $address_line_2, $city, $postal_code, $sex, $phone_number, $email_address, $password, $user_type, $is_active, $updateKey, $company_id, $img_path, $created_by)
{

    // echo $img_path;
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($password != "") {
        if ($updateKey === '0') {
            // echo $updateKey;
            $updateKey = GenerateIndexNumber($link, $user_type);
            $sql = "INSERT INTO `user_accounts` (`email`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by`, `civil_status`, `nic_number`, `user_name`) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $Student = GetAccounts($link)[$updateKey];
            $student_number = $Student['user_name'];

            $sql = "UPDATE `user_accounts` SET `email` = ?, `pass` = ?, `first_name` = ?, `last_name` = ?, `sex` = ?, `addressl1` = ?, `addressl2` = ?, `city` = ?, `PNumber` = ?, `WPNumber` = ?, `created_at` = ?, `user_status` = ?, `acc_type` = ?, `img_path` = ?, `update_by` = ?, `civil_status`= ?, `nic_number` = ? WHERE `user_name` = ?";
        }

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssss", $param_1, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16, $param_17, $param_18, $param_2);

            $param_1 = $email_address;
            $param_3 = password_hash($password, PASSWORD_DEFAULT);
            $param_4 = $first_name;
            $param_5 = $last_name;
            $param_6 = $sex;
            $param_7 = $address_line_1;
            $param_8 = $address_line_2;
            $param_9 = $city;
            $param_10 = $phone_number;
            $param_11 = $phone_number;
            $param_12 = $current_time;
            $param_13 = $is_active;
            $param_14 = $user_type;
            $param_15 = $img_path;
            $param_16 = $created_by;
            $param_17 = $status;
            $param_18 = $nic;
            $param_2 = $updateKey;

            if (mysqli_stmt_execute($stmt_sql)) {
                $error = array('status' => 'success', 'message' => 'User account data saved successfully');
            } else {
                $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
            }

            mysqli_stmt_close($stmt_sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
    } else {
        $sql = "UPDATE `user_accounts` SET `email` = ?, `first_name` = ?, `last_name` = ?, `sex` = ?, `addressl1` = ?, `addressl2` = ?, `city` = ?, `PNumber` = ?, `WPNumber` = ?, `created_at` = ?, `user_status` = ?, `acc_type` = ?, `img_path` = ?, `update_by` = ?, `civil_status`= ? WHERE `user_name` = '$updateKey'";

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssssssss", $param_1, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16, $param_17);

            $param_1 = $email_address;
            $param_4 = $first_name;
            $param_5 = $last_name;
            $param_6 = $sex;
            $param_7 = $address_line_1;
            $param_8 = $address_line_2;
            $param_9 = $city;
            $param_10 = $phone_number;
            $param_11 = $phone_number;
            $param_12 = $current_time;
            $param_13 = $is_active;
            $param_14 = $user_type;
            $param_15 = $img_path;
            $param_16 = $created_by;
            $param_17 = $status;



            if (mysqli_stmt_execute($stmt_sql)) {
                $error = array('status' => 'success', 'message' => 'User account data saved successfully' . $img_path);
            } else {
                $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
            }

            mysqli_stmt_close($stmt_sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
    }




    return json_encode($error);
}
function GetCityList($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `district_id`, `name_en`, `name_si`, `name_ta`, `sub_name_en`, `sub_name_si`, `sub_name_ta`, `postcode`, `latitude`, `longitude` FROM `cities` ORDER BY `name_en`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function getDistricts($link)
{
    $sql = "SELECT `id`, `province_id`, `name_en`, `name_si`, `name_ta` FROM `districts`";
    $ArrayResult = array();
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetCountriesList($link)
{

    $ArrayResult = array();
    $sql = "SELECT `number`, `alpha2`, `alpha3`, `langEN`, `langDE`, `langES`, `langFR`, `langIT`, `tld` FROM `world_countries` ORDER BY `langEN`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['alpha2']] = $row;
        }
    }
    return $ArrayResult;
}

// ---------------------------------------------



function GetLocations($link)
{

    $ArrayResult = array();
    $sql = "SELECT * FROM `master_location` ORDER BY `location_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['location_id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveLocation($link, $location_name, $is_active, $created_by, $logo_path, $address_line1, $address_line2, $city, $phone_1, $phone_2, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_location` (`location_name`, `is_active`, `created_at`, `created_by`, `logo_path`, `address_line1`, `address_line2`, `city`, `phone_1`, `phone_2`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ?)";
    } else {
        $sql = "UPDATE `master_location` SET `location_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ?, `logo_path` = ?, `address_line1` = ?, `address_line2` = ?, `city` = ?, `phone_1` = ?, `phone_2` = ? WHERE `location_id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssssss", $location_name, $is_active, $current_time, $created_by, $logo_path, $address_line1, $address_line2, $city, $phone_1, $phone_2);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Location saved successfully', 'last_inserted_id' => $UpdateKey);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateLocationStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_location` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `location_id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Location Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


//**********************************Rashmika***********************************

//**************************** starting of supplier*************************** 
function GetSupplier($link)
{

    $ArrayResult = array();
    $sql = "SELECT  `supplier_id`, `supplier_name`, `opening_balance`, `is_active`, `created_by`, `created_at`, `email`, `contact_person`, `street_name`, `city`, `zip_code`, `telephone`, `fax` FROM `master_supplier` ORDER BY `supplier_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['supplier_id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveSupplier($link, $supplier_name, $opening_balance, $contact_person, $email, $street_name, $city, $zip_code, $telephone, $fax, $is_active, $created_by, $UpdateKey)

{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_supplier` ( `supplier_name`, `opening_balance`, `is_active`, `created_by`, `created_at`, `email`, `contact_person`, `street_name`, `city`, `zip_code`, `telephone`, `fax`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_supplier` SET `supplier_name` = ?, `opening_balance`=?, `is_active`=?, `created_by`=?, `created_at`=?, `email`=?, `contact_person`=?, `street_name`=?, `city`=?, `zip_code`=?, `telephone`=?, `fax`=? WHERE `supplier_id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssss", $supplier_name, $opening_balance, $is_active, $created_by, $current_time, $email, $contact_person, $street_name, $city, $zip_code, $telephone, $fax);

        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Supplier saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function UpdateSupplierStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_supplier` SET `is_active`=?, `created_by`=? WHERE `supplier_id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ss", $is_active, $current_time);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Supplier Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}
//***************************************end of the supplier********************



//***************************************start of the unit********************
function GetUnit($link)
{

    $ArrayResult = array();
    $sql = "SELECT `unit_id`, `unit_name`, `is_active`, `created_at`, `created_by` FROM `master_unit` ORDER BY `unit_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['unit_id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveUnit($link, $unit_name, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_unit` (`unit_name`, `is_active`, `created_by`, `created_at`) VALUES (?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_unit` SET `unit_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `unit_id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssss", $unit_name, $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Unit saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateUnitStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_unit` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `unit_id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Unit Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}
//***************************************end of the unit*******************


function GetSections($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `section_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_sections` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


// Section 
function SaveSection($link, $section_name, $is_active, $created_by, $UpdateKey, $pos_display)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_sections` (`section_name`, `is_active`, `created_at`, `created_by`, `pos_display`) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_sections` SET `section_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ?, `pos_display` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $section_name, $is_active, $current_time, $created_by, $pos_display);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Section saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateSectionStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_sections` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Section Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

// Departments

function GetDepartments($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_departments` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveDepartment($link, $department_name, $is_active, $created_by, $UpdateKey, $section_id, $pos_display)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_departments` (`section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display`) VALUES (?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_departments` SET  `section_id` = ? ,`department_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ?, `pos_display` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssss", $section_id, $department_name, $is_active, $current_time, $created_by, $pos_display);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Department saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateDepartmentStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_departments` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Department Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

// Categories

function GetCategories($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_categories` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveCategory($link, $category_name, $is_active, $created_by, $UpdateKey, $section_id, $department_id, $pos_display)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_categories` (`section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by`, `pos_display`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_categories` SET  `section_id` = ?, `department_id` = ? ,`category_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ?, `pos_display` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssss", $section_id, $department_id, $category_name, $is_active, $current_time, $created_by, $pos_display);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Category saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateCategoryStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_categories` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Category Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function GetProducts($link)
{

    $ArrayResult = array();
    $sql = "SELECT * FROM `master_product` ORDER BY `active_status` DESC, `product_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}



function GetRawProducts($link)
{

    $ArrayResult = array();
    $sql = "SELECT * FROM `master_product` WHERE `item_type` LIKE 'Raw' OR `item_type` LIKE 'SItem' OR `item_type` LIKE 'RawnSell' ORDER BY `active_status` DESC, `product_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveProduct($link, $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $UpdateKey, $name_si, $name_ti, $price_2, $recipe_type, $barcode, $locationList, $openingStock)
{
    $error = array();
    $created_at = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_product` (`product_code`, `product_name`, `display_name`, `print_name`, `section_id`, `department_id`, `category_id`, `brand_id`, `measurement`, `reorder_level`, `lead_days`, `cost_price`, `selling_price`, `minimum_price`, `wholesale_price`, `item_type`, `item_location`, `image_path`, `created_by`, `created_at`, `active_status`, `generic_id`, `supplier_list`, `size_id`, `color_id`, `product_description`, `name_si`, `name_ti`, `price_2`, `recipe_type`, `barcode`, `location_list`, `opening_stock`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_product` SET `product_code` = ?, `product_name` = ?, `display_name` = ?, `print_name` = ?, `section_id` = ?, `department_id` = ?, `category_id` = ?, `brand_id` = ?, `measurement` = ?, `reorder_level` = ?, `lead_days` = ?, `cost_price` = ?, `selling_price` = ?, `minimum_price` = ?, `wholesale_price` = ?, `item_type` = ?, `item_location` = ?, `image_path` = ?, `created_by` = ?, `created_at` = ?, `active_status` = ?, `generic_id` = ?, `supplier_list` = ?,  `size_id` = ?, `color_id`= ?, `product_description` = ?, `name_si` = ?, `name_ti` = ? , `price_2` = ?, `recipe_type` = ?, `barcode` = ?, `location_list` = ?, `opening_stock` = ? WHERE `product_id` = ?";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        if ($UpdateKey != 0) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssssssssssssssssssss", $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $created_at, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $name_si, $name_ti, $price_2, $recipe_type, $barcode, $locationList, $openingStock, $UpdateKey);
        } else {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssssssssssssssssssssssssss", $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $created_at, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $name_si, $name_ti, $price_2, $recipe_type, $barcode, $locationList, $openingStock);
        }

        if (mysqli_stmt_execute($stmt_sql)) {
            if ($UpdateKey == 0) {
                $UpdateKey = mysqli_insert_id($link); // Get the last inserted ID

            }
            $error = array('status' => 'success', 'message' => 'Product saved successfully', 'last_inserted_id' => $UpdateKey);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later.' . $link->error);
        }

        $stock_result = UpdateOpeningBalance($UpdateKey, $openingStock, $product_name, $item_location, $created_by);
        // var_dump($stock_result);

        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. ' . $brand_id . $link->error);
    }
    return json_encode($error);
}

function UpdateOpeningBalance($ProductKey, $openingStock, $product_name, $item_location, $created_by)
{
    global $link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `type`, `quantity`, `product_id`, `reference`, `location_id`, `created_by`, `created_at`, `is_active`, `ref_id` FROM `transaction_stock_entry` WHERE `product_id` LIKE '$ProductKey' AND `ref_id` LIKE 'OB' AND `is_active` = 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        // Update Opening Stock
        $sql = "UPDATE `transaction_stock_entry` SET `quantity` = ? WHERE WHERE `product_id` LIKE '$ProductKey' AND `ref_id` LIKE 'OB' AND `is_active` = 1";

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "s", $openingStock);
            if (mysqli_stmt_execute($stmt_sql)) {
                $stock_result = array('status' => 'success', 'message' => 'Opening Stock Updated successfully');
            } else {
                $stock_result = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
            }
            mysqli_stmt_close($stmt_sql);
        } else {
            $stock_result = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
    } else {
        // Insert Opening Stock
        $stock_type = "DEBIT";
        $reference = $stock_type . " : " . $openingStock . " " . $product_name . " | Opening Stock";
        $stock_result = CreateStockEntry($link, $stock_type, $openingStock, $ProductKey, $reference, $item_location, $created_by, 1, 'OB');
    }
    return $stock_result;
}


function UpdateProductStatus($link, $is_active, $UpdateKey, $created_by)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_product` SET `active_status` = ?, `created_at` = ?, `created_by` = ? WHERE `product_id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Product Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

// Categories

function GetTables($link)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `table_name`, `is_active`, `created_at`, `created_by`, `location_id` FROM `master_table` ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetTablesByLocation($link, $location_id)
{

    $ArrayResult = array();
    $sql = "SELECT `id`, `table_name`, `is_active`, `created_at`, `created_by`, `location_id` FROM `master_table` WHERE `location_id` LIKE '$location_id' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveTable($link, $table_name, $is_active, $created_by, $UpdateKey, $location_id)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_table` (`table_name`, `is_active`, `created_at`, `created_by`, `location_id`) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_table` SET `table_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ?, `location_id` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $table_name, $is_active, $current_time, $created_by, $location_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Table saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function UpdateTableStatus($link, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "UPDATE `master_table` SET `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Table Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function GetCart($link, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT t.`id`, t.`user_id`, t.`product_id`, t.`item_price`, t.`item_discount`, t.`quantity`, t.`added_date`, t.`is_active`, t.`customer_id`, t.`hold_status`, t.`printed_status`, mp.`display_name`
    FROM `temp_order` t
    JOIN `master_product` mp ON t.`product_id` = mp.`product_id` WHERE t.`user_id` LIKE '$UserName' AND t.`hold_status` = 0 ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetCartByLocation($link, $locationId)
{
    $productInfo = array();
    $sql = "SELECT `product_id`, SUM(`quantity`) AS total_quantity FROM `temp_order` WHERE `location_id` LIKE ? GROUP BY `product_id`";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $locationId);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $productInfo[$row['product_id']] = array(
            'product_id' => $row['product_id'],
            'total_quantity' => $row['total_quantity']
        );
    }

    $stmt->close();

    return $productInfo;
}


function ProductsByMaterial($link, $materialId)
{
    $ArrayResult = array();
    $sql =  "SELECT `main_product`, `recipe_product`, `qty`, `created_by`, `created_at` FROM `transaction_recipe` WHERE `recipe_product` LIKE '$materialId'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['main_product']] = $row;
        }
    }
    return $ArrayResult;
}

function CheckGetCartQty($link, $productCode)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`quantity`) AS `currentQuantity` FROM `temp_order` WHERE  `product_id` LIKE '$productCode'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['currentQuantity'];
        }
    }
    return $ArrayResult;
}

function GetHoldItemQty($link, $LocationID)
{

    $ArrayResult = array();
    $sql = "SELECT *  FROM `transaction_invoice` WHERE `invoice_status` LIKE '1' AND `is_active` LIKE 1 AND `location_id` LIKE '$LocationID' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoiceNumber = $row['invoice_number'];

            $inner_sql = "SELECT `product_id`, SUM(`quantity`) AS `total_quantity` FROM `transaction_invoice_items` WHERE `invoice_number` LIKE '$invoiceNumber' GROUP BY `product_id`";
            $inner_result = $link->query($inner_sql);
            if ($inner_result->num_rows > 0) {
                while ($row = $inner_result->fetch_assoc()) {
                    $ArrayResult[$row['product_id']] = $row;
                }
            }
        }
    }
    return $ArrayResult;
}

function GetHoldItemQtyNew($link, $LocationID)
{
    $ArrayResult = array();
    $sql = "SELECT product_id, SUM(quantity) AS total_quantity
            FROM transaction_invoice_items AS tii
            INNER JOIN transaction_invoice AS ti ON tii.invoice_number = ti.invoice_number
            WHERE ti.invoice_status = '1' AND ti.is_active = 1 AND ti.location_id = '$LocationID'
            GROUP BY product_id";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row['total_quantity'];
        }
    }
    return $ArrayResult;
}



function GetHoldCart($link, $LoggedUser, $invoice_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `hold_status` FROM `temp_order` WHERE `user_id` LIKE '$LoggedUser' AND `hold_status` = 0 ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function AddToCart($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity, $TableID, $printedStatus, $locationId)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;


    // $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `printed_status` FROM `temp_order` WHERE `user_id` LIKE '$UserName' AND `product_id` LIKE '$ProductID' AND `hold_status` = 0 ORDER BY `id`";

    // $result = $link->query($sql);
    // if ($result->num_rows > 0) {
    //     $sql = "UPDATE `temp_order` SET  `user_id` = ?, `product_id` = ?, `item_price` = ?, `item_discount` = ?, `quantity` = ?, `added_date` = ?, `is_active` = ?, `customer_id` = ?, `table_id` = ?, `printed_status` = ? WHERE `user_id` LIKE '$UserName' AND `product_id` LIKE '$ProductID'";
    // } else {
    //     $sql = "INSERT INTO `temp_order` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `table_id`, `printed_status` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // }

    $sql = "INSERT INTO `temp_order` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `table_id`, `printed_status`, `location_id` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $UserName, $ProductID, $ItemPrice, $ItemDiscount, $Quantity, $current_time, $is_active, $CustomerID, $TableID, $printedStatus, $locationId);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cart Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function RemoveFromCart($link, $ProductID, $UserName)
{
    $sql = "DELETE FROM `temp_order` WHERE `user_id` LIKE ? AND `product_id` LIKE ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ss", $UserName, $ProductID);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cart Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function deleteRecordsWithHoldStatusNotHold($link, $UserName)
{
    $sql = "DELETE FROM `temp_order` WHERE `user_id` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $UserName);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cart Cleared successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function DeleteCurrentInvoiceProducts($link, $invoice_number)
{
    $sql = "DELETE FROM `transaction_invoice_items` WHERE `invoice_number` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $invoice_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cart Cleared successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function AddToInvoice($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity, $TableID, $invoice_number, $printed_status)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;

    $cost_price = GetCostPrice($link, $ProductID);

    // $sql = "SELECT `id` FROM `transaction_invoice_items` WHERE `invoice_number` LIKE '$invoice_number' AND `product_id` LIKE '$ProductID'";

    // $result = $link->query($sql);
    // if ($result->num_rows > 0) {
    //     $sql = "UPDATE `transaction_invoice_items` SET  `user_id` = ?, `product_id` = ?, `item_price` = ?, `item_discount` = ?, `quantity` = ?, `added_date` = ?, `is_active` = ?, `customer_id` = ?, `table_id` = ?, `invoice_number` = ?, `cost_price` = ?, `printed_status` = ? WHERE `invoice_number` LIKE '$invoice_number' AND `product_id` LIKE '$ProductID'";
    // } else {
    //     $sql = "INSERT INTO `transaction_invoice_items` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `table_id`, `invoice_number`, `cost_price`, `printed_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // }

    $sql = "INSERT INTO `transaction_invoice_items` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `table_id`, `invoice_number`, `cost_price`, `printed_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssss", $UserName, $ProductID, $ItemPrice, $ItemDiscount, $Quantity, $current_time, $is_active, $CustomerID, $TableID, $invoice_number, $cost_price, $printed_status);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function CreateInvoice($link, $invoice_number, $invoice_date, $inv_amount, $grand_total, $discount_amount, $discount_percentage, $customer_code, $service_charge, $tendered_amount, $close_type, $invoice_status, $location_id, $table_id, $created_by, $is_active, $stewardId, $cost_value, $ref_hold)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "SELECT `id` FROM `transaction_invoice` WHERE `invoice_number` LIKE '$invoice_number'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `transaction_invoice` SET  `invoice_number` = ?, `invoice_date` = ?, `inv_amount` = ?,  `grand_total` = ?, `discount_amount`= ?,`discount_percentage` = ?, `customer_code` = ?, `service_charge` = ?, `tendered_amount` = ?, `close_type` = ?, `invoice_status` = ?, `current_time` = ?, `location_id` = ?, `table_id` = ?, `created_by` = ?, `is_active` = ?, `steward_id` = ?, `cost_value` = ?, `ref_hold` = ? WHERE `invoice_number` LIKE '$invoice_number'";
    } else {
        $sql = "INSERT INTO `transaction_invoice` (`invoice_number`, `invoice_date`, `inv_amount`, `grand_total`, `discount_amount`, `discount_percentage`, `customer_code`, `service_charge`, `tendered_amount`, `close_type`, `invoice_status`, `current_time`, `location_id`, `table_id`, `created_by`, `is_active`, `steward_id`, `cost_value`, `ref_hold`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssssssssssssss", $invoice_number, $invoice_date, $inv_amount, $grand_total, $discount_amount, $discount_percentage, $customer_code, $service_charge, $tendered_amount, $close_type, $invoice_status, $current_time, $location_id, $table_id, $created_by, $is_active, $stewardId, $cost_value, $ref_hold);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Saved successfully', 'invoice_number' => $invoice_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $invoice_number);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $invoice_number);
    }
    return json_encode($error);
}

function SetTempInvoiceFinish($link, $invoice_number, $invoice_status)
{
    $sql = "UPDATE `transaction_invoice` SET  `invoice_status` = ? WHERE `invoice_number` LIKE '$invoice_number'";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $invoice_status);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Updated successfully', 'invoice_number' => $invoice_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $invoice_number);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $invoice_number);
    }
}


function UpdatePrintedStatus($link, $invoice_number, $printStatus)
{
    $sql = "UPDATE `transaction_invoice_items` SET  `printed_status` = ? WHERE `invoice_number` LIKE '$invoice_number'";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $printStatus);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Updated successfully', 'invoice_number' => $printStatus);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $printStatus);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'invoice_number' => $printStatus);
    }
}

function generateInvoiceNumber($link, $prefix)
{
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_invoice` WHERE `invoice_number` LIKE '$prefix%'";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $invoiceNumber = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }

    return null; // Return null on error
}

function GetInvoices($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice` ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult;
}



function GetLastInvoiceByLocationByUser($link, $LocationID, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice` WHERE `location_id` LIKE '$LocationID' AND `created_by` LIKE '$UserName' ORDER BY `id` DESC LIMIT 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetInvoiceByNumber($link, $invoice_number)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice` WHERE `invoice_number` LIKE '$invoice_number' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult[$invoice_number];
}

function GetHoldInvoices($link)
{
    $ArrayResult = array();
    $sql = "SELECT *  FROM `transaction_invoice` WHERE `invoice_status` LIKE '1' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult;
}


function GetHoldInvoicesByLocation($link, $LocationID)
{
    $ArrayResult = array();
    $sql = "SELECT *  FROM `transaction_invoice` WHERE `invoice_status` LIKE '1' AND `location_id` LIKE '$LocationID' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetHoldInvoicesByLocationUser($link, $LocationID, $CreatedBy)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice` WHERE `invoice_status` LIKE '1' AND `location_id` LIKE '$LocationID' AND `created_by` LIKE '$CreatedBy' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetByInvoicesDate($link, $Date, $LocationID)
{
    $ArrayResult = array();

    // Modify the SQL query to filter by the specified date
    $sql = "SELECT * FROM `transaction_invoice` WHERE `invoice_status` LIKE '2' AND `invoice_date` = '$Date' AND `location_id` LIKE '$LocationID' ORDER BY `id`";

    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }

    return $ArrayResult;
}

function GetInvoicesByCustomer($link, $customerId)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice` WHERE `customer_code` LIKE '$customerId' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['invoice_number']] = $row;
        }
    }
    return $ArrayResult;
}



function GetInvoiceItems($link, $invoice_number)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_invoice_items` WHERE `invoice_number`  LIKE '$invoice_number' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetInvoiceItemsPrint($link, $invoice_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, SUM(`quantity`) AS `quantity`, `added_date`, `is_active`, `customer_id`, `hold_status`, `table_id`, `invoice_number`, `cost_price`, `printed_status` FROM `transaction_invoice_items` WHERE `invoice_number`  LIKE '$invoice_number' GROUP BY `product_id`, `item_discount` ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function isInvoiceNumberExists($link, $invoiceNumber)
{
    $sql = "SELECT COUNT(*) FROM `transaction_invoice` WHERE `invoice_number` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $invoiceNumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}


function CreateReceipt($link, $rec_number, $type, $is_active, $date, $amount, $created_by, $ref_id, $location_id, $customer_id, $today_invoice)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // Check if a receipt with the given `rec_number` already exists
    $sql = "SELECT `id` FROM `transaction_receipt` WHERE `rec_number` LIKE ?";
    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "s", $rec_number);
    mysqli_stmt_execute($stmt_sql);
    $result = mysqli_stmt_get_result($stmt_sql);

    if ($result->num_rows > 0) {
        // If the receipt exists, update it
        $sql = "UPDATE `transaction_receipt` SET `rec_number`= ?, `type` = ?, `is_active` = ?, `date` = ?, `current_time` = ?, `amount` = ?, `created_by` = ?, `ref_id` = ?, `location_id` = ?, `customer_id` = ?, `today_invoice` = ? WHERE `rec_number` LIKE '$rec_number'";
    } else {
        // If the receipt doesn't exist, insert a new one
        $sql = "INSERT INTO `transaction_receipt` (`rec_number`, `type`, `is_active`, `date`, `current_time`, `amount`, `created_by`, `ref_id`, `location_id`, `customer_id`, `today_invoice`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $rec_number, $type, $is_active, $date, $current_time, $amount, $created_by, $ref_id, $location_id, $customer_id, $today_invoice);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Receipt Saved successfully', 'rec_number' => $rec_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'rec_number' => $rec_number);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'rec_number' => $rec_number);
    }

    return json_encode($error);
}


function CreateStockEntry($link, $type, $quantity, $product_id, $reference, $location_id, $created_by, $is_active, $ref_id)
{

    global $link;

    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // If the stock entry doesn't exist, insert a new one
    $sql = "INSERT INTO `transaction_stock_entry` (`type`, `quantity`, `product_id`, `reference`, `location_id`, `created_by`, `created_at`, `is_active`, `ref_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssss", $type, $quantity, $product_id, $reference, $location_id, $created_by, $current_time, $is_active, $ref_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Stock Entry Saved successfully', 'reference' => $reference);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'reference' => $reference);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'reference' => $reference);
    }

    return json_encode($error);
}


function generateRecNumber($link, $prefix)
{
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_receipt` WHERE `rec_number` LIKE '$prefix%'";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $rec_number = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $rec_number;
    }

    return null; // Return null on error
}

function GetReceipts($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_receipt` ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['rec_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetReceiptsByInvoice($link, $invoice_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `rec_number`, `type`, `is_active`, `date`, `current_time`, `amount`, `created_by`, `ref_id`, `location_id` FROM `transaction_receipt` WHERE `ref_id` LIKE '$invoice_number' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['rec_number']] = $row;
        }
    }
    return $ArrayResult;
}



function GetReceiptsValueByInvoice($link, $invoice_number)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`amount`) AS `paymentAmount` FROM `transaction_receipt` WHERE `ref_id` LIKE '$invoice_number' AND `is_active` LIKE 1 ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['paymentAmount'];
        }
    }
    return $ArrayResult;
}


function GetReceiptByNumber($link, $rec_number)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_receipt` WHERE `rec_number` LIKE '$rec_number' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['rec_number']] = $row;
        }
    }
    return $ArrayResult;
}

function isInvoiceNumberExistsForTable($link, $table_id)
{
    $sql = "SELECT COUNT(*) FROM `transaction_invoice` WHERE `table_id` = ? AND `invoice_status` LIKE '1' AND `is_active` = '1'";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $table_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0;  // If count is greater than 0, the invoice number exists for the specified table_id
    } else {
        return false; // Error in preparing the statement
    }
}

function GetCustomers($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `master_customer` ORDER BY `customer_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['customer_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetActiveCustomers($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `master_customer` WHERE `is_active` LIKE 1 ORDER BY `customer_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['customer_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetCustomersByID($link, $CustomerID)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `master_customer` WHERE `customer_id` = '$CustomerID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['customer_id']] = $row;
        }
    }
    return $ArrayResult[$CustomerID];
}



function GetCustomerName($link, $CustomerID)
{
    $customerName = '';

    // Make sure to sanitize the $CustomerID to prevent SQL injection
    $CustomerID = $link->real_escape_string($CustomerID);

    $sql = "SELECT CONCAT(`customer_first_name`, ' ', `customer_last_name`) AS `full_name` FROM `master_customer` WHERE `customer_id` = '$CustomerID'";

    $result = $link->query($sql);
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customerName = $row['full_name'];
        }
        $result->free_result(); // Free the result set
    }

    return $customerName;
}

function GetLocationCustomers($link, $LocationID)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `master_customer` WHERE `location_id` LIKE '$LocationID' ORDER BY `customer_first_name`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['customer_id']] = $row;
        }
    }
    return $ArrayResult;
}

function CreateCustomer($link, $customer_id, $customer_first_name, $customer_last_name, $phone_number, $address_line1, $address_line2, $city_id, $email_address, $opening_balance, $created_by, $created_at, $company_id, $location_id, $is_active, $credit_limit, $credit_days, $region_id, $route_id, $area_id)
{
    $error = array();
    if ($customer_id != 0) {
        // If the customer exists, update it
        $sql = "UPDATE `master_customer` SET   `customer_first_name` = ?, `customer_last_name` = ?, `phone_number` = ?, `address_line1` = ?, `address_line2` = ?, `city_id` = ?, `email_address` = ?, `opening_balance` = ?, `created_by` = ?, `created_at` = ?, `company_id` = ?, `location_id` = ?, `is_active` = ?, `credit_limit` = ?, `credit_days` = ?, `region_id` = ?, `route_id` = ?, `area_id` = ? WHERE `customer_id` = '$customer_id'";
    } else {
        // If the customer doesn't exist, insert a new one
        $sql = "INSERT INTO `master_customer` (`customer_first_name`, `customer_last_name`, `phone_number`, `address_line1`, `address_line2`, `city_id`, `email_address`, `opening_balance`, `created_by`, `created_at`, `company_id`, `location_id`, `is_active`, `credit_limit`, `credit_days`, `region_id`, `route_id`, `area_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssss",  $customer_first_name, $customer_last_name, $phone_number, $address_line1, $address_line2, $city_id, $email_address, $opening_balance, $created_by, $created_at, $company_id, $location_id, $is_active, $credit_limit, $credit_days, $region_id, $route_id, $area_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Customer saved successfully', 'customer_id' => $customer_id);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'customer_id' => $customer_id);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'customer_id' => $customer_id);
    }

    return json_encode($error);
}



// ********************************************************************************************************************************************************
// ********************************************************************************************************************************************************
// Transactions Module

function AddOrUpdateTempPurchaseOrder($link, $UserName, $ProductID, $Quantity, $OrderRate, $SupplierID, $LocationID, $OrderUnit, $taxType)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;
    $HoldStatus = 1;

    // Check if the record already exists
    $sql_select = "SELECT `id` FROM `temp_purchase_order` WHERE `user_name` = ? AND `product_id` = ?";
    $stmt_select = mysqli_prepare($link, $sql_select);
    mysqli_stmt_bind_param($stmt_select, "ss", $UserName, $ProductID);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_store_result($stmt_select);

    if (mysqli_stmt_num_rows($stmt_select) > 0) {
        // Record exists, perform update
        $sql = "UPDATE `temp_purchase_order` SET `user_name` = ?, `product_id` = ?, `quantity` = ?, `order_rate` = ?, `added_date` = ?, `is_active` = ?, `supplier_id` = ?, `hold_status` = ?, `location_id` = ?, `order_unit` = ?, `tax_type` = ? WHERE `user_name` = '$UserName' AND `product_id` = '$ProductID'";
    } else {
        // Record doesn't exist, perform insert
        $sql = "INSERT INTO `temp_purchase_order` (`user_name`, `product_id`, `quantity`, `order_rate`, `added_date`, `is_active`, `supplier_id`, `hold_status`, `location_id`, `order_unit`, `tax_type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    // Execute the appropriate query
    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $UserName, $ProductID, $Quantity, $OrderRate, $current_time, $is_active, $SupplierID, $HoldStatus,  $LocationID, $OrderUnit, $taxType);

    if (mysqli_stmt_execute($stmt_sql)) {
        $error = array('status' => 'success', 'message' => 'Record updated successfully');
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    // Close the statements
    mysqli_stmt_close($stmt_select);
    mysqli_stmt_close($stmt_sql);

    return json_encode($error);
}


function GetTempPO($link, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `user_name`, `product_id`, `quantity`, `order_rate`, `added_date`, `is_active`, `supplier_id`, `hold_status`, `location_id`, `order_unit`, `tax_type` FROM `temp_purchase_order` WHERE `user_name` LIKE '$UserName'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function ClearTempPO($link, $UserName)
{
    $sql = "DELETE FROM `temp_purchase_order` WHERE `user_name` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $UserName);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Order Cleared successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}



function RemoveFromOrder($link, $RecordID)
{
    $sql = "DELETE FROM `temp_purchase_order` WHERE `id` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $RecordID);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Removed successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function isPurchaseOrderExists($link, $purchaseOrderNumber)
{
    $sql = "SELECT COUNT(*) FROM `transaction_purchase_order` WHERE `po_number` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $purchaseOrderNumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}

function generatePurchaseOrderNumber($link, $prefix)
{
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_purchase_order` WHERE `po_number` LIKE '$prefix%'";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $purchaseOrderNumber = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $purchaseOrderNumber;
    }

    return null; // Return null on error
}




function SetTempPOFinish($link, $purchaseOrderNumber, $invoice_status)
{
    $sql = "UPDATE `transaction_purchase_order` SET  `invoice_status` = ? WHERE `po_number` LIKE '$purchaseOrderNumber'";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $invoice_status);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Updated successfully', 'po_number' => $purchaseOrderNumber);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'po_number' => $purchaseOrderNumber);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'po_number' => $purchaseOrderNumber);
    }
    return $error;
}

function AddToPurchaseOrder($link, $UserName, $ProductID, $Quantity, $po_number, $OrderUnit, $OrderRate)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;
    $created_by = $UserName; // Assuming created_by is the username, you may need to adjust this based on your requirements.

    $sql = "SELECT `id` FROM `transaction_purchase_order_items` WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$ProductID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `transaction_purchase_order_items` SET `product_id` = ? , `quantity` = ?, `order_unit` = ?, `order_rate` = ?, `is_active` = ?, `created_by` = ?, `created_at` = ?, `po_number` = ? WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$ProductID'";
    } else {
        $sql = "INSERT INTO `transaction_purchase_order_items` (`product_id`, `quantity`, `order_unit`, `order_rate`, `is_active`, `created_by`, `created_at`, `po_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $ProductID, $Quantity, $OrderUnit, $OrderRate,  $is_active, $created_by, $current_time, $po_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Purchase Order Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

function CreatePurchaseOrder($link, $po_number, $location_id, $supplier_id, $currency, $tax_type, $sub_total, $created_by, $is_active, $po_status, $remarks)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "SELECT `id` FROM `transaction_purchase_order` WHERE `po_number` LIKE ?";
    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "s", $po_number);
    mysqli_stmt_execute($stmt_sql);
    mysqli_stmt_store_result($stmt_sql);

    if (mysqli_stmt_num_rows($stmt_sql) > 0) {
        $sql = "UPDATE `transaction_purchase_order` SET `po_number` = ?,`location_id` = ?, `supplier_id` = ?, `currency` = ?, `tax_type` = ?, `sub_total` = ?, `created_by` = ?, `created_at` = ?, `is_active` = ?, `po_status` = ?, `remarks` = ? WHERE `po_number` LIKE '$po_number";
    } else {
        $sql = "INSERT INTO `transaction_purchase_order` (`po_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `created_by`, `created_at`, `is_active`, `po_status`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    mysqli_stmt_close($stmt_sql);

    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $po_number, $location_id, $supplier_id, $currency, $tax_type, $sub_total, $created_by, $current_time, $is_active, $po_status, $remarks);

    if (mysqli_stmt_execute($stmt_sql)) {
        $error = array('status' => 'success', 'message' => 'Purchase Order Saved successfully', 'po_number' => $po_number);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'po_number' => $po_number);
    }

    mysqli_stmt_close($stmt_sql);

    return json_encode($error);
}


function GetPurchaseOrders($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `po_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `created_by`, `created_at`, `is_active`, `po_status`, `remarks` FROM `transaction_purchase_order` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['po_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetPurchaseOrderByID($link, $po_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `po_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `created_by`, `created_at`, `is_active`, `po_status`, `remarks` FROM `transaction_purchase_order` WHERE `po_number` LIKE '$po_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult[0];
}



function GetPurchaseOrderItems($link, $po_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `product_id`, `quantity`, `order_unit`, `order_rate`, `created_by`, `created_at`, `is_active`, `po_number` FROM `transaction_purchase_order_items` WHERE `po_number` LIKE '$po_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetCompanyInfo($link)
{
    $ArrayResult = array();
    $sql = " SELECT `id`, `company_name`, `company_address`, `company_address2`, `company_city`, `company_postalcode`, `company_email`, `company_telephone`, `company_telephone2`, `owner_name`, `job_position`, `description`, `vision`, `mission`, `founder_message`, `org_logo`, `founder_photo`, `website` FROM `company`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult[0];
}


function AddToTempGRN($link, $UserName, $ProductID, $Quantity, $po_number, $OrderUnit, $OrderRate, $received_qty)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;
    $created_by = $UserName; // Assuming created_by is the username, you may need to adjust this based on your requirements.

    $sql = "SELECT `id` FROM `temp_good_receive_note` WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$ProductID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `temp_good_receive_note` SET `product_id` = ? , `quantity` = ?, `order_unit` = ?, `order_rate` = ?, `is_active` = ?, `created_by` = ?, `created_at` = ?, `po_number` = ?, `received_qty` = ? WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$ProductID'";
    } else {
        $sql = "INSERT INTO `temp_good_receive_note` (`product_id`, `quantity`, `order_unit`, `order_rate`, `is_active`, `created_by`, `created_at`, `po_number`, `received_qty`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssss", $ProductID, $Quantity, $OrderUnit, $OrderRate,  $is_active, $created_by, $current_time, $po_number, $received_qty);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Purchase Order Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

function UpdateGRNQty($link, $created_by, $ProductID, $po_number, $receivedQty)
{
    $error = array();

    $sql = "UPDATE `temp_good_receive_note` SET `received_qty` = ? WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$ProductID' AND `created_by` LIKE '$created_by'";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $receivedQty);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Record Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}



function ClearTempGRN($link, $UserName)
{
    $sql = "DELETE FROM `temp_good_receive_note` WHERE `created_by` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $UserName);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Order Cleared successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function GetTempGRNItems($link, $UserName)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `product_id`, `quantity`, `order_unit`, `order_rate`, `created_by`, `created_at`, `is_active`, `po_number`, `received_qty`, `po_number` FROM `temp_good_receive_note` WHERE `created_by` LIKE '$UserName'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


function ProcessGRN($link, $grn_number, $location_id, $supplier_id, $currency, $tax_type, $sub_total, $created_by, $is_active, $grn_status, $remarks, $grand_total, $tax_value, $payment_status, $po_number)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "SELECT `id` FROM `transaction_good_receive_note` WHERE `grn_number` LIKE ?";
    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "s", $grn_number);
    mysqli_stmt_execute($stmt_sql);
    mysqli_stmt_store_result($stmt_sql);

    if (mysqli_stmt_num_rows($stmt_sql) > 0) {
        $sql = "UPDATE `transaction_good_receive_note` SET `grn_number` = ?, `location_id` = ?, `supplier_id` = ?, `currency` = ?, `tax_type` = ?, `sub_total` = ?, `tax_value` = ?, `grand_total` = ?, `created_by` = ?, `created_at` = ?, `is_active` = ?, `grn_status` = ?, `remarks` = ?, `payment_status` = ?, `po_number` = ? WHERE `grn_number` LIKE '$grn_number";
    } else {
        $sql = "INSERT INTO `transaction_good_receive_note` (`grn_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `tax_value`, `grand_total`, `created_by`, `created_at`, `is_active`, `grn_status`, `remarks`, `payment_status`, `po_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    mysqli_stmt_close($stmt_sql);

    $stmt_sql = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt_sql, "sssssssssssssss", $grn_number, $location_id, $supplier_id, $currency, $tax_type, $sub_total, $tax_value, $grand_total, $created_by, $current_time, $is_active, $grn_status, $remarks, $payment_status, $po_number);

    if (mysqli_stmt_execute($stmt_sql)) {
        $error = array('status' => 'success', 'message' => 'GRN Saved successfully', 'grn_number' => $grn_number);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'grn_number' => $grn_number);
    }

    mysqli_stmt_close($stmt_sql);

    return json_encode($error);
}


function isGRNExists($link, $grn_number)
{
    $sql = "SELECT COUNT(*) FROM `transaction_good_receive_note` WHERE `grn_number` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $purchaseOrderNumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}

function generateGRNNumber($link, $prefix)
{
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_good_receive_note` WHERE `grn_number` LIKE '$prefix%'";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $generated_number = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $generated_number;
    }

    return null; // Return null on error
}



function AddToGRN($link, $UserName, $ProductID, $OrderUnit, $OrderRate, $received_qty, $grn_number, $po_number)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;
    $created_by = $UserName; // Assuming created_by is the username, you may need to adjust this based on your requirements.

    $sql = "SELECT `id` FROM `transaction_good_receive_note_items` WHERE `grn_number` LIKE '$grn_number' AND `product_id` LIKE '$ProductID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `transaction_good_receive_note_items` SET  `product_id` = ?,  `order_unit` = ?, `order_rate` = ?, `created_by` = ?, `created_at` = ?, `is_active` = ?, `grn_number` = ?, `received_qty` = ?, `po_number` = ? WHERE `grn_number` LIKE '$grn_number' AND `product_id` LIKE '$ProductID'";
    } else {
        $sql = "INSERT INTO `transaction_good_receive_note_items` ( `product_id`, `order_unit`, `order_rate`, `created_by`, `created_at`, `is_active`, `grn_number`, `received_qty`, `po_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssss", $ProductID, $OrderUnit, $OrderRate,  $created_by, $current_time,  $is_active, $grn_number, $received_qty, $po_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'GRN Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function GetGRNList($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `grn_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `tax_value`, `grand_total`, `created_by`, `created_at`, `is_active`, `grn_status`, `remarks`, `payment_status`, `po_number` FROM `transaction_good_receive_note` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['grn_number']] = $row;
        }
    }
    return $ArrayResult;
}


function GetGRNByID($link, $grn_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `grn_number`, `location_id`, `supplier_id`, `currency`, `tax_type`, `sub_total`, `tax_value`, `grand_total`, `created_by`, `created_at`, `is_active`, `grn_status`, `remarks`, `payment_status`, `po_number` FROM `transaction_good_receive_note` WHERE `grn_number` LIKE '$grn_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult[0];
}


function GetGRNItems($link, $grn_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `product_id`, `order_unit`, `order_rate`, `created_by`, `created_at`, `is_active`, `grn_number`, `received_qty`, `po_number` FROM `transaction_good_receive_note_items` WHERE `grn_number` LIKE '$grn_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetGRNItemCountByPO($link, $po_number, $product_code)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`received_qty`) AS `total_grn_qty` FROM `transaction_good_receive_note_items` WHERE `po_number` LIKE '$po_number' AND `product_id` LIKE '$product_code'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['total_grn_qty'];
        }
    }
    return $ArrayResult;
}


function RemoveFromTempGRN($link, $LoggedUser, $ProductID, $po_number)
{
    $sql = "DELETE FROM `temp_good_receive_note` WHERE `created_by` = ? AND `product_id` = ? AND `po_number` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $LoggedUser, $ProductID, $po_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Order Item Cleared successfully' . $sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function GetStockBalanceByProductByLocation($link, $product_code, $location_id)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`quantity`) AS `credit_count` FROM `transaction_stock_entry` WHERE `location_id` LIKE '$location_id' AND `product_id` LIKE '$product_code' AND `type` LIKE 'CREDIT' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $credit_count = $row['credit_count'];
        }
    }

    $sql = "SELECT SUM(`quantity`) AS `debit_count` FROM `transaction_stock_entry` WHERE `location_id` LIKE '$location_id' AND `product_id` LIKE '$product_code' AND `type` LIKE 'DEBIT'  AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $debit_count = $row['debit_count'];
        }
    }

    $ArrayResult = $debit_count - $credit_count;

    return $ArrayResult;
}

function GetStockBalanceByProduct($link, $product_code)
{
    $ArrayResult = 0;
    $sql = "SELECT SUM(`quantity`) AS `credit_count` FROM `transaction_stock_entry` WHERE `product_id` LIKE '$product_code' AND `type` LIKE 'CREDIT' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $credit_count = $row['credit_count'];
        }
    }

    $sql = "SELECT SUM(`quantity`) AS `debit_count` FROM `transaction_stock_entry` WHERE `product_id` LIKE '$product_code' AND `type` LIKE 'DEBIT'  AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $debit_count = $row['debit_count'];
        }
    }

    $ArrayResult = $debit_count - $credit_count;

    return $ArrayResult;
}



require __DIR__ . '/../vendor/autoload.php';

// Place the 'use' statements at the beginning of the file or after the opening PHP tag
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;

function GenerateNormalBarcode($Code)
{
    // Create a BarcodeGenerator object for Code 128
    $generator = new BarcodeGeneratorPNG();

    // Set the content (data) for the barcode
    $content = $Code; // Use the function parameter $Code

    // Generate the barcode image
    $barcode = $generator->getBarcode($content, $generator::TYPE_CODE_128);

    // Encode the barcode image as base64
    $base64 = base64_encode($barcode);

    // Return or do something with $base64
    return $base64;
}

function GenerateHighResolutionBarcode($Code)
{
    // Create a BarcodeGenerator object for Code 128
    $generator = new BarcodeGeneratorPNG();

    // Set the content (data) for the barcode
    $content = $Code; // Use the function parameter $Code

    // Set the dimensions of the barcode image
    $width = 600; // Set the width of the barcode
    $height = 200; // Set the height of the barcode

    // Generate the barcode image
    $barcode = $generator->getBarcode($content, $generator::TYPE_CODE_128, $width, $height);

    // Convert the image to base64
    $base64 = base64_encode($barcode);

    // Return or do something with $base64
    return $base64;
}


function SaveRecipe($link, $main_product, $recipe_product, $quantity, $created_by)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `transaction_recipe` (`main_product`, `recipe_product`, `qty`, `created_by`, `created_at`) VALUES (?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $main_product, $recipe_product, $quantity,  $created_by, $current_time);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Recipe Saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}



function ClearRecipe($link, $main_product)
{
    $sql = "DELETE FROM `transaction_recipe` WHERE `main_product` = ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $main_product);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Recipe Cleared successfully' . $sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}



function GetItemRecipe($link, $product_id)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_recipe` WHERE `main_product` LIKE '$product_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function updateCostPrice($link, $product_id, $amount)
{
    $sql = "UPDATE `master_product` SET `cost_price` = ? WHERE `product_id` LIKE ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ss", $amount, $product_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cost Price updated successfully' . $sql);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}


function GetCostPrice($link, $product_id)
{
    $ArrayResult = 0;
    $sql = "SELECT `cost_price` FROM `master_product` WHERE `product_id` LIKE '$product_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['cost_price'];
        }
    }
    return $ArrayResult;
}


function GetSellingPrice($link, $product_id)
{
    $ArrayResult = 0;
    $sql = "SELECT `selling_price` FROM `master_product` WHERE `product_id` LIKE '$product_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['selling_price'];
        }
    }
    return $ArrayResult;
}


function GetStewards($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by` FROM `user_accounts` WHERE `acc_type` LIKE 'Steward' ORDER BY `user_status` DESC, `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['user_name']] = $row;
        }
    }
    return $ArrayResult;
}


function GetRemovalNotices($link)
{

    $ArrayResult = array();
    $sql = " SELECT * FROM `transaction_removal_remark` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function SaveRemovalNotice($link, $ref_id, $remark, $user_id, $created_by, $location_id, $product_id, $itemQuantity)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `transaction_removal_remark` (`ref_id`, `remark`, `user_id`, `created_by`, `created_at`, `location_id`, `product_id`, `item_quantity`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $ref_id, $remark, $user_id,  $created_by, $current_time, $location_id, $product_id, $itemQuantity);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Remark Saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}




function CancelInvoice($link, $invoice_number)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // If the stock entry doesn't exist, insert a new one
    $sql = "UPDATE `transaction_invoice` SET `is_active` = 0 WHERE `invoice_number` LIKE ?";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $invoice_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Invoice Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

function CancelReceipt($link, $rec_number)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // If the stock entry doesn't exist, insert a new one
    $sql = "UPDATE `transaction_receipt` SET `is_active` = 0 WHERE `rec_number` LIKE ?";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "s", $rec_number);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Receipt Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}

function CancelStockEntryReceipt($link, $invoice_number, $product_id)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // If the stock entry doesn't exist, insert a new one
    $sql = "UPDATE `transaction_stock_entry` SET `is_active` = 0 WHERE `ref_id` LIKE ? AND `product_id` LIKE ?";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ss", $invoice_number, $product_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Stock Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}


function CancellationEntry($link, $cancellation_type,  $created_by, $ref_key, $reason)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    // If the stock entry doesn't exist, insert a new one
    $sql = "INSERT INTO `transaction_cancellation`( `cancellation_type`, `ref_key`, `reason`, `created_by`, `created_at`) VALUES (?, ?, ?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $cancellation_type, $ref_key, $reason, $created_by, $current_time);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Cancellation Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}




function checkCancelStatus($link, $refKey)
{
    $sql = "SELECT COUNT(*) FROM `transaction_cancellation` WHERE `ref_key` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $refKey);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}


function GetProductionNoteItems($link, $pn_number)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_production_items` WHERE `pn_id` LIKE '$pn_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}
function getAllProductionNote($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_production` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['pn_number']] = $row;
        }
    }
    return $ArrayResult;
}

function getProductionNote($link, $pn_number)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_production`WHERE `pn_number` LIKE '$pn_number' ";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['pn_number']] = $row;
        }
    }
    return $ArrayResult;
}


function CreateProductionNote($link, $production_cost, $location_id, $created_by, $remark, $production_date, $pn_number, $is_active)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $preCheck = getProductionNote($link, $pn_number);
    if (count($preCheck) > 0) {
        $sql = "UPDATE `transaction_production` SET `production_cost` = ?, `location_id` = ?, `created_by` = ?, `created_at` = ?, `remark` = ?, `production_date` = ?, `pn_number` = ?, `is_active` = ? WHERE `pn_number` LIKE '$pn_number'";
    } else {
        // If the stock entry doesn't exist, insert a new one
        $sql = "INSERT INTO `transaction_production`( `production_cost`, `location_id`, `created_by`, `created_at`, `remark`, `production_date`, `pn_number`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }


    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $production_cost, $location_id, $created_by, $current_time, $remark, $production_date, $pn_number, $is_active);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Production Note saved successfully', 'pn_number' => $pn_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'pn_number' => $pn_number);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'pn_number' => $pn_number);
    }

    return json_encode($error);
}

function preCheckPNItems($link, $pn_number, $product_id)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `product_id`, `quantity`, `cost_price`, `pn_id`, `created_at`, `created_by` FROM `transaction_production_items` WHERE `pn_id` LIKE '$pn_number' AND `product_id` LIKE '$product_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


function CreateProductionNoteItems($link, $product_id,  $quantity, $cost_price, $pn_number, $created_by, $is_active)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $preCheck = preCheckPNItems($link, $pn_number, $product_id);
    if (count($preCheck) > 0) {
        // If the stock entry doesn't exist, insert a new one
        $sql = "UPDATE `transaction_production_items` SET `product_id` = ?, `quantity` = ?, `cost_price` = ?, `pn_id` = ?, `created_at` = ?, `created_by` = ?, `is_active` = ? WHERE `pn_number` LIKE '$pn_number' AND `product_id` LIKE '$product_id'";
    } else {
        // If the stock entry doesn't exist, insert a new one
        $sql = "INSERT INTO `transaction_production_items`( `product_id`, `quantity`, `cost_price`, `pn_id`, `created_at`, `created_by`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssss", $product_id, $quantity, $cost_price, $pn_number, $current_time, $created_by, $is_active);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Production Note Item saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }

    return json_encode($error);
}



function isPONumberExists($link, $pn_number)
{
    $sql = "SELECT COUNT(*) FROM `transaction_production` WHERE `pn_number` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $pn_number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}


function generatePNNumber($link)
{

    $prefix = "PN";
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_production`";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $invoiceNumber = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }

    return null; // Return null on error
}


function getQuotations($link)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `quote_number`, `quote_date`, `quote_amount`, `grand_total`, `discount_amount`, `discount_percentage`, `customer_code`, `service_charge`, `close_type`, `invoice_status`, `current_time`, `location_id`, `created_by`, `is_active`, `cost_value`, `remark` FROM `transaction_quotation`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['quote_number']] = $row;
        }
    }
    return $ArrayResult;
}

function getQuotationsItems($link, $quote_number)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `hold_status`, `table_id`, `quote_number`, `cost_price` FROM `transaction_quotation_items` WHERE `quote_number` LIKE '$quote_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}



function isQuoteExist($link, $quote_number)
{
    $sql = "SELECT COUNT(*) FROM `transaction_quotation` WHERE `quote_number` = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $quote_number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0; // If count is greater than 0, the invoice number exists
    } else {
        return false; // Error in preparing the statement
    }
}


function generateQuoteNumber($link)
{

    $prefix = "QT";
    // Query to count existing invoice numbers
    $sql = "SELECT COUNT(*) AS count FROM `transaction_quotation`";

    $result = $link->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;

        // Generate the next invoice number with the prefix
        $invoiceNumber = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }

    return null; // Return null on error
}



function AddToQuote($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity, $TableID, $quote_number)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;

    $cost_price = GetCostPrice($link, $ProductID);

    $sql = "SELECT `id` FROM `transaction_quotation_items` WHERE `quote_number` LIKE '$quote_number' AND `product_id` LIKE '$ProductID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `transaction_quotation_items` SET  `user_id` = ?, `product_id` = ?, `item_price` = ?, `item_discount` = ?, `quantity` = ?, `added_date` = ?, `is_active` = ?, `customer_id` = ?, `table_id` = ?, `quote_number` = ?, `cost_price` = ? WHERE `quote_number` LIKE '$quote_number' AND `product_id` LIKE '$ProductID'";
    } else {
        $sql = "INSERT INTO `transaction_quotation_items` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `table_id`, `quote_number`, `cost_price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssssss", $UserName, $ProductID, $ItemPrice, $ItemDiscount, $Quantity, $current_time, $is_active, $CustomerID, $TableID, $quote_number, $cost_price);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Quotation Updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}

function CreateQuote($link, $quote_number, $quote_date, $quote_amount, $grand_total, $discount_amount, $discount_percentage, $customer_code, $service_charge, $invoice_status, $location_id, $created_by, $is_active, $cost_value, $remark)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "SELECT `id` FROM `transaction_quotation` WHERE `quote_number` LIKE '$quote_number'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `transaction_quotation` SET  `quote_number` = ?, `quote_date` = ?, `quote_amount` = ?, `grand_total` = ?, `discount_amount` = ?, `discount_percentage` = ?, `customer_code` = ?, `service_charge` = ?, `invoice_status` = ?, `current_time` = ?, `location_id` = ?, `created_by` = ?, `is_active` = ?, `cost_value` = ?, `remark` = ? WHERE `quote_number` LIKE '$quote_number'";
    } else {
        $sql = "INSERT INTO `transaction_quotation` (`quote_number`, `quote_date`, `quote_amount`, `grand_total`, `discount_amount`, `discount_percentage`, `customer_code`, `service_charge`, `invoice_status`, `current_time`, `location_id`, `created_by`, `is_active`, `cost_value`, `remark`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssssssssssss", $quote_number, $quote_date, $quote_amount, $grand_total, $discount_amount, $discount_percentage, $customer_code, $service_charge, $invoice_status, $current_time, $location_id, $created_by, $is_active,  $cost_value, $remark);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Quotation Saved successfully', 'quote_number' => $quote_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'quote_number' => $quote_number);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error, 'quote_number' => $quote_number);
    }
    return json_encode($error);
}

function getPageTable($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `page_table`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetUserPrivileges($link, $userName, $pageID)
{
    $ArrayResult = array();
    $sql = "SELECT `id`, `user_name`, `read`, `write`, `all`, `updated_at`, `updated_by`, `page_name` FROM `user_previleges` WHERE `user_name` LIKE '$userName' AND `page_name` LIKE '$pageID'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['user_name']] = $row;
        }
    }
    return $ArrayResult;
}

function updatePrivilege($link, $userName, $pageID, $accessMode, $loggedUser)
{

    echo $accessMode;
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    $sql = "SELECT * FROM `user_previleges` WHERE `user_name` LIKE '$userName' AND `page_name` LIKE '$pageID'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $readAccess = $row['read'];
            $writeAccess = $row['write'];
            $AllAccess = $row['all'];
        }

        if ($accessMode == "read") {
            if ($readAccess == 1) {
                $readAccess = 0;
            } else {
                $readAccess = 1;
            }
        }

        if ($accessMode == "write") {
            if ($writeAccess == 1) {
                $writeAccess = 0;
            } else {
                $writeAccess = 1;
            }
        }

        if ($accessMode == "all") {
            if ($AllAccess == 1) {
                $AllAccess = 0;
            } else {
                $AllAccess = 1;
            }
        }

        $sql = "UPDATE `user_previleges` SET  `user_name` = ?, `read` = ?, `write` = ?, `all` = ?, `updated_at` = ?, `updated_by` = ?, `page_name`  = ? WHERE `user_name` LIKE '$userName' AND `page_name` LIKE '$pageID'";
    } else {

        $readAccess = $writeAccess = $AllAccess = 0;

        if ($accessMode == "read") {
            $readAccess = 1;
        }
        if ($accessMode == "write") {
            $writeAccess = 1;
        }
        if ($accessMode == "all") {
            $AllAccess = 1;
        }

        $sql = "INSERT INTO `user_previleges` (`user_name`, `read`, `write`, `all`, `updated_at`, `updated_by`, `page_name`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssssss",  $userName, $readAccess, $writeAccess, $AllAccess, $current_time, $loggedUser, $pageID);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Privilege Saved successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ');
    }
    return json_encode($error);
}


function GetCustomerCreditLimit($link, $customer_id)
{

    $ArrayResult = 0;
    $sql = "SELECT `credit_limit` FROM `master_customer` WHERE `customer_id` LIKE '$customer_id'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['credit_limit'];
        }
    }
    return $ArrayResult;
}




function getCustomerBalance($link, $customerId)
{
    $customerBalance = 0;
    $sql = "SELECT SUM(`inv_amount`) AS `invoiceTotal` FROM `transaction_invoice` WHERE `is_active` LIKE 1 AND `invoice_status` LIKE 2 AND `customer_code` LIKE '$customerId'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoiceTotal = $row['invoiceTotal'];
        }
    }

    $returnValue =  GetCustomerReturnAmount($customerId);


    $sql = "SELECT SUM(`amount`) AS `receiptAmount` FROM `transaction_receipt` WHERE `is_active` LIKE 1 AND `customer_id` LIKE '$customerId'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $receiptAmount = $row['receiptAmount'];
        }
    }

    $customerBalance = $invoiceTotal - $receiptAmount - $returnValue;
    return $customerBalance;
}

function getCustomerBalanceDetailed($link, $customerId)
{
    $customerBalance = 0;
    $sql = "SELECT SUM(`inv_amount`) AS `invoiceTotal` FROM `transaction_invoice` WHERE `is_active` LIKE 1 AND `invoice_status` LIKE 2 AND `customer_code` LIKE '$customerId'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoiceTotal = $row['invoiceTotal'];
        }
    }

    $returnValue =  GetCustomerReturnAmount($customerId);
    $GetSettlementTotal = GetSettlementTotal($customerId);


    $sql = "SELECT SUM(`amount`) AS `receiptAmount` FROM `transaction_receipt` WHERE `is_active` LIKE 1 AND `customer_id` LIKE '$customerId'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $receiptAmount = $row['receiptAmount'];
        }
    }

    $customerBalance = $invoiceTotal - $receiptAmount;
    $balanceArray = array('customerBalance' => $customerBalance, 'invoiceTotal' => $invoiceTotal, 'receiptAmount' => $receiptAmount, 'returnValue' => $returnValue, 'GetSettlementTotal' => $GetSettlementTotal);

    return $balanceArray;
}



function GetRegions($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `customer_region` ";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetRoutes($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `customer_route` ";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetAreas($link)
{
    $ArrayResult = array();
    $sql = "SELECT * FROM `customer_area` ";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function MostUsedDiscounts($link, $ProductID)
{
    $ArrayResult = array();
    $sql = "SELECT `product_id`, `item_discount`, COUNT(`item_discount`) AS discount_count  FROM `transaction_invoice_items` WHERE `is_active` LIKE 1 AND `item_discount` != 0 AND `product_id` LIKE '$ProductID' GROUP BY `product_id`, `item_discount` ORDER BY item_discount DESC, discount_count DESC LIMIT 5;";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function GetDepartmentsBySection($link, $sectionId)
{
    $ArrayResult = array();

    $sql = "SELECT `id`, `section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_departments` WHERE `section_id` LIKE '$sectionId'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}



function GetCategoryBySectionDepartment($link, $sectionId, $departmentId)
{
    $ArrayResult = array();

    $sql = "SELECT `id`, `section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_categories` WHERE `section_id` LIKE '$sectionId' AND `department_id` LIKE '$departmentId'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetBankList($link)
{
    $ArrayResult = array();

    $sql = "SELECT * FROM `master_banks`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['bank_id']] = $row;
        }
    }
    return $ArrayResult;
}


function RemovedItemsByInvoice($link, $invNumber)
{
    $ArrayResult = array();

    $sql = "SELECT `id`, `ref_id`, `remark`, `user_id`, `created_by`, `created_at`, `location_id`, `product_id`, SUM(item_quantity) AS `item_quantity` FROM `transaction_removal_remark` WHERE `ref_id` LIKE '$invNumber' GROUP BY `product_id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}



function generateRTNNumber()
{
    // Query to get the count of transactions for the current date
    global $pdo; // assuming you have a PDO connection object

    $sql = "SELECT COUNT(*) as count FROM transaction_return";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Increment the count by 1 and pad with leading zeros to ensure 3 digits
    $incrementalNumber = str_pad($rowCount + 1, 4, '0', STR_PAD_LEFT);

    // Generate the RTN number
    $rtnNumber = "RTN" . $incrementalNumber;

    return $rtnNumber;
}

// Function to insert data into transaction_return table and return the status
function insertIntoReturnTable($customer_id, $location_id, $updated_by, $reason, $refund_id, $tableData, $isActive, $ref_invoice, $return_amount)
{
    global $pdo;

    try {
        $current_time = date("Y-m-d H:i:s");
        $rtnNumber = generateRTNNumber();

        $stmt = $pdo->prepare("INSERT INTO transaction_return (rtn_number, customer_id, location_id, created_at, updated_by, reason, refund_id, is_active, ref_invoice, return_amount) VALUES (:rtn_number, :customer_id, :location_id, :created_at, :updated_by, :reason, :refund_id, :is_active, :ref_invoice, :return_amount)");

        $stmt->bindParam(':rtn_number', $rtnNumber);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':location_id', $location_id);
        $stmt->bindParam(':created_at', $current_time);
        $stmt->bindParam(':updated_by', $updated_by);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':refund_id', $refund_id);
        $stmt->bindParam(':is_active', $isActive);
        $stmt->bindParam(':ref_invoice', $ref_invoice);
        $stmt->bindParam(':return_amount', $return_amount);

        $stmt->execute();

        if (!empty($tableData)) {
            foreach ($tableData as $selectedRaw) {

                $product_id = $selectedRaw['productId'];
                $item_rate = $selectedRaw['rate'];
                $item_qty = $selectedRaw['qty'];

                $insertResult = insertReturnItems($rtnNumber, $location_id, $product_id, $item_rate, $item_qty, $updated_by, $isActive);
            }
        }

        return array('status' => 'success', 'message' => 'Return Note Saved successfully', 'rtnNumber' => $rtnNumber);
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}

// Function to insert product items into the transaction_return_items table
function insertReturnItems($rtn_number, $location_id, $product_id, $item_rate, $item_qty, $updated_by, $isActive)
{
    global $pdo;
    global $link;

    $Products = GetProducts($link);
    $current_time = date("Y-m-d H:i:s");
    try {
        // Prepare the SQL statement for insertion
        $stmt = $pdo->prepare("INSERT INTO transaction_return_items (rtn_number, location_id, product_id, item_rate, item_qty, updated_at, update_by, is_active) 
                               VALUES (:rtn_number, :location_id, :product_id, :item_rate, :item_qty, :updated_at, :updated_by, :is_active)");

        // Bind parameters and execute the statement
        $stmt->bindParam(':rtn_number', $rtn_number);
        $stmt->bindParam(':location_id', $location_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':item_rate', $item_rate);
        $stmt->bindParam(':item_qty', $item_qty);
        $stmt->bindParam(':updated_by', $updated_by);
        $stmt->bindParam(':updated_at', $current_time);
        $stmt->bindParam(':is_active', $isActive);
        $stmt->execute();

        $type = "DEBIT";

        $sub_product_name = $Products[$product_id]['product_name'];
        $reference = $type . " : " . $item_qty . " " . $sub_product_name . "(s) is Debited to " . $rtn_number . " | Return Debit";
        $stockEntryResult = CreateStockEntry($link, $type, $item_qty, $product_id, $reference, $location_id, $updated_by, $isActive, $rtn_number);

        return array('status' => 'success', 'message' => 'Item inserted successfully');
    } catch (PDOException $e) {
        // Handle errors, you can log or throw the exception

        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function GetReturns()
{

    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `rtn_number`, `customer_id`, `location_id`, `created_at`, `updated_by`, `reason`, `refund_id`, `is_active`, `ref_invoice`, `return_amount` FROM `transaction_return`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['rtn_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetCustomerReturnAmount($customerId)
{
    global $pdo;
    global $link;
    $returnValue = 0;

    $sql = "SELECT SUM(`return_amount`) AS `return_amount` FROM `transaction_return` WHERE `customer_id` LIKE '$customerId' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $returnValue = $row['return_amount'];
        }
    }
    return $returnValue;
}

function GetUnsettledReturns($customerId)
{
    global $pdo;
    global $link;

    // Fetch return settlements outside the main loop
    $returnSettlements = GetReturnSettlements($customerId);

    $ArrayResult = array();

    // Construct SQL query to fetch transaction returns for the given customer
    $sql = "SELECT `id`, `rtn_number`, `customer_id`, `location_id`, `created_at`, `updated_by`, `reason`, `refund_id`, `is_active`, `ref_invoice`, `return_amount` FROM `transaction_return` WHERE `customer_id` = ?";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customerId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through the fetched rows
    foreach ($rows as $row) {
        $settlementAmount = 0;

        // Check if there are return settlements for the current return number
        foreach ($returnSettlements as $selectedArray) {
            $settleRtnNumber = $selectedArray['rtn_number'];

            if ($row['rtn_number'] == $settleRtnNumber) {
                $settlementAmount += $selectedArray['settled_amount'];
            }
        }

        // Only add the return to the result array if settlement amount is less than or equal to return amount
        if ($settlementAmount <= $row['return_amount']) {
            $ArrayResult[$row['rtn_number']] = $row;
        }
    }

    return $ArrayResult;
}

function GetSettledAmount($rtn_number)
{

    global $pdo;
    global $link;

    $ArrayResult = 0;

    $sql = "SELECT SUM(`settled_amount`) AS `settled_amount` FROM `translation_return_settlement` WHERE `is_active` LIKE 1 AND `rtn_number` LIKE '$rtn_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['settled_amount'];
        }
    }
    return $ArrayResult;
}

function GetSettlementTotal($customerId)
{
    global $pdo;
    global $link;

    $ArrayResult = 0;

    $sql = "SELECT SUM(`settled_amount`) AS `settled_amount` FROM `translation_return_settlement` WHERE `is_active` LIKE 1 AND `customer_id` LIKE '$customerId'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['settled_amount'];
        }
    }
    return $ArrayResult;
}

function GetReturnSettlements($customerId)
{
    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `rtn_number`, `invoice_number`, `customer_id`, `settled_amount`, `is_active`, `updated_by`, `updated_at`, `location_id` FROM `translation_return_settlement` WHERE `is_active` LIKE 1 AND `customer_id` LIKE '$customerId'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetInvoiceSettlement($invoiceNumber)
{
    global $pdo;
    global $link;
    $returnValue = 0;

    $sql = "SELECT SUM(`settled_amount`) AS `settled_amount` FROM `translation_return_settlement` WHERE `invoice_number` LIKE '$invoiceNumber' AND `is_active` LIKE 1";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $returnValue = $row['settled_amount'];
        }
    }
    return $returnValue;
}

function GetUnsettledReturnBalance($customerId)
{

    global $pdo;
    global $link;
    $returnValue = 0;

    $sql = "SELECT SUM(`return_amount`) AS `return_amount` FROM `transaction_return` WHERE `customer_id` LIKE '$customerId' AND `is_active` LIKE 1 AND (`settled_invoice` = '' OR `settled_invoice` IS NULL)";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $returnValue = $row['return_amount'];
        }
    }
    return $returnValue;
}

function GetReturnItems($rtn_number)
{

    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `rtn_number`, `location_id`, `product_id`, `item_rate`, SUM(`item_qty`) AS `item_qty`, `updated_at`, `update_by`, `is_active` FROM `transaction_return_items` WHERE `rtn_number` LIKE '$rtn_number' GROUP BY `product_id`,`item_rate`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetReturnItemsPrint($rtn_number)
{

    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `rtn_number`, `location_id`, `product_id`, `item_rate`, `item_qty`, `updated_at`, `update_by`, `is_active` FROM `transaction_return_items` WHERE `rtn_number` LIKE '$rtn_number'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetReturnItemsByInvoice($invoiceNumber)
{

    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT tri.id AS item_id, tri.rtn_number AS item_rtn_number, tri.location_id AS item_location_id, tri.product_id, tri.item_rate, SUM(tri.item_qty) AS `item_qty`, tri.updated_at AS item_updated_at, tri.update_by AS item_update_by, tri.is_active AS item_is_active, tr.id AS return_id, tr.rtn_number AS return_rtn_number, tr.customer_id, tr.location_id AS return_location_id, tr.created_at AS return_created_at, tr.updated_by AS return_updated_by, tr.reason, tr.refund_id, tr.is_active AS return_is_active, tr.ref_invoice FROM transaction_return_items tri JOIN transaction_return tr ON tri.rtn_number = tr.rtn_number WHERE tr.ref_invoice = '$invoiceNumber' GROUP BY tri.product_id, tri.item_rate;
    ";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


// Refund



function generateRefundNumber()
{
    // Query to get the count of transactions for the current date
    global $pdo; // assuming you have a PDO connection object

    $sql = "SELECT COUNT(*) as count FROM transaction_refund";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Increment the count by 1 and pad with leading zeros to ensure 3 digits
    $incrementalNumber = str_pad($rowCount + 1, 4, '0', STR_PAD_LEFT);

    // Generate the RTN number
    $rtnNumber = "RFD" . $incrementalNumber;

    return $rtnNumber;
}


// Function to insert data into transaction_return table and return the status
function insertIntoRefundTable($customer_id, $rtn_number, $refund_amount, $is_active, $update_by, $rtn_location, $current_location)
{
    global $link;
    global $pdo;
    global $cashAccountId;
    global $salesRevenueAccountId;

    try {
        $current_time = date("Y-m-d H:i:s");
        $refund_id = generateRefundNumber();

        $stmt = $pdo->prepare("INSERT INTO transaction_refund (`refund_id`, `rtn_number`, `refund_amount`, `refund_datetime`, `is_active`, `update_by`, `customer_id`, `rtn_location`, `current_location`) VALUES (:refund_id, :rtn_number, :refund_amount, :refund_datetime, :is_active, :update_by, :customer_id, :rtn_location, :current_location)");

        $stmt->bindParam(':refund_id', $refund_id);
        $stmt->bindParam(':rtn_number', $rtn_number);
        $stmt->bindParam(':refund_amount', $refund_amount);
        $stmt->bindParam(':refund_datetime', $current_time);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':update_by', $update_by);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':rtn_location', $rtn_location);
        $stmt->bindParam(':current_location', $current_location);

        $stmt->execute();
        $refundUpdate = UpdateRefundId($refund_id, $rtn_number);

        // Journal Entry (Debit then Credit)
        $CurrentLocationName = GetLocations($link)[$current_location]['location_name'];
        $description = "Refund - " . $refund_id . " @ " . $CurrentLocationName;
        $journal_entry = addDoubleEntryTransaction($salesRevenueAccountId, $cashAccountId, $refund_amount, $current_time, $description, $refund_id, $update_by, $current_location);


        return array('status' => 'success', 'message' => 'Refund Processed successfully', 'refund_id' => $refund_id, 'refundIdResult' => $refundUpdate, 'journalResult' => $journal_entry);
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function UpdateRefundId($refund_id, $rtnNumber)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE transaction_return SET `refund_id` = :refund_id WHERE rtn_number LIKE :rtn_number");
        $stmt->bindParam(':refund_id', $refund_id);
        $stmt->bindParam(':rtn_number', $rtnNumber);
        $stmt->execute();

        return array('status' => 'success', 'message' => 'Refund ID Updated successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function GetRefundList()
{

    global $pdo;
    global $link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `refund_id`, `rtn_number`, `refund_amount`, `refund_datetime`, `is_active`, `update_by`, `customer_id`, `rtn_location`, `current_location` FROM `transaction_refund`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['refund_id']] = $row;
        }
    }
    return $ArrayResult;
}



function GetExpensesTypes()
{

    global $pdo;
    global $link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `type`, `is_active` FROM `transaction_expenses_types`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetExpenseList()
{

    global $pdo;
    global $link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_expenses`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['expense_id']] = $row;
        }
    }
    return $ArrayResult;
}


function generateExpenseNumber()
{
    // Query to get the count of transactions for the current date
    global $pdo; // assuming you have a PDO connection object

    $sql = "SELECT COUNT(*) as count FROM transaction_expenses";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Increment the count by 1 and pad with leading zeros to ensure 3 digits
    $incrementalNumber = str_pad($rowCount + 1, 4, '0', STR_PAD_LEFT);

    // Generate the RTN number
    $rtnNumber = "EX" . $incrementalNumber;

    return $rtnNumber;
}


function SaveExpense($ex_type, $ex_description, $amount, $update_by, $location_id, $is_active)
{
    global $link;
    global $pdo;

    global $cashAccountId;
    global $expenseAccountId;

    try {
        $current_time = date("Y-m-d H:i:s");
        $expenseId = generateExpenseNumber();

        $stmt = $pdo->prepare("INSERT INTO transaction_expenses (`expense_id`, `ex_type`, `ex_description`, `amount`, `updated_by`, `updated_at`, `location_id`, `is_active`) VALUES (:expense_id, :ex_type, :ex_description, :amount, :updated_by, :updated_at, :location_id, :is_active)");

        $stmt->bindParam(':expense_id', $expenseId);
        $stmt->bindParam(':ex_type', $ex_type);
        $stmt->bindParam(':ex_description', $ex_description);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':updated_by', $update_by);
        $stmt->bindParam(':updated_at', $current_time);
        $stmt->bindParam(':location_id', $location_id);
        $stmt->bindParam(':is_active', $is_active);

        $stmt->execute();

        // Journal Entry (Debit then Credit)
        $CurrentLocationName = GetLocations($link)[$location_id]['location_name'];
        $description = "Expense - " . $expenseId . " @ " . $ex_description . "-" . $CurrentLocationName;
        $journal_entry = addDoubleEntryTransaction($expenseAccountId, $cashAccountId, $amount, $current_time, $description, $expenseId, $update_by, $location_id);


        return array('status' => 'success', 'message' => 'Expense Saved successfully', 'expenseId' => $expenseId, 'journalResult' => $journal_entry);
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function SaveReturnSettlement($rtn_number, $invoice_number, $settled_amount, $is_active, $updated_by, $location_id, $customer_id)
{
    global $link;
    global $pdo;

    try {
        $current_time = date("Y-m-d H:i:s");
        $expenseId = generateExpenseNumber();

        $stmt = $pdo->prepare("INSERT INTO translation_return_settlement (`rtn_number`, `invoice_number`, `settled_amount`, `is_active`, `updated_by`, `updated_at`, `location_id`, `customer_id`) VALUES (:rtn_number, :invoice_number, :settled_amount, :is_active, :updated_by, :updated_at, :location_id, :customer_id)");

        $stmt->bindParam(':rtn_number', $rtn_number);
        $stmt->bindParam(':invoice_number', $invoice_number);
        $stmt->bindParam(':settled_amount', $settled_amount);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':updated_by', $updated_by);
        $stmt->bindParam(':updated_at', $current_time);
        $stmt->bindParam(':location_id', $location_id);
        $stmt->bindParam(':customer_id', $customer_id);

        $stmt->execute();

        return array('status' => 'success', 'message' => $invoice_number . ' Settled Saved successfully', 'expenseId' => $expenseId);
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}

function GetPaymentTypes()
{

    global $pdo;
    global $link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `text`, `is_active` FROM `payment_types`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}


function generateBatchNumber($prefix, $forwardVal)
{
    // Connect to your database

    global $pdo;
    global $link;

    // SQL query to count existing batch numbers with the prefix
    $sql = "SELECT COUNT(*) AS count FROM transaction_batch WHERE batch_number LIKE '$prefix%'";

    // Execute the query
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        // If there are rows, fetch the count
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $count += $forwardVal;

        // Generate the new batch number with count + 1 and padded numeric part
        $newBatchNumber = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // If no rows found, start from 1
        $newBatchNumber = $prefix . '0001';
    }
    return $newBatchNumber;
}

// Function to save data for transaction batch
function SaveTransactionBatch($batch_number, $target_qty, $production_qty, $batch_product, $remarks, $production_date, $update_by, $cost_per_kg, $location_id, $production_cost)
{
    global $pdo; // Assuming $pdo is your PDO connection object

    try {
        $current_time = date("Y-m-d H:i:s");

        $stmt = $pdo->prepare("INSERT INTO transaction_batch (`batch_number`, `target_qty`, `production_qty`, `batch_product`, `remarks`, `production_date`, `update_by`, `update_at`, `cost_per_kg`, `location_id`, `production_cost`) VALUES (:batch_number, :target_qty, :production_qty, :batch_product, :remarks, :production_date, :update_by, :update_at, :cost_per_kg, :location_id, :production_cost)");

        $stmt->bindParam(':batch_number', $batch_number);
        $stmt->bindParam(':target_qty', $target_qty);
        $stmt->bindParam(':production_qty', $production_qty);
        $stmt->bindParam(':batch_product', $batch_product);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':production_date', $production_date);
        $stmt->bindParam(':update_by', $update_by);
        $stmt->bindParam(':update_at', $current_time);
        $stmt->bindParam(':cost_per_kg', $cost_per_kg);
        $stmt->bindParam(':location_id', $location_id);
        $stmt->bindParam(':production_cost', $production_cost);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Batch Production saved successfully', 'batchNumber' => $batch_number);
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}

// Function to save data for transaction batch item list
function SaveTransactionBatchItemList($batch_number, $product_id, $product_qty, $cost_price, $calculated_unit, $update_by, $location_id)
{
    global $pdo; // Assuming $pdo is your PDO connection object

    try {
        $current_time = date("Y-m-d H:i:s");

        $stmt = $pdo->prepare("INSERT INTO transaction_batch_item_list (`batch_number`, `product_id`, `product_qty`, `cost_price`, `calculated_unit`, `update_at`, `update_by`, `location_id`) VALUES (:batch_number, :product_id, :product_qty, :cost_price, :calculated_unit, :update_at, :update_by, :location_id)");

        $stmt->bindParam(':batch_number', $batch_number);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':product_qty', $product_qty);
        $stmt->bindParam(':cost_price', $cost_price);
        $stmt->bindParam(':calculated_unit', $calculated_unit);
        $stmt->bindParam(':update_at', $current_time);
        $stmt->bindParam(':update_by', $update_by);
        $stmt->bindParam(':location_id', $location_id);

        $stmt->execute();

        return array('status' => 'success', 'message' => 'Transaction Batch Item List saved successfully');
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage());
    }
}


function GetBatchProduction()
{

    global $pdo;
    global $link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_batch`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['batch_number']] = $row;
        }
    }
    return $ArrayResult;
}

function GetBatchProductionItems($batchNumber)
{

    global $pdo;
    global $link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `transaction_batch_item_list` WHERE `batch_number` LIKE '$batchNumber'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function GetThemeModel($userTheme = 'light')
{
    $bgColor = 'bg-light';
    if ($userTheme == 'dark') {
        $bgColor = 'bg-dark';
    }

    return $bgColor;
}



function convertToCamelCase($string)
{
    // Convert to array of words
    $words = explode(' ', strtolower($string));
    // Capitalize each word except the first one and concatenate
    $camel_case = $words[0];
    for ($i = 1; $i < count($words); $i++) {
        $camel_case .= ucfirst($words[$i]);
    }

    return $camel_case;
}

function convertToSnakeCase($string)
{
    return strtolower(str_replace(' ', '_', $string));
}

// Function to sanitize input
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function getDefaultValue($element)
{
    if (empty($element['default_value'])) {
        return $element['element_type'] === 'number' ? '' : ($element['element_type'] === 'date' ? date('Y-m-d') : '');
    }
    return $element['default_value'];
}

function getUserTheme($userThemeInput = null)
{
    if ($userThemeInput) {
        return GetThemeModel(sanitizeInput($userThemeInput));
    }
    return GetThemeModel();
}

function renderForm($elementArray, $form_name)
{

    foreach ($elementArray as $element) {
        $elementName = convertToCamelCase($element['element_name']);
        $$elementName = getDefaultValue($element);
    }

    ob_start(); // Start output buffering
?>
    <form action="#" id="<?= $form_name ?>" method="post">
        <div class="row g-3">
            <?php foreach ($elementArray as $element) :
            ?>
                <div class="<?= htmlspecialchars($element['class_list']) ?>">
                    <?php
                    $elementName = htmlspecialchars($element['element_name']);
                    $camelCase = convertToCamelCase($elementName);
                    $snakeCase = convertToSnakeCase($elementName);
                    ?>
                    <?php if ($element['element_type'] == 'select') : ?>

                        <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
                        <select <?= $element['require_status'] ?> name="<?= $snakeCase ?>" id="<?= $snakeCase ?>">
                            <?php foreach ($element['select_values'] as $data) : ?>
                                <option value="<?= htmlspecialchars($data['id']) ?>" <?= ($data['id'] == $element['default_value']) ? 'selected' : '' ?>><?= htmlspecialchars($data['value']) ?></option>
                            <?php endforeach; ?>
                        </select>

                    <?php elseif ($element['element_type'] == 'checkbox') : ?>
                        <div class="border-bottom mb-2"></div>
                        <label for="<?= $snakeCase ?>" class="mb-2"><?= $elementName ?></label>
                        <div class="row g-2">
                            <?php foreach ($element['select_values'] as $data) :
                                $isChecked = in_array($data['id'], $element['default_value']) ? 'checked' : ''; ?>
                                <div class="col-4 col-md-3">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="<?= $snakeCase ?>[]" id="<?= $snakeCase ?>" class="form-check-input" value="<?= $data['id']; ?>" <?= $isChecked ?>>
                                            <?= $data['value']; ?>
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($element['element_type'] == 'file') :
                        $tempName = 'temp' . $camelCase ?>
                        <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
                        <input <?= $element['require_status'] ?> class="form-control" type="<?= $element['element_type'] ?>" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

                        <!-- Temp File -->
                        <input class="form-control" type="hidden" value="<?= $tempName ?>" name="temp_<?= $snakeCase ?>" id="temp_<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

                    <?php else : ?>
                        <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
                        <input <?= $element['require_status'] ?> class="form-control" type="<?= $element['element_type'] ?>" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        </div>


    </form>

    <script>
        <?php foreach ($elementArray as $element) : ?>
            <?php if ($element['element_type'] == 'select') : ?>
                $('#<?= convertToSnakeCase($element['element_name']) ?>').select2();
            <?php endif; ?>
        <?php endforeach; ?>
    </script>
<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}

function convertSelectBox2DArray($inputArray, $keyValue, $valueName)
{
    $resultArray = array();

    foreach ($inputArray as $data) {
        if (is_array($valueName)) {
            $value = array();
            foreach ($valueName as $name) {
                $value[$name] = $data[$name];
            }
        } else {
            $value = $data[$valueName];
        }

        $resultArray[$data[$keyValue]] = array(
            'id' => $data[$keyValue],
            'value' => $value,
        );
    }

    return $resultArray;
}


function convertSelectBox1DArray($inputArray)
{
    $resultArray = array();
    foreach ($inputArray as $data => $value) {
        $resultArray[] = array(
            'id' => $data + 1,
            'value' => $value,
        );
    }
    return $resultArray;
}

function convertSelectBox1DArrayValueOnly($inputArray)
{
    $resultArray = array();
    foreach ($inputArray as $data => $value) {
        $resultArray[] = array(
            'id' => $value,
            'value' => $value,
        );
    }
    return $resultArray;
}

function ReturnTextInput($elementName, $requiredStatus, $classList, $defaultValue = '', $readOnlyStatus = null)
{
    $elementName = htmlspecialchars($elementName);
    $camelCase = convertToCamelCase($elementName);
    $snakeCase = convertToSnakeCase($elementName);

    $$camelCase = $defaultValue;

    ob_start(); // Start output buffering
?>
    <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
    <input <?= $requiredStatus ?> <?= $readOnlyStatus ?> class="<?= $classList ?>" type="text" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}



function ReturnNumberInput($elementName, $requiredStatus, $classList, $defaultValue = '')
{
    $elementName = htmlspecialchars($elementName);
    $camelCase = convertToCamelCase($elementName);
    $snakeCase = convertToSnakeCase($elementName);

    $$camelCase = $defaultValue;

    ob_start(); // Start output buffering
?>
    <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
    <input <?= $requiredStatus ?> class="<?= $classList ?>" type="text" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}

function ReturnDateInput($elementName, $requiredStatus, $classList, $defaultValue = null)
{
    $defaultValue = ($defaultValue == null) ? date('Y-m-d') : $defaultValue;
    $elementName = htmlspecialchars($elementName);
    $camelCase = convertToCamelCase($elementName);
    $snakeCase = convertToSnakeCase($elementName);

    $$camelCase = $defaultValue;

    ob_start(); // Start output buffering
?>
    <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
    <input <?= $requiredStatus ?> class="<?= $classList ?>" type="date" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}


function ReturnSelectInput($elementName, $requiredStatus, $classList, $dataList, $defaultValue = 0,)
{
    $elementName = htmlspecialchars($elementName);
    $camelCase = convertToCamelCase($elementName);
    $snakeCase = convertToSnakeCase($elementName);

    $$camelCase = $defaultValue;

    ob_start(); // Start output buffering
?>
    <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
    <select <?= $requiredStatus ?> name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" class="<?= $classList ?>">
        <option value="">Select <?= $elementName ?></option>
        <?php foreach ($dataList as $data) : ?>
            <option value="<?= htmlspecialchars($data['id']) ?>" <?= ($data['id'] == $defaultValue) ? 'selected' : '' ?>>
                <?php if (is_array($data['value'])) : ?>
                    <?php foreach ($data['value'] as $value) : ?>
                        <?= htmlspecialchars($value) ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?= htmlspecialchars($data['value']) ?>
                <?php endif ?>
            </option>
        <?php endforeach; ?>
    </select>

    <script>
        $('#<?= $snakeCase ?>').select2();
    </script>

<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}



function ReturnFileInput($elementName, $requiredStatus, $classList, $defaultValue = null)
{
    $elementName = htmlspecialchars($elementName);
    $camelCase = convertToCamelCase($elementName);
    $snakeCase = convertToSnakeCase($elementName);

    $$camelCase = $defaultValue;
    $tempName = 'temp' . $camelCase;
    ob_start(); // Start output buffering
?>
    <label for="<?= $snakeCase ?>"><?= $elementName ?></label>
    <input <?= $requiredStatus ?> class="<?= $classList ?>" type="file" value="<?= htmlspecialchars($$camelCase) ?>" name="<?= $snakeCase ?>" id="<?= $snakeCase ?>" placeholder="<?= $elementName ?>">

    <!-- Temp File -->
    <input class="form-control" type="hidden" value="<?= $defaultValue ?>" name="temp_<?= $snakeCase ?>" id="temp_<?= $snakeCase ?>" placeholder="<?= $elementName ?>">


<?php
    $output = ob_get_clean(); // Get the buffered output and clean the buffer
    return $output;
}
