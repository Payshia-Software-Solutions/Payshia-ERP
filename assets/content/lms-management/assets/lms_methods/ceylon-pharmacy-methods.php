<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function GetUserRecoverAnswers()
{
    global $lms_link;

    // Initialize arrays to store results
    $arrayResult = array();

    // Get counts for each user
    $sql = "SELECT 
                student_id,
                SUM(CASE WHEN patient_status LIKE 'Pending' AND time < DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 1 ELSE 0 END) AS died_count,
                SUM(CASE WHEN patient_status LIKE 'Pending' AND time >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 1 ELSE 0 END) AS pending_count,
                SUM(CASE WHEN patient_status LIKE 'Recovered' THEN 1 ELSE 0 END) AS recovered_count
            FROM 
                care_start
            GROUP BY 
                student_id";

    $result = $lms_link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Store data for each user
            $arrayResult[$row['student_id']] = array(
                'student_id' => $row['student_id'],
                'died_count' => $row['died_count'],
                'pending_count' => $row['pending_count'],
                'recovered_count' => $row['recovered_count']
            );
        }
    }

    return $arrayResult;
}



function GetCeylonPharmacyPatients()
{
    global $lms_link;
    $arrayResult = array();

    $sql_inner = "SELECT * FROM `care_patient`";
    $result_inner = $lms_link->query($sql_inner);
    if ($result_inner->num_rows > 0) {
        while ($row = $result_inner->fetch_assoc()) {
            $arrayResult[] = $row;
        }
    }

    return $arrayResult;
}
