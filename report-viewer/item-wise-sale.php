<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$fromDate = isset($_GET['from-date-input']) && $_GET['from-date-input'] !== '' ? $_GET['from-date-input'] : null;
$toDate = isset($_GET['to-date-input']) && $_GET['to-date-input'] !== '' ? $_GET['to-date-input'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromDate === null || $toDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

$itemWiseSale = GetItemWiseSale($link, $fromDate, $toDate, $location_id);

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;
$invoiceSales = getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);

$location_name = $Locations[$location_id]['location_name'];
$fromDate = new DateTime($fromDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');

$toDate = new DateTime($toDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

// Report Detail
$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

$pageTitle = "Item Wise Sale - " . $location_name;
$reportTitle = "Item Wise Sale";


if (!empty($invoiceSales)) {
    foreach ($invoiceSales as $selectedArray) {
        $subTotal += $selectedArray['inv_amount'];
        $discountAmount += $selectedArray['discount_amount'];
        $serviceCharge += $selectedArray['service_charge'];
        $grandTotal += $selectedArray['grand_total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/report-viewer.css">

</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                    <tr>
                        <th>From Date</th>
                        <td class="text-end"><?= $formattedFromQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td class="text-end"><?= $formattedToQueryDate ?></td>
                    </tr>
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Item Price</th>
                        <th scope="col">Item Discounts</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSale = $totalDiscount = $rowCount = $totalCostValue = 0;
                    if (!empty($itemWiseSale)) {
                        foreach ($itemWiseSale as $selectedArray) {
                            $product_name = $Products[$selectedArray['product_id']]['product_name'];
                            $quantity = $selectedArray['total_quantity'];
                            $costPrice = $selectedArray['cost_price'];
                            $itemPrice = $selectedArray['item_price'];
                            $itemDiscount = $selectedArray['total_discounts'];
                            $totalValue = $quantity * $itemPrice;
                            $totalCostValue += $quantity * $costPrice;

                            // Total
                            $totalSale += $totalValue;
                            $totalDiscount += $itemDiscount;
                            $rowCount++;
                    ?>
                            <tr>
                                <td class=""><?= $rowCount ?></td>
                                <td class=""><?= MakeFormatProductCode($selectedArray['product_id']) ?> - <?= $product_name ?></td>
                                <td class="" style="max-width: 200px;"><?= $quantity ?></td>
                                <td class="text-end"><?= number_format($itemPrice, 3) ?></td>
                                <td class="text-end"><?= number_format($itemDiscount, 3) ?></td>
                                <td class="text-end text-bold"><?= number_format($totalValue, 3) ?></td>
                            </tr>

                    <?php

                        }
                    }

                    $grandTotalSale = $totalSale - $discountAmount + $serviceCharge;
                    $profitValue = $grandTotalSale - ($totalCostValue + $discountAmount + $serviceCharge * 0.8);
                    ?>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="4">Total</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalDiscount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalSale) ?></td>
                    </tr>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Bill Discount</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($discountAmount) ?></td>
                    </tr>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Charge</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($serviceCharge) ?></td>
                    </tr>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Total Sale</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($grandTotalSale) ?></td>
                    </tr>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Total Cost</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalCostValue) ?></td>
                    </tr>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Profit</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($profitValue) ?></td>
                    </tr>


                </tbody>
            </table>
        </div>

        <script>
            window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>