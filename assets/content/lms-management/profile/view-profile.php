<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$LoggedUser = $_POST['LoggedUser'];
$studentNumber = $_POST['studentNumber'];
$selectedCourse = $_POST['selectedCourse'];

$cityList = GetCities($link);
$DistrictList = getDistricts($link);

$batchStudents =  GetLmsStudents();
$selectedArray = $batchStudents[$studentNumber];



// Create Variables
$email_address = $selectedArray['e_mail'];
$first_name = $selectedArray['first_name'];
$last_name = $selectedArray['last_name'];
$nic_number = $selectedArray['nic'];
$phone_number = $selectedArray['telephone_1'];
$whatsapp_number = $selectedArray['telephone_2'];
$address_l1 = $selectedArray['address_line_1'];
$address_l2 = $selectedArray['address_line_2'];
$cityId = $selectedArray['city'];
$districtId = $selectedArray['district'];
$full_name = $selectedArray['full_name'];
$name_with_initials = $selectedArray['name_with_initials'];
$referenceId = $selectedArray['id'];
$name_on_certificate = $selectedArray['name_on_certificate'];
$student_name = $first_name . " " . $last_name;
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">User Information | <?= $studentNumber ?></h5>
            <p class="border-bottom pb-2"></p>

            <div class="row mt-3">
                <div class="col-6 col-md-4">
                    <p class="mb-0 text-secondary">Email Address</p>
                    <h6 class="mb-0"><?= $email_address ?></h6>

                </div>

                <div class="col-6 col-md-4">
                    <p class="mb-0 text-secondary">Student Details</p>
                    <h5 class="mb-0"><?= $student_name; ?></h5>
                    <p class="mb-0"><?= $nic_number; ?></p>
                    <p class="mb-0"><?= $address_l1; ?>, <?= $address_l2; ?></p>
                </div>

                <div class="col-6 col-md-4">
                    <p class="mb-0 text-secondary">Phone Number</p>
                    <p class="mb-0"><a class="mb-0 text-secondary" href="tel:<?= $phone_number ?>"><i class="fa-solid fa-phone clickable"></i></a> <?= formatPhoneNumber($phone_number) ?> </p>
                    <p class="mb-0"><a class="mb-0 text-secondary" href="<?= generateWhatsAppLink($phone_number) ?>" target="_blank"><i class="fa-brands fa-whatsapp clickable"></i></a> <?= formatPhoneNumber($whatsapp_number) ?> </p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-4">
                    <p class="mb-0 text-secondary">Full Name</p>
                    <h6 class="mb-0"><?= ($full_name != "") ? $full_name : "Not Set" ?></h6>
                </div>
                <div class="col-12 col-md-4">
                    <p class="mb-0 text-secondary">Name with Initials</p>
                    <h6 class="mb-0"><?= ($name_with_initials != "") ? $name_with_initials : "Not Set" ?></h6>
                </div>
                <div class="col-12 col-md-4">
                    <p class="mb-0 text-secondary">Name on Certificate</p>
                    <h6 class="mb-0"><?= ($name_on_certificate != "") ? $name_on_certificate : "Not Set" ?></h6>
                </div>
            </div>

            <div class="row mt-3 g-3">

                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">City</p>
                    <h6 class="mb-0"><?= $cityList[(int)$cityId]['name_en'] ?>, <?= $cityList[(int)$cityId]['postcode'] ?></h6>
                </div>
                <div class="col-6 col-md-2">
                    <p class="mb-0 text-secondary">District</p>
                    <h6 class="mb-0"><?= $DistrictList[$cityList[(int)$cityId]['district_id']]['name_en'] ?></h6>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-light" type="button" onclick="OpenEditProfile('<?= $studentNumber ?>', '<?= $selectedCourse ?>')"><i class="fa-solid fa-pencil"></i> Edit</button>
                </div>
            </div>


        </div>
    </div>
</div>