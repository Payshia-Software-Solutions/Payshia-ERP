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
$statusId = $selectedArray['civil_status'];
$email_address = $selectedArray['e_mail'];
$first_name = $selectedArray['first_name'];
$last_name = $selectedArray['last_name'];
$nic_number = $selectedArray['nic'];
$birth_day = $selectedArray['birth_day'];
$phone_number = $selectedArray['telephone_1'];
$whatsapp_number = $selectedArray['telephone_2'];
$address_l1 = $selectedArray['address_line_1'];
$address_l2 = $selectedArray['address_line_2'];
$cityId = $selectedArray['city'];
$districtId = $selectedArray['district'];
$gender = $selectedArray['gender'];
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

            <form id="submit-form" method="post">

                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <p class="mb-0 text-secondary">Email Address</p>
                        <input type="text" class="form-control" name="email_address" id="email_address" value="<?= $email_address ?>" required>
                    </div>

                    <div class="col-6 col-md-2">
                        <p class="mb-0 text-secondary">Status</p>
                        <select class="form-control w-100" id="status_id" name="status_id" required>
                            <option <?= ($statusId == "Dr.") ? 'selected' : '' ?> value="Dr.">Dr.</option>
                            <option <?= ($statusId == "Mr.") ? 'selected' : '' ?> value="Mr." selected>Mr.</option>
                            <option <?= ($statusId == "Miss.") ? 'selected' : '' ?> value="Miss.">Miss.</option>
                            <option <?= ($statusId == "Mrs.") ? 'selected' : '' ?> value="Mrs.">Mrs.</option>
                            <option <?= ($statusId == "Rev.") ? 'selected' : '' ?> value="Rev.">Rev.</option>
                        </select>
                    </div>


                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">First Name</p>
                        <input type="text" class="form-control" name="fname" id="fname" value="<?= $first_name ?>">
                    </div>

                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">Last Name</p>
                        <input type="text" class="form-control" name="lname" id="lname" value="<?= $last_name ?>" required>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-12 col-md-2">
                        <p class="mb-0 text-secondary">NIC Number</p>
                        <input type="text" class="form-control" name="NicNumber" id="NicNumber" value="<?= $nic_number ?>" required>
                    </div>

                    <div class="col-6 col-md-2">
                        <p class="mb-0 text-secondary">Gender</p>
                        <select class="form-control w-100" id="gender" name="gender" required>
                            <option <?= ($gender == "Male") ? 'selected' : '' ?> value="Male">Male</option>
                            <option <?= ($gender == "Female") ? 'selected' : '' ?> value="Female">Female</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <p class="mb-0 text-secondary">Address Line 1</p>
                        <input type="text" class="form-control" name="addressL1" id="addressL1" value="<?= $address_l1 ?>" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <p class="mb-0 text-secondary">Address Line 2</p>
                        <input type="text" class="form-control" name="addressL2" id="addressL2" value="<?= $address_l2 ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">City</p>
                        <select class="form-control" name="city" id="city" required>
                            <?php
                            if (!empty($cityList)) {
                                foreach ($cityList as $selectedArray) {
                            ?>
                                    <option <?= ($selectedArray['id']  == $cityId) ? 'selected' : '' ?> value="<?= $selectedArray['id'] ?>"><?= $selectedArray['name_en'] ?> - <?= $selectedArray['name_si'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">Phone Number</p>
                        <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" value="<?= $phone_number ?>" required>
                    </div>

                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">WhatsApp Number</p>
                        <input type="tel" class="form-control" name="whatsAppNumber" id="whatsAppNumber" value="<?= $whatsapp_number ?>" required>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="mb-0 text-secondary">Birth Day</p>
                        <input type="date" class="form-control" name="birth_day" id="birth_day" value="<?= $birth_day ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-12 col-md-5">
                        <p class="mb-0 text-secondary">Full Name</p>
                        <input type="text" class="form-control" name="fullName" id="fullName" value="<?= $full_name ?>" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <p class="mb-0 text-secondary">Name with Initials</p>
                        <input type="text" class="form-control" name="nameWithInitials" id="nameWithInitials" value="<?= $name_with_initials ?>" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <p class="mb-0 text-secondary">Name on Certificate</p>
                        <input type="text" class="form-control" name="nameOnCertificate" id="nameOnCertificate" value="<?= $name_on_certificate ?>" required>
                    </div>
                </div>

                <div class="row  mt-3">
                    <div class="col-md-3 offset-md-9 text-end">
                        <button onclick="SaveProfileInfo('<?= $studentNumber ?>', '<?= $selectedCourse ?>')" class="btn btn-success form-control" type="button"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                    </div>
                </div>

            </form>


        </div>
    </div>
</div>