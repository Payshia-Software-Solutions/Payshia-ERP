<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';


$location_id = $_GET['location_id'];

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$location_name = $Locations[$location_id]['location_name'];

$section_id = isset($_GET['section_id']) && $_GET['section_id'] !== '' ? $_GET['section_id'] : null;
$department_id = isset($_GET['department_id']) && $_GET['department_id'] !== '' ? $_GET['department_id'] : null;
$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? $_GET['category_id'] : null;
$dateInput = isset($_GET['queryDate']) && $_GET['queryDate'] !== '' ? $_GET['queryDate'] : null;

// Check if the required parameters are not set or have empty values
if ($section_id === null || $department_id === null || $category_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

$pageTitle = "Stock Balance Report - " . $location_name;
$reportTitle = "Stock Balance Report";
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
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Product #</th>
                        <th scope="col">Product</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Avg. Cost</th>
                        <th scope="col">Selling Price</th>
                        <th scope="col">Cost Value</th>
                        <th scope="col">Sell Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($Products)) {
                        $totalStockValue = $totalSellingValue = 0;
                        foreach ($Products as $selectedArray) {

                            if ($section_id != $selectedArray['section_id'] && $section_id != 'All') {
                                continue;
                            }
                            if ($department_id != $selectedArray['department_id'] && $department_id != 'All') {
                                continue;
                            }
                            if ($category_id != $selectedArray['category_id'] && $category_id != 'All') {
                                continue;
                            }

                            $product_id = $selectedArray['product_id'];
                            $stockBalance = GetStockBalanceByProductByLocationToDate($link, $product_id, $location_id, $dateInput);
                            if ($stockBalance == 0) {
                                continue;
                            }
                            $costPrice = GetCostPrice($link, $product_id);
                            $sellingPrice = GetSellingPrice($link, $product_id);
                            $stockValue = $stockBalance * $costPrice;
                            $stockSellingValue = $sellingPrice * $stockBalance;
                            $totalStockValue += $stockValue;
                            $totalSellingValue += $stockSellingValue;


                    ?>
                            <tr>
                                <td class="">#00<?= $selectedArray['product_id'] ?></td>
                                <td class=""><?= $selectedArray['product_name'] ?></td>
                                <td class="text-end"><?= number_format($stockBalance, 3) ?></td>
                                <td class="text-end"><?= formatAccountBalance($costPrice) ?></td>
                                <td class="text-end"><?= formatAccountBalance($sellingPrice) ?></td>
                                <td class="text-end"><?= formatAccountBalance($stockValue) ?></td>
                                <td class="text-end"><?= formatAccountBalance($stockSellingValue) ?></td>
                            </tr>

                    <?php
                        }
                    }
                    ?>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5"></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalStockValue) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalSellingValue) ?></td>
                    </tr>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="6">Profit</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalSellingValue - $totalStockValue) ?></td>
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