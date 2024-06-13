<?php

include __DIR__ . '/../../../../include/config.php'; // Database Configuration
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function GetDeliveryPartners()
{
    global $link;
    $ArrayResult = array();

    $sql = "SELECT * FROM `delivery_partners`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['partner_id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetPhoneCodes()
{
    global $link;
    $ArrayResult = array();

    $sql = "SELECT * FROM `countries`";

    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['phonecode']] = $row;
        }
    }
    return $ArrayResult;
}
