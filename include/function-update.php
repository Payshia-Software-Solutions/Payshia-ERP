<?php
date_default_timezone_set("Asia/Colombo");

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


function GenerateIndexNumber($link)
{
    $GeneratedNumber = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);

    $sql = "SELECT `user_name` FROM `user_accounts` WHERE `user_name` LIKE '$GeneratedNumber'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        GenerateIndexNumber($link);
    } else {
        return 'JL' . $GeneratedNumber;
    }
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


function SaveUserAccount($link, $status, $first_name, $last_name, $nic, $student_number, $address_line_1, $address_line_2, $city, $postal_code, $sex, $phone_number, $email_address, $password, $user_type, $is_active, $updateKey, $company_id, $img_path, $created_by)
{

    // echo $img_path;
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($password != "") {
        if ($updateKey == 0) {
            $sql = "INSERT INTO `user_accounts` (`email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path`, `update_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $Student = GetAccounts($link)[$updateKey];
            $student_number = $Student['student_number'];

            $sql = "UPDATE `user_accounts` SET `email` = ?, `user_name` = ?, `pass` = ?, `first_name` = ?, `last_name` = ?, `sex` = ?, `addressl1` = ?, `addressl2` = ?, `city` = ?, `PNumber` = ?, `WPNumber` = ?, `created_at` = ?, `user_status` = ?, `acc_type` = ?, `img_path` = ?, `update_by` = ? WHERE `student_number` = '$updateKey'";
        }

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssss", $param_1, $param_2, $param_3, $param_4, $param_5, $param_6, $param_7, $param_8, $param_9, $param_10, $param_11, $param_12, $param_13, $param_14, $param_15, $param_16);

            $param_1 = $email_address;
            $param_2 = $student_number;
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
        $sql = "UPDATE `user_account` SET `status` = ?, `first_name` = ?, `last_name` = ?, `nic` = ?, `address_line_1` = ?, `address_line_2` = ?, `city` = ?, `postal_code` = ?, `sex` = ?, `phone_number` = ?, `email_address` = ?,`created_at` = ?, `user_type` = ?, `is_active` = ?, `company_id` = ? ,`img_path` = ?, `update_by` = ? WHERE `student_number` = '$updateKey'";

        if ($stmt_sql = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssssssssss", $status, $first_name, $last_name, $nic, $address_line_1, $address_line_2, $city, $postal_code, $sex, $phone_number, $email_address, $current_time, $user_type, $is_active, $company_id, $img_path, $update_by);



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

function GetProducts($link)
{

    $ArrayResult = array();
    $sql = "SELECT `product_id`, `product_code`, `product_name`, `display_name`, `print_name`, `section_id`, `department_id`, `category_id`, `brand_id`, `measurement`, `reoder_evel`, `lead_days`, `cost_price`, `selling_price`, `minimum_price`, `wholesale_price`, `item_type`, `item_location`, `image_path`, `created_by`, `created_at`, `active_status`, `generic_id` FROM `master_product` ORDER BY `product_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetLocations($link)
{

    $ArrayResult = array();
    $sql = "SELECT `location_id`, `location_name`, `is_active`, `created_at`, `created_by` FROM `master_location` ORDER BY `location_id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['location_id']] = $row;
        }
    }
    return $ArrayResult;
}



function SaveLocation($link, $location_name, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_location` (`location_name`, `is_active`, `created_at`, `created_by`) VALUES (?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_location` SET `location_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `location_id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssss", $location_name, $is_active, $current_time, $created_by);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Location saved successfully');
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
