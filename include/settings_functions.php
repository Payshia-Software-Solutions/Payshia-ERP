<?php
function GetSetting($link, $location_id, $setting)
{
    $sql = "SELECT `id`, `location_id`, `setting`, `default_value`, `custom_value` FROM `setting_location` WHERE `location_id` LIKE '$location_id' AND `setting` LIKE '$setting'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $custom_value = $row['custom_value'];
            $default_value = $row['default_value'];
        }

        if ($custom_value == "") {
            return $default_value;
        } else {
            return $custom_value;
        }
    } else {
        return null;
    }
}


function GetDefaultSettingValues($link)
{
    $ArrayResult = array();
    $sql = 'SELECT `id`, `settingName`, `defaultValue` FROM `setting_default_values`';
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['settingName']] = $row;
        }
    }
    return $ArrayResult;
}


function UpdateSetting($link, $location_id, $setting, $value)
{
    $defaultValues = GetDefaultSettingValues($link);
    $default_value = $defaultValues[$setting]['defaultValue'];

    $sql = "SELECT * FROM `setting_location` WHERE `setting` LIKE '$setting' AND `location_id` LIKE '$location_id'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `setting_location` SET `default_value` = ?, `custom_value` = ? WHERE `setting` LIKE ? AND `location_id` LIKE ?";
        $errorTitle = "Updated";
    } else {
        $sql = "INSERT INTO `setting_location`(`default_value`, `custom_value`, `setting`, `location_id`) VALUES (?, ?, ?, ?)";
        $errorTitle = "Saved";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "ssss", $default_value, $value, $setting, $location_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Setting ' . $errorTitle . ' successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}



function GetUserDefaultValue($link, $userName, $setting)
{
    $ArrayResult = "";
    $sql = "SELECT `id`, `user_name`, `setting`, `value` FROM `user_default_values` WHERE `user_name` LIKE '$userName' AND `setting` LIKE '$setting'";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult = $row['value'];
        }
    }
    return $ArrayResult;
}


function UpdateUserDefaultValue($link, $userName, $setting, $value)
{
    $sql = "SELECT `id`, `user_name`, `setting`, `value` FROM `user_default_values` WHERE `user_name` LIKE '$userName' AND `setting` LIKE '$setting'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE `user_default_values` SET `value` = ? WHERE `user_name` LIKE ? AND `setting` LIKE ?";
    } else {
        $sql = "INSERT INTO `user_default_values` (`value`, `user_name`, `setting`) VALUES (?, ?, ?)";
    }

    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $value, $userName, $setting);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'User Setting updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}
