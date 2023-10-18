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
    $sql = "SELECT `product_id`, `product_code`, `product_name`, `display_name`, `print_name`, `section_id`, `department_id`, `category_id`, `brand_id`, `measurement`, `reorder_level`, `lead_days`, `cost_price`, `selling_price`, `minimum_price`, `wholesale_price`, `item_type`, `item_location`, `image_path`, `created_by`, `created_at`, `active_status`, `generic_id`, `supplier_list`, `size_id`, `color_id`, `product_description` FROM `master_product` ORDER BY `active_status` DESC, `product_id` DESC";

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
    $sql = "SELECT `id`, `section_name`, `is_active`, `created_at`, `created_by` FROM `master_sections` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}



// Section 
function SaveSection($link, $section_name, $is_active, $created_by, $UpdateKey)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_sections` (`section_name`, `is_active`, `created_at`, `created_by`) VALUES (?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_sections` SET `section_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssss", $section_name, $is_active, $current_time, $created_by);
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
    $sql = "SELECT `id`, `section_id`, `department_name`, `is_active`, `created_at`, `created_by` FROM `master_departments` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveDepartment($link, $department_name, $is_active, $created_by, $UpdateKey, $section_id)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_departments` (`section_id`, `department_name`, `is_active`, `created_at`, `created_by`) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_departments` SET  `section_id` = ? ,`department_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sssss", $section_id, $department_name, $is_active, $current_time, $created_by);
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
    $sql = "SELECT `id`, `section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by` FROM `master_categories` ORDER BY `id` DESC";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function SaveCategory($link, $category_name, $is_active, $created_by, $UpdateKey, $section_id, $department_id)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_categories` (`section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by`) VALUES (?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_categories` SET  `section_id` = ?, `department_id` = ? ,`category_name` = ?, `is_active` = ?, `created_at` = ?, `created_by` = ? WHERE `id` = '$UpdateKey'";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssss", $section_id, $department_id, $category_name, $is_active, $current_time, $created_by);
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


function SaveProduct($link, $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $UpdateKey)
{
    $error = array();
    $created_at = date("Y-m-d H:i:s");

    if ($UpdateKey == 0) {
        $sql = "INSERT INTO `master_product` (`product_code`, `product_name`, `display_name`, `print_name`, `section_id`, `department_id`, `category_id`, `brand_id`, `measurement`, `reorder_level`, `lead_days`, `cost_price`, `selling_price`, `minimum_price`, `wholesale_price`, `item_type`, `item_location`, `image_path`, `created_by`, `created_at`, `active_status`, `generic_id`, `supplier_list`, `size_id`, `color_id`, `product_description`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `master_product` SET `product_code` = ?, `product_name` = ?, `display_name` = ?, `print_name` = ?, `section_id` = ?, `department_id` = ?, `category_id` = ?, `brand_id` = ?, `measurement` = ?, `reorder_level` = ?, `lead_days` = ?, `cost_price` = ?, `selling_price` = ?, `minimum_price` = ?, `wholesale_price` = ?, `item_type` = ?, `item_location` = ?, `image_path` = ?, `created_by` = ?, `created_at` = ?, `active_status` = ?, `generic_id` = ?, `supplier_list` = ?,  `size_id` = ?, `color_id`= ?, `product_description` = ? WHERE `product_id` = ?";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        if ($UpdateKey != 0) {
            mysqli_stmt_bind_param($stmt_sql, "sssssssssssssssssssssssssss", $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $created_at, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description, $UpdateKey);
        } else {
            mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssssssssssss", $product_code, $product_name, $display_name, $print_name, $section_id, $department_id, $category_id, $brand_id, $measurement, $reorder_level, $lead_days, $cost_price, $selling_price, $minimum_price, $wholesale_price, $item_type, $item_location, $image_path, $created_by, $created_at, $active_status, $generic_id, $supplier_list, $size_id, $color_id,  $product_description);
        }

        if (mysqli_stmt_execute($stmt_sql)) {
            if ($UpdateKey == 0) {
                $UpdateKey = mysqli_insert_id($link); // Get the last inserted ID
            }
            $error = array('status' => 'success', 'message' => 'Product saved successfully', 'last_inserted_id' => $UpdateKey);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later.' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. ' . $brand_id . $link->error);
    }
    return json_encode($error);
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
    $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id`, `hold_status` FROM `temp_order` WHERE `user_id` LIKE '$UserName' AND `hold_status` = 0 ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function AddToCart($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity)
{
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $is_active = 1;

    $sql = "SELECT `id`, `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id` FROM `temp_order` WHERE `user_id` LIKE '$UserName' AND `product_id` LIKE '$ProductID' ORDER BY `id`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `temp_order` SET  `user_id` = ?, `product_id` = ?, `item_price` = ?, `item_discount` = ?, `quantity` = ?, `added_date` = ?, `is_active` = ?, `customer_id` = ? WHERE `user_id` LIKE '$UserName' AND `product_id` LIKE '$ProductID'";
    } else {
        $sql = "INSERT INTO `temp_order` (`user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, `added_date`, `is_active`, `customer_id` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssssssss", $UserName, $ProductID, $ItemPrice, $ItemDiscount, $Quantity, $current_time, $is_active, $CustomerID);
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
