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



function UpdateSetting($link, $location_id, $setting, $value)
{
    $sql = "UPDATE `setting_location` SET `custom_value` = ? WHERE `setting` LIKE ? AND `location_id` LIKE ?";
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt_sql, "sss", $value, $setting, $location_id);
        if (mysqli_stmt_execute($stmt_sql)) {
            $error = array('status' => 'success', 'message' => 'Setting updated successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
        }
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $link->error);
    }
    return json_encode($error);
}
