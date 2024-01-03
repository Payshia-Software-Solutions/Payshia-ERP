<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/lms-functions.php';

$refId = $_POST['refId'];
$selectedArray = GetTemporaryUsers()[$refId];
$CourseBatches = getLmsBatches();
$cityList = GetCities($link);
$DistrictList = getDistricts($link);
// Create Variables
$email_address = $selectedArray['email_address'];
$first_name = $selectedArray['first_name'];
$last_name = $selectedArray['last_name'];
$nic_number = $selectedArray['nic_number'];
$phone_number = $selectedArray['phone_number'];
$whatsapp_number = $selectedArray['whatsapp_number'];
$address_l1 = $selectedArray['address_l1'];
$address_l2 = $selectedArray['address_l2'];
$paid_amount = $selectedArray['paid_amount'];
$approved_status = $selectedArray['aprroved_status'];
$cityId = $selectedArray['city'];
$districtId = $selectedArray['district'];
$full_name = $selectedArray['full_name'];
$name_with_initials = $selectedArray['name_with_initials'];
$referenceId = $selectedArray['id'];
$name_on_certificate = $selectedArray['name_on_certificate'];
$student_name = $first_name . " " . $last_name;

if ($approved_status == "Not Approved") {
    $color = "danger";
} else if ($approved_status == "Approved") {
    $color = "success";
} else {
    $color = "success";
}

?>
<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-0">User Information | REF #<?= $referenceId ?></h5>
            <p class="border-bottom pb-2"></p>

            <div class="row mt-3">
                <div class="col-6 col-md-4">
                    <p class="mb-0 text-secondary">Email Address</p>
                    <h6 class="mb-0"><?= $email_address ?></h6>
                    <span class="badge bg-<?= $color ?>"><?= $approved_status ?></span>
                    <div class="mt-2">
                        <button class="btn btn-light" type="button" onclick="OpenEditUserInfo('<?= $referenceId ?>')"><i class="fa-solid fa-pencil"></i> Edit</button>
                    </div>
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

            <div class="row mt-3">
                <div class="col-6 col-md-2">
                    <p class="mb-0 text-secondary">Paid Amount</p>
                    <h6 class="mb-0"><?= number_format($paid_amount, 2) ?></h6>
                </div>
                <div class="col-6 col-md-3">
                    <p class="mb-0 text-secondary">City</p>
                    <h6 class="mb-0"><?= $cityList[(int)$cityId]['name_en'] ?>, <?= $cityList[(int)$cityId]['postcode'] ?></h6>
                </div>
                <div class="col-6 col-md-2">
                    <p class="mb-0 text-secondary">District</p>
                    <h6 class="mb-0"><?= $DistrictList[$cityList[(int)$cityId]['district_id']]['name_en'] ?></h6>
                </div>
                <div class="col-6 col-md-5">
                    <p class="mb-0 text-secondary">Selected Course</p>
                    <h6 class="mb-0"><?= $selectedArray['selected_course'] ?></h6>
                </div>
            </div>

            <div class="row g-2 mt-3">
                <div class="col-md-4">
                    <select class="form-control" name="studentBatch" id="studentBatch" required>
                        <option value="">Select Batch</option>
                        <?php
                        if (!empty($CourseBatches)) {
                            foreach ($CourseBatches as $selectedArray) {
                        ?>
                                <option value="<?= $selectedArray['course_code'] ?>"><?= $selectedArray['course_code'] ?> - <?= $selectedArray['course_name'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>

                </div>
                <div class="col-md-2">
                    <select class="form-control" name="userLevel" id="userLevel" required>
                        <option value="">User Level</option>
                        <option value="Student" selected>Student</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button onclick="UpdateUserStatus('<?= $refId ?>', 'Rejected')" class="btn btn-danger w-100 form-control" type="button"><i class="fa-solid fa-user-xmark"></i> Reject</button>
                </div>
                <div class="col-md-3">
                    <button onclick="UpdateUserStatus('<?= $refId ?>', 'Approved')" class="btn btn-dark w-100 form-control" type="button"><i class="fa-solid fa-user-check"></i> Approve</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#studentBatch').select2()
</script>