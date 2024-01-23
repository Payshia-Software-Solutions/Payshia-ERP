<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
$Cities = GetCities($link);
$first_name = $last_name = $gender = $status = $phone_number = $subject_id = $address_line1 = $address_line2 = $city_id = $email_address = $img_path = $class_category = $tutor_profile = $nic = "";
$is_active = $_POST['is_active'];
$user_type = "Officer";

$img_path = "no-image.png";

// `id`, `email`, `user_name`, `pass`, `first_name`, `last_name`, `sex`, `addressl1`, `addressl2`, `city`, `PNumber`, `WPNumber`, `created_at`, `user_status`, `acc_type`, `img_path` FROM `user_accounts`

$img_path = "no-image.png";
$UpdateKey = 0;
if (isset($_POST['UpdateKey']) && $_POST['UpdateKey'] !== "0") {
    $Tutor = GetAccounts($link)[$_POST['UpdateKey']];
    $first_name = $Tutor['first_name'];
    $last_name = $Tutor['last_name'];
    $gender = $Tutor['sex'];
    $address_line1 = $Tutor['addressl1'];
    $address_line2 = $Tutor['addressl2'];
    $city_id = $Tutor['city'];
    $phone_number = $Tutor['PNumber'];
    $email_address = $Tutor['email'];
    $password = $Tutor['pass'];
    $password = $Tutor['pass'];
    $img_path = $Tutor['img_path'];
    $user_type = $Tutor['acc_type'];
    $UpdateKey = $_POST['UpdateKey'];
    $nic = $_POST['nic_number'];
}


if ($is_active == 1) {
    $ButtonText = "Save & Active";
} else {
    $ButtonText = "Delete";
}

?>

<div class="row my-4">
    <div class="col-12">
        <form class="add-class-form" id="add-form" action="" method="POST">
            <h2>Add New User</h2>
            <?php
            if (isset($_POST['UpdateKey']) && $_POST['UpdateKey'] != 0) { ?>
                <img src="./assets/images/tutor/<?= $img_path ?>" class="tutor-image">
            <?php } ?>

            <p class="text-secondary mb-2 mt-3 border-bottom">Profile Information</p>
            <div class="row ">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="class-dates">Status</label>
                        <select id="status" name="status" required>
                            <option <?= ($status == 'Mr') ? 'selected' : '' ?> value="Mr">Mr</option>
                            <option <?= ($status == 'Miss') ? 'selected' : '' ?> value="Mrs">Miss</option>
                            <option <?= ($status == 'Mrs') ? 'selected' : '' ?> value="Mrs">Mrs</option>
                            <option <?= ($status == 'Rev') ? 'selected' : '' ?> value="Mrs">Rev</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="class-name">First name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" value="<?= $first_name ?>" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="class-name">Last name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?= $last_name ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-image">NIC Number</label>
                        <input value="<?= $nic ?>" type="text" class="" id="nic_number" name="nic_number" placeholder="Enter NIC Number">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="class-dates">Gender</label>
                        <select id="gender" name="gender" required>
                            <option <?= ($gender == 'Male') ? 'selected' : '' ?> value="Male">Male</option>
                            <option <?= ($gender == 'Female') ? 'selected' : '' ?> value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="class-dates">Email</label>
                        <input type="email" id="email_address" name="email_address" class="" placeholder="Enter the Email Address" required value="<?= $email_address ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-image">Profile Image</label>
                        <input type="file" class="" id="profile-image" name="profile-image">
                        <input type="hidden" class="" id="img_tmp" name="img_tmp" value="<?= $img_path ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-dates">Phone Number</label>
                        <input type="number" id="phone_number" name="phone_number" class="" placeholder="Enter the Phone Number" required value="<?= $phone_number ?>">
                    </div>
                </div>

            </div>

            <p class="text-secondary mb-2 mt-2 border-bottom">Address Information</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-dates">Address Line 1</label>
                        <input value="<?= $address_line1 ?>" type="text" id="address_line1" name="address_line1" class="" required placeholder="Enter the Address Line 1">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-dates">Address Line 2</label>
                        <input value="<?= $address_line2 ?>" type="text" id="address_line2" name="address_line2" class="" placeholder="Enter the Address Line 2" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class-dates">City</label>
                        <select id="city_id" name="city_id" required>
                            <option value="">Select City</option>
                            <?php
                            if (!empty($Cities)) {
                                foreach ($Cities as $City) {
                            ?>
                                    <option <?= ($city_id == $City['id']) ? 'selected' : '' ?> value="<?= $City['id'] ?>"><?= $City['name_en'] ?> - <?= $City['postcode'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <p class="text-secondary mb-2 mt-2 border-bottom">Authentication Details</p>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="class-dates">User Type</label>
                        <select id="user_type" name="user_type" required>
                            <option <?= ($user_type == 'Cashier') ? 'selected' : '' ?> value="Cashier">Cashier</option>
                            <option <?= ($user_type == 'Officer') ? 'selected' : '' ?> value="Officer">Officer</option>
                            <option <?= ($user_type == 'Admin') ? 'selected' : '' ?> value="Admin">Admin</option>
                            <option <?= ($user_type == 'Front-User') ? 'selected' : '' ?> value="Front-User">Front-User</option>
                            <option <?= ($user_type == 'Steward') ? 'selected' : '' ?> value="Steward">Steward</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="class-dates">Password</label>
                        <input value="" type="password" id="password" name="password" class="" <?= ($UpdateKey === 0) ? 'required' : '' ?> placeholder="Enter the Password">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="class-dates">Confirm Password</label>
                        <input value="" type="password" id="c_password" name="c_password" class="" placeholder="Enter the Password Again" <?= ($UpdateKey === 0) ? 'required' : '' ?>>
                    </div>
                </div>

            </div>

            <button type="button" class="submit-btn mt-4" onclick="SaveStudent ('<?= $is_active ?>','<?= $UpdateKey ?>') "><?= $ButtonText ?></button>
        </form>
    </div>

</div>

<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea#profile_description',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>