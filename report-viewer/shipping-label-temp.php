<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';
include '../include/lms-functions.php';

$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$ref_id = isset($_GET['ref_id']) && $_GET['ref_id'] !== '' ? $_GET['ref_id'] : null;

// Check if the required parameter is not set or has an empty value
if ($ref_id === null) {
    die("Invalid request. Please provide the 'ref_id' parameter with a non-empty value.");
}


$selectedArray = GetOrders()[$ref_id];
$Deliveries = GetDeliverySetting();

$delivery_id = $selectedArray['delivery_id'];
$trackingNumber = $selectedArray['tracking_number'];
$indexNumber = $selectedArray['index_number'];
$orderDate = ($selectedArray['order_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['order_date'])) : 'Not Set';
$packed_date = ($selectedArray['packed_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['packed_date'])) : 'Not Set';
$send_date = ($selectedArray['send_date'] != null) ? date("Y-m-d H:i", strtotime($selectedArray['send_date'])) : 'Not Set';
$current_status = $selectedArray['current_status'];
$delivery_partner = $selectedArray['delivery_partner'];
$course_code = $selectedArray['course_code'];
$estimate_delivery = $selectedArray['estimate_delivery'];
$full_name = $selectedArray['full_name'];
$street_address = $selectedArray['street_address'];
$city = $selectedArray['city'];
$district = $selectedArray['district'];
$phone_1 = $selectedArray['phone_1'];
$phone_2 = $selectedArray['phone_2'];
$is_active = $selectedArray['is_active'];
$received_date = $selectedArray['received_date'];
$package_weight =  $selectedArray['package_weight'];
$codAmount = $selectedArray['cod_amount'];

if ($current_status == 1) {
    $active_status = "Processing";
    $color = "danger";
} else if ($current_status == 2) {
    $active_status = "Packed";
    $color = "success";
} else if ($current_status == 3) {
    $active_status = "Delivered";
    $color = "dark";
}

$qrText = "https://pharmacollege.lk/track-order?trackingNumber=" . $trackingNumber;
$deliveryItem = $Deliveries[$delivery_id]['delivery_title'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - <?= $trackingNumber ?></title>
    <link rel="stylesheet" href="../assets/css/shipping-label-1.0.css">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css" />
</head>

<body>
    <div class="shipping-label p-4" style="background-image: url('../assets/images/shipping-label.jpg');">
        <div class="row">
            <div class="col-4 px-4">
                <img src="../assets/images/company/logo.png" style="width: 100%;">
            </div>
            <div class="col-8">
                <h4 class="company-title"><?= $CompanyInfo['company_name'] ?></h4>
                <h4 class="company-sub-title"><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?>, <?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></h4>
                <p class="mb-0"><?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p class="mb-0">Email: <?= $CompanyInfo['company_email'] ?></p>
                <p class="mb-0">Web: <?= $CompanyInfo['website'] ?></p>
            </div>
        </div>

        <hr class="mb-0">

        <div class="row">
            <div class="col-6">
                <p class="text-secondary mb-0">Sender Details</p>
                <h4 class="company-title" style="font-size: 15px;"><?= $CompanyInfo['company_name'] ?></h4>
                <p class="mb-0">Warehouse Pelmadulla</p>
                <p class="mb-0">0715 884 884</p>
                </h4>
            </div>

            <div class="col-6">
                <p class="text-secondary mb-0">Receiver Details</p>
                <h4 class="company-title"><?= $full_name ?></h4>

                <p class="mb-0"><?= $street_address ?></p>
                <p class="mb-0"><?= $city ?>, <?= $district ?></p>
                <p class="mb-0"><?= $phone_1 ?>, <?= $phone_2 ?></p>
                </h4>
            </div>


        </div>
        <hr class="mb-0">

        <div class="row">
            <div class="col-12 text-center">
                <p class="text-secondary mb-0 ">Tracking Number</p>
                <h3 class="tracking-title"><?= $trackingNumber ?></h3>
            </div>
        </div>


        <hr class="mb-0">

        <div class="row">
            <div class="col-12">

                <div class="row">
                    <div class="col-5">
                        <p class="mb-0">Item Code</p>
                    </div>
                    <div class="col-7">
                        <h5 class="px-3"><?= $deliveryItem ?></h5>
                    </div>
                </div>


                <div class="row">
                    <div class="col-5">
                        <p class="mb-0">Shipped Date</p>
                    </div>
                    <div class="col-7">
                        <h5 class="px-3"><?= $packed_date ?></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-5">
                        <p class="mb-0">Index Number</p>
                    </div>
                    <div class="col-7">
                        <h5 class="px-3"><?= $indexNumber ?></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-5">
                        <p class="mb-0">Weight</p>
                    </div>
                    <div class="col-7">
                        <h5 class="px-3"><?= $package_weight ?> Kg</h5>
                    </div>
                </div>

            </div>
        </div>

        <hr class="mb-0">

        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <p class="text-secondary mb-0">Remarks</p>
                    <h4 class="company-title">No Remarks</h4>
                </div>

                <div class="mb-3">
                    <p class="text-secondary mb-0">COD Amount</p>
                    <h4 class="company-title">LKR <?= number_format($codAmount, 2) ?></h4>
                </div>

            </div>
            <div class="col-6">
                <div class="text-center border-dark border-2 pt-3 w-50 rounded-3" style="border: 2px solid black;">
                    <img src="<?= generateQRCode($qrText) ?>" style="width: 20mm">
                    <div class="text-center bg-dark text-white mt-3">Scan Me</div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<script>
    window.print();
</script>