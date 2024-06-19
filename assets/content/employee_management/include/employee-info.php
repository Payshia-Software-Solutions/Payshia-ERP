<div class="text-center border-bottom mb-2">

    <img class="mt-3" style="width: 100px;" src="./assets/images/profile.png">
    <h2 class="mt-2 mb-0"><?= $employeeInfo['full_name'] ?> <span style="font-size:18px" class=""><i class="fa-solid fa-circle-check"></i></span></h2>
    <p class="text-secondary mb-0"><?= $positionListSelectValues[$employeeInfo['position']]['value'] ?></p>
    <h4><?= $employeeId ?></h4>

    <?php if ($buttonSet == 1) : ?>
        <div class="row my-3">
            <div class="col-12 text-end">
                <div class="row g-1 ">
                    <div class="col-6 col-md-3 d-flex">
                        <button type="button" onclick="AddNewEmployee('<?= $employeeId ?>')" class="btn btn-dark w-100 flex-fill"><i class="fa-solid fa-pencil"></i> Edit</button>
                    </div>
                    <div class="col-6 col-md-3 d-flex">
                        <button type="button" onclick="LinkUserAccount('<?= $employeeId ?>')" class="btn btn-dark w-100 flex-fill"><i class="fa-solid fa-user"></i> Link Users</button>
                    </div>

                    <div class="col-6 col-md-3 d-flex">
                        <button type="button" onclick="EditEducation('<?= $employeeId ?>')" class="btn btn-dark w-100 flex-fill"><i class="fa-solid fa-graduation-cap"></i> Education</button>
                    </div>

                    <div class="col-6 col-md-3 d-flex">
                        <button type="button" onclick="EditExperience('<?= $employeeId ?>')" class="btn btn-dark w-100 flex-fill"><i class="fa-solid fa-briefcase"></i> Experience</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<div class="row g-2 ">
    <div class="col-6 col-lg-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <i class="fa-solid fa-user-tag fa-1x"></i>
                <h5 class="mb-0 mt-1"><?= $employeeInfo['employee_type'] ?></h5>
                <p class="text-secondary mb-0">Employee Type</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <i class="fa-solid fa-building-user fa-1x"></i>
                <h5 class="mb-0 mt-1"><?= $workLocation[$employeeInfo['work_location']]['value'] ?></h5>
                <p class="text-secondary mb-0">Work Location</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body text-center">
                <i class="fa-solid fa-address-card fa-1x"></i>
                <h5 class="mb-0 mt-1"><?= $departmentList[$employeeInfo['department']]['value'] ?></h5>
                <p class="text-secondary mb-0">Department</p>
            </div>
        </div>
    </div>
</div>

<!-- Personal Information -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card ">
            <div class="card-body">
                <h4 class="border-bottom pb-2 mb-3">Personal Information</h4>
                <div class="row g-2">


                    <div class="col-md-4">
                        <h6 class="text-secondary">Full Name</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['full_name'] ?></h6>
                    </div>


                    <div class="col-md-4">
                        <h6 class="text-secondary">Name with Initials</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['name_with_initials'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">NIC #</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['national_identification_number'] ?></h6>
                    </div>


                    <div class="col-md-4">
                        <h6 class="text-secondary">Phone</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['phone_number'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Email Address</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['email'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Date of Birth</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['date_of_birth'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Gender</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['gender'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Married Status</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['married_status'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Current Address</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['address_line_1'] ?>, <?= $employeeInfo['address_line_2'] ?>, <?= $cityList[$employeeInfo['city']]['name_en'] ?></h6>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-secondary">Permanent Address</h6>
                    </div>
                    <div class="col-md-8">
                        <h6 class=""><?= $employeeInfo['permanent_address_line_1'] ?>, <?= $employeeInfo['permanent_address_line_2'] ?>, <?= $cityList[$employeeInfo['permanent_city']]['name_en'] ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Personal Information -->