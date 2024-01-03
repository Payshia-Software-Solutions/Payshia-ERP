<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
include '../../../../../include/finance-functions.php';
include '../../../../../include/reporting-functions.php';
include '../../../../../include/lms-functions.php';
include '../../../../../include/lms-reports.php';

$Locations = GetLocations($link);
$studentBatch = $_POST['studentBatch'];
$default_location = $_POST['default_location'];
$location_name = $Locations[$default_location]['location_name'];
$AllStudents = GetLmsStudents();
$cities = GetCities($link);
$districtsList = getDistricts($link);
$studentBathes = getLmsBatches();
$allUserEnrollments =  getAllUserEnrollments();
?>

<div class="table-responsive">
    <table class="table table-striped table-fixed table-hover" id="report-table">
        <thead>
            <tr>
                <th scope="col">Index Number</th>
                <th scope="col">Student Name</th>
                <th scope="col">Address</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Name with Initials</th>
                <th scope="col">Name On Certificate</th>
                <th scope="col">Enrollments</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($AllStudents)) {
                foreach ($AllStudents as $selectedArray) {
                    $enrollmentArray = array();
                    $cityName = $districtName =  $postalCode =  $districtId = "";

                    $studentId = $selectedArray['student_id'];
                    $indexNumber = $selectedArray['username'];
                    $studentName = ucwords($selectedArray['first_name'] . " " . $selectedArray['last_name']);
                    $addressL1 = $selectedArray['address_line_1'];
                    $addressL2 = $selectedArray['address_line_2'];
                    $city = $selectedArray['city'];
                    $telephone_1 = $selectedArray['telephone_1'];
                    $nameWithInitials = $selectedArray['name_with_initials'];
                    $nameOnCertificate = $selectedArray['name_on_certificate'];

                    if (isset($cities[$city]['name_en'])) {
                        $cityName = $cities[$city]['name_en'];
                        $postalCode = $cities[$city]['postcode'];
                        $districtId = $cities[$city]['district_id'];
                    }

                    if (isset($districtsList[$districtId]['name_en'])) {
                        $districtName = $districtsList[$districtId]['name_en'];
                    }

                    // Use array_filter to filter out elements with the specified student_id
                    $enrollmentArray = array_filter($allUserEnrollments, function ($item) use ($studentId) {
                        return $item['student_id'] == $studentId;
                    });

                    if ($studentBatch != "0") {
                        $courseAvailability = array_filter($enrollmentArray, function ($item) use ($studentBatch) {
                            return $item['course_code'] == $studentBatch;
                        });


                        $conditionSatisfied = false;
                        if (empty($courseAvailability)) {
                            $conditionSatisfied = true;
                        }

                        if ($conditionSatisfied) {
                            continue; // Continue the main loop when the condition is satisfied
                        }
                    }
            ?>
                    <tr>
                        <td><?= $indexNumber ?></td>
                        <td><?= $studentName ?></td>
                        <td><?= $addressL1 ?>, <?= $addressL2 ?>, <?= $cityName ?>, <?= $districtName ?>, <?= $postalCode ?></td>
                        <td><?= $telephone_1 ?></td>
                        <td><?= $nameWithInitials ?></td>
                        <td><?= $nameOnCertificate ?></td>
                        <td class="text-center">
                            <?php
                            if (!empty($enrollmentArray)) {
                                foreach ($enrollmentArray as $enrollment) {
                            ?>
                                    <p class="mb-0">
                                        <?= $enrollment['course_code'] ?>
                                    </p>
                            <?php
                                }
                            }
                            ?>
                        </td>

                    </tr>

            <?php
                }
            }
            ?>
        </tbody>

    </table>


</div>

<script>
    $(document).ready(function() {
        $('#report-table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', `colvis`],
            ordering: false,
            // searching: false, // Disable search input   
        });
    });
</script>