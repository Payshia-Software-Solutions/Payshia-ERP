<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$promotionList = [['test'], ['test2']];
$ActiveCount = count($promotionList);

?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-clock icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Offers</p>
                <h1><?= $ActiveCount ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-9 text-end mt-4 mt-md-0">
        <button class="btn btn-dark" type="button" onclick="AddNewOffer() "><i class="fa-solid fa-plus"></i> Add New Offer</button>
    </div>
</div>


<div class="row mt-4">
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-3 mt-0">Available Promotions</div>

        <div class="row g-2">
            <?php
            if (!empty($promotionList)) {
                foreach ($promotionList as $selectedArray) {

                    $statusColor = "primary";
                    $active_status = "Active";
            ?>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">

                                <div class="row g-2">
                                    <div class="col-md-9">
                                        <h4 class="mb-0 fw-bold">Happy Hour</h4>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="badge bg-<?= $statusColor ?>"><?= $active_status ?></span>
                                    </div>
                                </div>

                                <div class="border-bottom pb-2"></div>

                                <div class="bg-light rounded-3 mt-2 p-3 mb-2">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <p class="text-secondary pb-1 border-bottom ">Start Time</p>
                                            <h6 class="mb-2"><i class="fa-solid fa-calendar-days"></i> <?= date('d-m-Y') ?></h6>
                                            <h6 class="mb-0"><i class="fa-solid fa-clock z"></i> 11.00 AM</h6>
                                        </div>

                                        <div class="col-6">
                                            <p class="text-secondary pb-1 border-bottom ">End Time</p>
                                            <h6 class="mb-2"><i class="fa-solid fa-calendar-days"></i> <?= date('d-m-Y') ?></h6>
                                            <h6 class="mb-0"><i class="fa-solid fa-clock z"></i> 12.00 AM</h6>
                                        </div>

                                        <div class="col-12">
                                            <span class="badge bg-primary">Monday</span>
                                            <span class="badge bg-danger">Tuesday</span>
                                            <span class="badge bg-success">Wednesday</span>
                                            <span class="badge bg-warning">Thursday</span>
                                            <span class="badge bg-dark">Friday</span>
                                            <span class="badge bg-info">Saturday</span>
                                            <span class="badge bg-secondary">Sunday</span>
                                        </div>

                                        <div class="bg-white rounded-3">
                                            <div class="col-12">
                                                <h1 class="fw-bolder promotion-text my-3 text-dark text-center">10% Off</h1>
                                            </div>
                                        </div>


                                        <!-- Bank Promotion  -->
                                        <div class="col-6">
                                            <p class="border-bottom mb-2">Bank Promotion</p>
                                            <div class="bg-white rounded-3 p-2">
                                                <h6 class="fw-bolder mb-0"><i class="fa-solid fa-building-columns"></i> Sampath Bank</h6>
                                            </div>

                                        </div>

                                        <div class="col-6">
                                            <p class="border-bottom mb-2">Card Type</p>
                                            <div class="bg-white rounded-3 p-2">
                                                <h6 class="fw-bolder mb-0"><i class="fa-solid fa-credit-card"></i> Master Card</h6>
                                            </div>
                                        </div>
                                        <!-- End of Bank Promotion -->

                                        <div class="col-12 d-none">

                                            <p class="border-bottom mb-2">Selected Products</p>

                                            <!-- Promotion Item List -->
                                            <div class="bg-white rounded-3 p-2">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h6 class="mb-1"><i class="fa-solid fa-tag"></i> Nasi Goreng</h6>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="mb-1"><i class="fa-solid fa-tag"></i> Cheesy Pasta</h6>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="mb-1"><i class="fa-solid fa-tag"></i> Fish Patty</h6>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="mb-1"><i class="fa-solid fa-tag"></i> Chicken Samosa</h6>
                                                    </div>

                                                    <p class="text-end mb-0"><a class=" text-primary" href="#">More Items</a></p>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-6 col-md-4">
                                            <p class="border-bottom mb-2">Loyalty Type</p>
                                            <div class="bg-white rounded-3 p-2">
                                                <h6 class="mb-1"><i class="fa-solid fa-face-smile"></i> Gold</h6>
                                            </div>
                                        </div>

                                        <div class="col-6 col-md-4">
                                            <p class="border-bottom mb-2">Min Amount</p>
                                            <div class="bg-white rounded-3 p-2">
                                                <h6 class="mb-1"><i class="fa-solid fa-money-bill"></i> 2000.00</h6>
                                            </div>
                                        </div>

                                        <div class="col-6 col-md-4">
                                            <p class="border-bottom mb-2">Max Discount</p>
                                            <div class="bg-white rounded-3 p-2">
                                                <h6 class="mb-1"><i class="fa-solid fa-money-bill"></i> 1000.00</h6>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="border border-1 rounded-3 p-2 mb-2">
                                    <p class="mb-0">This offer will add <span class="fw-bold">10% off</span> add discounts for Any bill between <span class="fw-bold">11:00 to 12:00</span></p>
                                </div>

                                <div class="row g-2">
                                    <div class=" col-4 d-flex">
                                        <button type="button" class="btn btn-success w-100 flex-fill"><i class="fa-solid fa-pause"></i> Hold</button>
                                    </div>
                                    <div class="col-4 d-flex">
                                        <button type="button" class="btn btn-danger w-100 flex-fill"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </div>
                                    <div class="col-4 d-flex">
                                        <button type="button" class="btn btn-warning w-100 flex-fill"><i class="fa-solid fa-flag-checkered"></i> End</button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

        </div>

    </div>

    <div class="col-md-4">
        <div class="table-title font-weight-bold mb-3 mt-0">Expired Promotions</div>

        <div class="row">

            <div class="col-6">
                <div class="card">
                    <div class="card-body">

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>